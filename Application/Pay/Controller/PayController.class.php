<?php
namespace Pay\Controller;
use Think\Controller;

class PayController extends Controller{
	/* 商城订单支付页 */
	function shop(){
		if(!$_GET['pay_id']){exit;}else{$pay_id = $_GET['pay_id'];}
		$shop_order = M('shop_order');$shop_order_detail = M('shop_order_detail');$weixin = A("Wxapi/Weixin");
		//查询是否已生成prepay_id
		$order = $shop_order -> getByPay_id($pay_id);
		if($order == null){
			//支付订单信息
			//exit('1');
			$order_info = $shop_order_detail -> where(" pay_id = '$pay_id' ") -> select();
			if($order_info == null){exit('<div style="font-size:4em;text-align:center;margin:80px;">订单已超时关闭！</div>');}
			/* 判断订单是否过期 */
			if(time() - $order_info[0]['time'] > 7200){exit('<div style="font-size:4em;text-align:center;margin:80px;">订单已超时关闭！</div>');}
			//订单总金额
			$total_fee = 0;$good_name = "";$good_num = 0;
			$shop_goods = M('shop_goods');$good_pic = M('good_pic');
			foreach($order_info as $v){
				$total_fee = $total_fee + $v['good_price']*$v['good_num'];
				$good_name .=$v['good_name'].".";
				$good_num =$good_num + $v['good_num'];
			}
			$out_trade_no = $order_info[0]['order_sn'];
			$notify_url = "http://".$_SERVER['SERVER_NAME'].U('/Pay/Notify/shop');//交易成功后通知地址
			$openid = M('users') -> getFieldByUser_id($order_info[0]['user_id'],'openid');//openid信息
			$order_id = $order_info[0]['order_id'];
			/* 确定代金券信息 */
			$daijin_type = 0;
			if(I('get.daijin')){
				$daijin_order = M('daijin_order');$shop_daijin = M('shop_daijin');
				$user_daijin = $daijin_order -> getByOrder_id(I('get.daijin'));
				if($user_daijin != null && $user_daijin['state'] == 0 && $user_daijin['user_id'] == $order_info[0]['user_id']){
					/* 确定是本人且代金券没有使用 */
					$daijin_info = $shop_daijin -> getByDaijin_id($user_daijin['daijin_id']);
					/* 判断代金券是否匹配 ,是否过期，金额是否可以使用*/
					if($daijin_info['date_switch'] == 0 && $daijin_info['daijin_date'] < time()){
						/* 只有这一种情况是过期的 */
					}else{
						if($daijin_info['daijin_rule'] == 2 && $daijin_info['daijin_line'] > $total_fee){
							/* 这一种情况是规则金额不能用 */
						}else{
							$total_fee = $total_fee - $daijin_info['daijin_name'];$daijin_type = 1;
							if($total_fee <= 0){echo "订单金额小于等于0，无法进行支付";exit;}
							$daijin_order -> where("order_id = '$_GET[daijin]'") -> setField('state',1);
						}
					}
				}
			}
			
			$prepay_id = $weixin -> get_prepay_id($openid,$total_fee*100,$out_trade_no,$notify_url,$pay_id,$good_name);
			$data = array(
				'pay_id'=>$pay_id,
				'order_sn'=>$out_trade_no,
				'total_fee'=>$total_fee,
				'time'=>$order_info[0]['time'],
				'user_id'=>$order_info[0]['user_id'],
				'prepay_id'=>$prepay_id
			);
			if($daijin_type == 1){$data['daijin_id'] = I('get.daijin');}
			/* 发送订单模板消息 */
			$template = A("Pay/Template");
			$url = 'http://'.$_SERVER['SERVER_NAME'].U('/pay/pay/shop').'?pay_id='.$pay_id;
			$template->send_shop($out_trade_no,$good_name.'*'.$good_num,C('SHOP_ORDER_TYPE1'),$openid,$url);
			$shop_order -> add($data);
			//模版消息参数
			
		}else{
			$prepay_id = $order['prepay_id'];
			$order_info = $shop_order_detail -> where(" pay_id = '$pay_id' ") -> select();
			$total_fee = $order['total_fee'];
		}
		$paysign = $weixin->paysign($prepay_id);
		$signPackage=$weixin->getSignPackage();
		$this->assign("signPackage",$signPackage);
		$this->assign("paysign",$paysign);
		$this->assign("order_info",$order_info);
		$this->assign("total_fee",$total_fee);
		$this->display();
	}
	
