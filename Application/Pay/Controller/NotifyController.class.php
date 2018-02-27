<?php
namespace Pay\Controller;
use Think\Controller;

class NotifyController extends Controller{
	/* 商城订单支付成功通知 */
	public function shop(){
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $pay_id = trim($postObj->attach);
        $total_fee = trim($postObj->cash_fee)/100;
		$shop_order = M('shop_order');$shop_order_detail = M('shop_order_detail');$shop_goods = M('shop_goods');
		$order = $shop_order -> getByPay_id($pay_id);
		if($order['is_true'] == 1){
			echo '<xml>
				   <return_code><![CDATA[SUCCESS]]></return_code>
				   <return_msg><![CDATA[OK]]></return_msg>
				</xml>';
			return;
		}
		$user_id = trim($order['user_id']);
		$openid = M('users') -> getFieldByUser_id($user_id,'openid');//openid信息
		$good_name = "";$good_num = 0;$add_jifen = 0;
		/* 查询订单详情 */
		$order_info = $shop_order_detail -> field("good_id,good_name,good_profit,good_num,pay_id,good_jifen,jifen_profit") -> where(" pay_id = '$pay_id' ") -> select();
		foreach($order_info as $v){
			$good_name .=$v['good_name'].".";
			$good_num =$good_num + $v['good_num'];
			/* 减库存，送积分 */
			$shop_goods -> where("good_id = '$v[good_id]'") -> setDec('number',$v['good_num']);
			$add_jifen = $add_jifen + $v['good_jifen']*$v['good_num'];
		}
		$template = A("Pay/Template");
		
		if($add_jifen > 0){
			/* 增加积分，记录 */
			M('users') -> where("user_id = '$user_id' ")->setInc('jifen',$add_jifen);
			$jifen_data = array(
				'number'=>$add_jifen,
				'user_id'=>$user_id,
				'type'=>6,//购买商品赠送返还，6
				'time'=>time()
			);
			M('jifen') -> add($jifen_data);
			$template->send_jifen($add_jifen,$openid);
		}
		/* 发送模板消息通知 */	
		$url = 'http://'.$_SERVER['SERVER_NAME'].U('/user/center/order');
		$template->send_shop($order['order_sn'],$good_name.'*'.$good_num,C('SHOP_ORDER_TYPE2'),$openid,$url);		
		/* 更改订单付款状态 */
		$shop_order -> where(" pay_id = '$pay_id' ") ->save(array('is_true'=>1,'pay_time'=>time()));		
		/* 减少商品库存 */	
		//分销处理
		$fenxiao = A("Xigua/Fenxiao");
		$fenxiao -> shop_deal($user_id,$order_info);	
		/* 引入公排复制 */
		$gongpai_info = F('gongpai_info','',DATA_ROOT);
		if($gongpai_info['gongpai_switch'] == 1){
			$state = 0;
			switch($gongpai_info['gongpai_exist']){
				case 1:
				$state = 1;
				break;
				case 2:
				foreach($order_info as $v){
					if($v['good_id'] == $gongpai_info['gongpai_good_id']){
						$state = 1;
						break;
					}
				}
				break;
			}
			if($state == 1){
				$gongpai = A("Xigua/Gongpai");
				$gongpai -> __construct($user_id,$order['order_id']);
				$gongpai -> index();
			}
			
		}
		
		
		echo '<xml>
			   <return_code><![CDATA[SUCCESS]]></return_code>
			   <return_msg><![CDATA[OK]]></return_msg>
			</xml>';
		
	}
	
	//充值成功通知
	public function add_money(){
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $user_id = trim($postObj->attach);
        $order_sn = trim($postObj->out_trade_no);
        $total_fee = trim($postObj->cash_fee)/100;
		$data = array(
			'user_id'=>$user_id,
			'desc'=>'会员钱包充值',
			'order_sn'=>$order_sn,
			'time'=>time(),
			'type'=>2,
			'fee'=>$total_fee,
		);
		M('broke_record')->add($data);
		$users = M('users');
		$users->where("user_id = '$user_id'")->setInc('shop_income',$total_fee);
		$user_info = $users ->field("shop_income,openid")->where("user_id = '$user_id'")->find();//openid信息
		/* 模板消息 */
		$template = A("Pay/Template");
		$template -> agent_chongzhi($total_fee,$user_info['openid'],$user_info['shop_income']);
		echo '<xml>
		   <return_code><![CDATA[SUCCESS]]></return_code>
		   <return_msg><![CDATA[OK]]></return_msg>
		</xml>';
		exit;
		
	}
	