	/* 代金券支付 */
	function daijin(){
		if(!$_GET['daijin_id']){exit;}else{$daijin_id = $_GET['daijin_id'];}
		if(!session('xigua_user_id')){exit;}else{$user_id = session('xigua_user_id');}
		$shop_daijin = M('shop_daijin');$daijin_order = M('daijin_order');$weixin = A("Wxapi/Weixin");
		//删除2小时前的订单
		$time = time() - 7200;
		$daijin_order -> where(" is_true = 0 and time < '$time' ") -> delete();
		$order_info = $daijin_order -> where("user_id = '$user_id' and daijin_id = '$daijin_id' and is_true = 0 ") -> find();
		$daijin_info = $shop_daijin -> getByDaijin_id($daijin_id);
		if($daijin_info == null){exit;}
		/* 判断代金券是否还有库存 */
		$daijin_info['now_number'] = $daijin_info['daijin_number'] - $daijin_info['sale_number'];
		if($daijin_info['now_number'] <= 0){$this->error('代金券已售罄！',U('/user/daijin/index')."?daijin_id=".$daijin_id);exit;}
		if($order_info == null){
			//订单总金额
			$total_fee = $daijin_info['daijin_fee'];$good_name = $daijin_info['daijin_name']."元商城代金券";
			$pay_id = $daijin_order -> max('pay_id');$pay_id++;
			$out_trade_no = 'daijin'.date("Y").time().$user_id;
			$notify_url = "http://".$_SERVER['SERVER_NAME'].U('/Pay/Notify/daijin');//交易成功后通知地址
			$openid = M('users') -> getFieldByUser_id($user_id,'openid');//openid信息
			$prepay_id = $weixin -> get_prepay_id($openid,$total_fee*100,$out_trade_no,$notify_url,$pay_id,$good_name);
			/* 保存订单信息 */
			$order_data = array(
				'user_id'=>$user_id,
				'daijin_id'=>$daijin_id,
				'total_fee'=>$total_fee,
				'time'=>time(),
				'prepay_id'=>$prepay_id,
				'pay_id'=>$pay_id
			);
			$daijin_order -> add($order_data);
		}else{
			$prepay_id = $order_info['prepay_id'];
		}
			
		
			
			/* 发送订单模板消息
			$template = A("Pay/Template");
			$url = 'http://'.$_SERVER['SERVER_NAME'].U('/pay/pay/shop').'?pay_id='.$pay_id;
			$template->send_shop($out_trade_no,$good_name,C('SHOP_ORDER_TYPE1'),$openid,$url);
		 */
			//模版消息参数
			
		//echo $prepay_id;
		$paysign = $weixin->paysign($prepay_id);
		$signPackage=$weixin->getSignPackage();
		$this->assign("signPackage",$signPackage);
		$this->assign("paysign",$paysign);
		
		$this->assign("daijin_info",$daijin_info);
		$this->display();
	}
	
	
	/* 用户账户余额充值 */
	function user_money(){
		$this->user_id = session('xigua_user_id');
		if(!$this->user_id){
			$redirect_uri='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			redirect('http://'.$_SERVER['HTTP_HOST'].U('/wxapi/oauth/index/')."?surl=".$redirect_uri);exit;
		}
		$this -> assign('app_info',F('config_info','',DATA_ROOT));
		$weixin = A("Wxapi/Weixin");
		$signPackage=$weixin->getSignPackage();
		$this->assign("signPackage",$signPackage);
		$users = D('Xigua/users');
		$this->assign('user_info',$users->get_user_info_by_user_id($this->user_id));
		$fenxiao_info = F('fenxiao_info','',DATA_ROOT);
		$this->assign('tixian_switch',$fenxiao_info['tixian_switch']);
		$this->assign('state',I('state'));
		$this->display();
	}
	
	function user_money_ajax(){
		$this->user_id = session('xigua_user_id');
		$broke_record = M('broke_record');
		$pagecount = 20;
		$count = $broke_record->where("user_id = '$this->user_id'") -> count();
		$Page = new \Think\Pageajax($count,$pagecount);
		$info = $broke_record->where("user_id = '$this->user_id'") ->limit($Page->firstRow.','.$Page->listRows)->order("time desc") -> select();
		foreach($info as $k=>$v){
			$info[$k]['time'] = date("Y-m-d H:i",$v['time']);
		}
		$show = $Page->show();
		$this->assign('page',$show);
		$this->assign('info',$info);
		$this->display();
	}
	/* 用户提现 */
	function user_tixian(){
		$this->user_id = session('xigua_user_id');
		if(!$this->user_id){$arr['success'] = 0;$arr['info']='网页会话已超时，请重新打开页面';echo json_encode($arr);exit;}
		$money = $_POST['money'];
		$fenxiao_info = F('fenxiao_info','',DATA_ROOT);
		if($money < $fenxiao_info['min_tixian']){$arr['success'] = 0;$arr['err_info'] = '单次提现金额至少为'.$fenxiao_info['min_tixian'];echo json_encode($arr);exit;}
		if($money > 200){$arr['success'] = 0;$arr['err_info'] = '单次提现金额不能超过200！';echo json_encode($arr);exit;}
		$record = M('broke_record') -> where("user_id = '$this->user_id' and  type = 3 and state = 0 ")->find();
		if($record != null){$arr['success'] = 0;$arr['err_info'] = '您有未完成的提现请求！请等待管理员通过后再次提现';echo json_encode($arr);exit;}
		$users = M('users');
		$user_info =$users-> field("shop_income,openid")->where("user_id='$this->user_id'")->find();
		if($money > $user_info['shop_income']){$arr['success'] = 0;$arr['err_info'] = '钱包余额低于提现金额！';echo json_encode($arr);exit;}
		$openid = $user_info['openid'];
		$Hongbao = A("Wxapi/Hongbao");
		
		$data = array(
			'user_id'=>$this->user_id,
			'desc'=>'钱包余额转出提现',
			'time'=>time(),
			'type'=>3,
			'fee'=>$money,
		);
		if($fenxiao_info['tixian_switch'] == 1){
			$data['state'] = 0;
			M('broke_record')->add($data);
			$arr['success'] = 2;$arr['err_info'] = '余额转出申请已提交！请等待管理员审核';
		}else{
			//红包接口
			$res = $Hongbao -> index($money,$openid,'钱包余额提现');
			$ress = $this->objectToArray($res);
			if($ress['result_code'] == 'SUCCESS'){
				$arr['success'] = 1;
				/* 记录提现操作，更改用户钱包余额 */
				$users -> where("user_id = '$this->user_id'")->setDec('shop_income',$money);
				$data['state'] = 1;
				M('broke_record')->add($data);
			}else{
				$arr['success'] = 0;$arr['err_info'] = $ress['return_msg'];
			}
		}
		
		echo json_encode($arr);
	}
	private function objectToArray($e){
		$e=(array)$e;
		foreach($e as $k=>$v){
			if( gettype($v)=='resource' ) return;
			if( gettype($v)=='object' || gettype($v)=='array' )
				$e[$k]=(array)$this->objectToArray($v);
		}
		return $e;
	}
	/* 充值订单 */
	function add_jinbi_order(){
		$this->user_id = session('xigua_user_id');
		$money = I('money');
		$arr = array();
		if(!$this->user_id){$arr['success'] = 0;$arr['info']='网页会话已超时，请重新打开页面';echo json_encode($arr);exit;}
		$fenxiao_info = F('fenxiao_info','',DATA_ROOT);
		if($fenxiao_info['chongzhi_exist'] == 1){
			$agent = M('users') -> getFieldByUser_id($this->user_id,'agent');
			if($agent == 0){$arr['success'] = 0;$arr['info']='普通会员暂时无法充值';echo json_encode($arr);exit;}
			
		}
		if($money<=0){$arr['success'] = 0;$arr['info']='至少充值1元';echo json_encode($arr);exit;}
		/***进行下单操作***/
		$total_fee = $money*100;
		$weixin = A("Wxapi/Weixin");
		$out_trade_no = "2016".$this->user_id.time();//订单号
		$notify_url = "http://".$_SERVER['SERVER_NAME'].U('/Pay/Notify/add_money');//交易成功后通知地址
		$openid = M('users') -> getFieldByUser_id($this->user_id,'openid');//openid信息
		$prepay_id = $weixin -> get_prepay_id($openid,$total_fee,$out_trade_no,$notify_url,$this->user_id,"会员充值");
		$paysign = $weixin->paysign($prepay_id);
		
		$arr = $paysign;
		$arr['timeStamp'] = (string)$arr['timeStamp'];
		$arr['success'] = 1;
		echo json_encode($arr);
	}
}