	//购买代金券通知
	public function daijin(){
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $pay_id = trim($postObj->attach);
        $order_sn = trim($postObj->out_trade_no);
        $total_fee = trim($postObj->cash_fee)/100;
		$daijin_order = M('daijin_order');
		$order_info = $daijin_order -> getByPay_id($pay_id);
		if($order_info == null || $order_info['is_true'] == 1){
			echo '<xml>
			   <return_code><![CDATA[SUCCESS]]></return_code>
			   <return_msg><![CDATA[OK]]></return_msg>
			</xml>';
			exit;
		}
		/* 更改订单状态，增加销量 */
		$daijin_order ->where("pay_id = '$pay_id'")->setField('is_true',1);
		$daijin_id = $order_info['daijin_id'];$user_id = $order_info['user_id'];
		M('shop_daijin')->where("daijin_id = '$daijin_id' ")->setInc('sale_number');
		
		$user_info = M('users') ->field("openid")->where("user_id = '$user_id'")->find();//openid信息
		F('tset_234',$user_info,DATA_ROOT);
		/* 通知消息 */
		$weixin = A("Wxapi/Weixin");
		$url = 'http://'.$_SERVER['SERVER_NAME'].U('/user/center/order');
		$daijin_info = M('shop_daijin') -> where("daijin_id = '$daijin_id'")->find();
		if($daijin_info['daijin_rule'] == 1){$str1 = "无门槛使用";}else{$str1 = "满".$daijin_info['daijin_line']."元使用";}
		if($daijin_info['date_switch'] == 1){$str2 = "永久有效";}else{$str2 = date("Y年m月d日 H时i分",$daijin_info['daijin_date']);}
		$word_url = 'http://'.$_SERVER['HTTP_HOST'].U('user/daijin/center');
		$weixin->send_word($user_info['openid'],$daijin_info['daijin_name'].'元代金券购买成功！\n\n须知：商城消费'.$str1.'\n有效期：'.$str2."\n\n<a href='".$word_url."'>查看详情</a>");
		
		/* 引入公排复制 */
		$gongpai_info = F('gongpai_info','',DATA_ROOT);
		if($gongpai_info['gongpai_switch'] == 1){
			$state = 0;
			switch($gongpai_info['gongpai_exist']){
				case 3:
				if($daijin_id == $gongpai_info['daijin_id']){$state = 1;}else{$state = 0;}
				break;
				default:
				$state = 0;
				break;
			}
			if($state == 1){
				$gongpai = A("Xigua/Gongpai");
				$gongpai -> __construct($user_id,'daijin');
				$gongpai -> index();
			}
		}
		
		echo '<xml>
		   <return_code><![CDATA[SUCCESS]]></return_code>
		   <return_msg><![CDATA[OK]]></return_msg>
		</xml>';
		exit;
		
	}
	
	//打赏成功通知
	public function dashang(){
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $attach = trim($postObj->attach);
        $order_sn = trim($postObj->out_trade_no);
        $total_fee = trim($postObj->cash_fee)/100;
        $openid = trim($postObj->openid);
		$arr = explode(',',$attach);
		$user_id = $arr[0];
		$new_id = $arr[1];
		$shang_user_id = $arr[2];
		/* user_id = 0  type = 4 是赞赏的平台收入 */
		$data = array(
			'user_id'=>$user_id,
			'desc'=>'分享文章得到赞赏',
			'order_sn'=>$order_sn,
			'time'=>time(),
			'type'=>4,//记录文章打赏收入
			'fee'=>$total_fee,
		);
		M('broke_record')->add($data);
		$shang_data = array(
			'user_id'=>$shang_user_id,
			'new_id'=>$new_id,
			'uid'=>$user_id,
			'order_sn'=>$order_sn,
			'time'=>time(),
			'fee'=>$total_fee,
		);
		M('news_shang') -> add($shang_data);
		if($user_id > 0){
			M('users') -> where("user_id = '$user_id'") -> setInc('shop_income',$total_fee);
			$user_info = M('users') ->field("openid")->where("user_id = '$user_id'")->find();//openid信息
			/* 模板消息 */
			$template = A("Pay/Template");
			$template -> send_hongbao($total_fee,$user_info['openid'],'分享文章得到赞赏','','dashang');
		}
		
		echo '<xml>
		   <return_code><![CDATA[SUCCESS]]></return_code>
		   <return_msg><![CDATA[OK]]></return_msg>
		</xml>';
		exit;
		
	}
}