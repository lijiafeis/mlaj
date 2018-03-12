<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/21 0021
 * Time: 18:05
 */
namespace Home\Controller;
use Think\Controller;
use Think\Image;
use Think\Pageajax;
use Think\Upload;

class CenterController extends Controller{

    // /*用户来，判断session存不存在，*/
    function __construct(){
        parent::__construct();
        if(I('id')){$id=I('id');}else{$id=0;}
        if(!session('xigua_user_id')){
            /*去判断用户身份并缓存id*/
            $redirect_uri='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            redirect('http://'.$_SERVER['HTTP_HOST'].U('/wxapi/oauth/index/')."?surl=".$redirect_uri);exit;
        }else{
            $this -> u_id = session('xigua_user_id');
            $id = M('user') -> where(['u_id' => $this -> u_id]) -> find();
            if($id === null){
                echo '微信号未注册，请注册绑定';die();
            }
            $this -> user_id = $id['id'];
        }
    }
	
	public function index(){
        if(session('flag') == 1){
            $headImgUrl = M('users') -> getFieldByUserId($this->u_id,'headimgurl');
            $data = M('user') -> where(['id' => $this->user_id]) -> select();
            $data = $data[0];
			//$sy_yeji = M('user') -> getFieldById($this -> user_id,'jifen');
			//$sy_yeji = $this -> getDYYj1();
			$sy_yeji = $this -> getDYYj2();
            $this -> assign('data',$data);
            $this -> assign('jifen',$sy_yeji);
            $this -> assign('headimgurl',$headImgUrl);
            $this -> display();
        }else{
			$username = cookie('username');
			$password = cookie('password');
			// dump($username);
			// dump($password);exit;
			$this -> assign('username',$username);
			$this -> assign('password',$password);
            $this -> display('login');
        }
	}
	
	/**
		计算当月提货的业绩，总代的计算当月的和下级的，其它的计算自己的。
	*/
	private function getDYYj(){
		$type = M('user') -> getFieldById($this->user_id,'type');
		// if($type != 1){
			// return M('user') -> getFieldById($this->user_id,'dy_yeji');
		// }else{
			//计算自己的
			$yeji = M('user') -> getFieldById($this->user_id,'dy_yeji');
			//计算和自己有关的总代的业绩
			$dat = M('user')
				-> field('sum(dy_yeji) as yeji')
				-> where(" zt_userid = {$this->user_id} or yeji_userid = {$this->user_id} or sj_userid = {$this->user_id} ") 
				-> select();
			return $dat[0]['yeji'] + $yeji;
		// }
	}
	
	//总代计算自己的和自己有关的  其它的计算自己的和下级的
	private function getDYYj1(){
		$type = M('user') -> getFieldById($this->user_id,'type');
		if($type == 1){
			$yeji = M('user') -> getFieldById($this->user_id,'dy_yeji');
			//计算和自己有关的总代的业绩
			$data = M('user') -> where(" zt_userid = {$this->user_id} or yeji_userid = {$this->user_id} or sj_userid = {$this->user_id}") -> select();
			foreach($data as $k => $v){
				$yeji += M('user') -> getFieldById($v['id'],'dy_yeji');
				$yeji += $this -> getyeji1($v['id']);
			}
			
			// $dat = M('user')
				// -> field('sum(dy_yeji) as yeji')
				// -> where(" zt_userid = {$this->user_id} or yeji_userid = {$this->user_id} or sj_userid = {$this->user_id} ") 
				// -> select();
		}else{
			$yeji = M('user') -> getFieldById($this->user_id,'dy_yeji');
			$data = M('user') -> where(" sj_userid = {$this->user_id}") -> select();
			foreach($data as $k => $v){
				$yeji += M('user') -> getFieldById($v['id'],'dy_yeji');
				$yeji += $this -> getyeji1($v['id']);
			}
			// $dat = M('user')
				// -> field('sum(dy_yeji) as yeji')
				// -> where(" sj_userid = {$this->user_id} ") 
				// -> select();
		}
			
			
			return $dat[0]['yeji'] + $yeji;
		// }
	}
	
	//总代计算自己的和自己有关的  其它的计算自己的和下级的
	private function getDYYj2(){
		$money = 0;
		$type = M('user') -> getFieldById($this->user_id,'type');
		if($type == 1){
			$money = M('user') -> getFieldById($this->user_id,'dy_yeji');
			//计算和自己有关的总代的业绩
			$data = M('user') -> where(" zt_userid = {$this->user_id} or yeji_userid = {$this->user_id} or sj_userid = {$this->user_id}") -> select();
			foreach($data as $k => $v){
				 if($v['type'] == 1){
					$money += M('user') -> getFieldById($v['id'],'dy_yeji');
					$money += $this -> getyeji1($v['id']);
				}
				
			}
			
			//$dat = M('user')
				// -> field('sum(dy_yeji) as yeji')
				// -> where(" zt_userid = {$this->user_id} or yeji_userid = {$this->user_id} or sj_userid = {$this->user_id} ") 
				// -> select();
		}else{
			$money = M('user') -> getFieldById($this -> user_id,'dy_yeji');
		}
			
			
			return $money;
		// }
	}

	function tuichu(){
//	    file_put_contents('sess.txt',session('flag'));
	    if(session('flag') == 1){
            session('flag',null);
            $this -> redirect('login');
        }

    }

    public function login(){
        $this -> display();
    }

    //充值信息
    public function chongzhi(){
        $data = M('chongzhi') -> where(['user_id' => $this -> user_id,'state' => 1]) -> select();
        $this -> assign('data',$data);
        $this -> display();
    }

    //充值积分
    public function czJf(){
        $this -> display();
    }

    public function abc(){
        file_put_contents('234.txt',$_POST['res']);
    }

    //用户充值积分
    public function chongzhijifen1(){
        $fee = $_POST['money'];
        $data['user_id'] = $this->user_id;
        $data['money'] = $fee;
        //记录购买信息
        $return = M('chongzhi')->add($data);
        $total_fee = $fee*100;
        $weixin = A("Wxapi/Weixin");
        $out_trade_no = "2017".$this->user_id.time();//订单号
        $notify_url = "http://".$_SERVER['SERVER_NAME'].U('/Home/Notice/buy_yz');//交易成功后通知地址
        $openid = M('users') -> getFieldByUser_id($this->u_id,'openid');//openid信息
        file_put_contents('45.txt',$openid);
        $prepay_id = $weixin -> get_prepay_id($openid,$total_fee,$out_trade_no,$notify_url,$return,"充值");
        $paysign = $weixin->paysign($prepay_id);
        $paysign['timeStamp'] = (string)$paysign['timeStamp'];
        $paysign['success'] = 1;
        echo json_encode($paysign);
    }

    //积分列表
    public function jifenList(){
        $jifen = M('user') -> getFieldById($this -> user_id,'jifen');
        $this -> assign('jifen',$jifen);
        $this -> display();
    }

    public function jifenbb(){
        $page = $_GET['p'];
        $start = ($page -1) * 20;
        $data = M('jifen')
            -> where(['user_id' => $this -> user_id])
            -> limit($start,20)
			-> order('time desc')
            -> select();
        $jifen = M('user') -> getFieldById($this -> user_id,'jifen');
//        $this -> assign('data',$data);
//        $this -> assign('jifen',$jifen);
        foreach ($data as $k => $v){
            $data[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
        }
        $this -> ajaxReturn($data);
    }

    //佣金记录
    public function yongjinList(){
        $userList = M('user') 
			-> alias('a')
			-> field('a.*,b.headimgurl')
			-> join("left join __USERS__ as b on a.u_id = b.user_id")
			-> where(" a.zt_userid = {$this->user_id} or yeji_userid = {$this->user_id} and a.type = 1")
			//-> where('a.type = 1')
			-> order('a.sy_yeji desc')
			->select();
        $sy_money = M('user') -> getFieldById($this->user_id,'sy_money');
		$data = M('fanyong') -> where(['user_id' => $this -> user_id]) -> select();
		$year = date('Y',time());
		$month = date('m',time());
		$y = $year;
		if($month == 1){
			$m = 12;
			$y = $y - 1;
		}else{
			$m = $month - 1;
		}
		$jiangjin = 0;
		$yeji = 0;
		foreach($data as $k => $v){
			$year = date('Y',$v['time']);
			$month = date('m',$v['time']);
		
			if($year != $y){
				unset($data[$k]);
				continue;
			}
			if($m != $month){
				unset($data[$k]);
				continue;
			}
			if($v['type'] == 1){
				$jiangjin += $v['money'];
			}
			//$money += $v['money'];
		}
		//计算团队总业绩
		//$zongyeji = M('user') -> where(['sj_userid' => $this->user_id]) -> sum('sy_yeji');
		// $zongyeji = M('user') 
			// -> alias('a')
			// -> field('sum(sy_yeji) as yeji')
			// -> where("id = {$this->user_id} or a.zt_userid = {$this->user_id} or yeji_userid = {$this->user_id} and a.type = 1")
			// //-> where('a.type = 1')
			// ->select();
		$zongyeji = M('user') -> getFieldById($this->user_id,'sy_yeji');
		//$zongyeji1 = M('user') -> getFieldById($this->user_id,'sy_yeji');
		//$zongyeji += (float)$zongyeji1;
		$money = $sy_money + $jiangjin;
        $this -> assign('data',$userList);
        $this -> assign('money',$money);
        $this -> assign('sy_money',$sy_money);
        $this -> assign('zongyeji',$zongyeji);
        $this -> assign('sy_tuijian',$jiangjin);
       
        $this -> display();
    }

    //佣金提现
    public function tixian(){
        $head_img = M('users') -> getFieldByUserId($this->u_id,'headimgurl');
        $info = M('user') -> where(['id' => $this -> user_id]) -> select();
//        dump($info);
        $data = M('user_bank') -> where(['user_id' => $this -> user_id]) -> select();
        $data = $data[0];
        $this -> assign('head_img',$head_img);
        $this -> assign('bank_info',$data);
        $this -> assign('info',$info[0]);
        $this -> display();
    }
	
	//金额转换成积分
	public function moneyToJifen(){
		$money = $_POST['money'];
		$money = intval($money);
		M('user') -> where(['id' => $this->user_id]) -> setDec('money',$money);
		$res = M('user') -> where(['id' => $this->user_id]) -> setInc('jifen',$money);
		if($res){
			//话费积分，记录，得到佣金，记录
			$data['user_id'] = $this->user_id;
			$data['number'] = $money;
			$data['time'] = time();
			$data['type'] = 2;
			M('jifen') -> add($data);
			echo 0;
		}else{
			echo -1;
		}
	}

    /* 用户提现 */
    function user_tixian(){
//        $this->user_id = session('xigua_user_id');
        if(!$this->user_id){
            $arr['success'] = 0;
            $arr['info']='网页会话已超时，请重新打开页面';
            echo json_encode($arr);exit;}
			
		//判断是否有未操作的提现申请
		$c = M('tixian') -> where(" user_id = {$this->user_id} and type = 0") -> select();
		if($c){
			echo -4;
			exit;
		}
			
		
        /******写入用户的提现银行信息*******/
        $data = array();
        $data['user_name'] = I('user_name');
        $data['tel'] = I('tel');
        if(I('pay_type') == 2){
            $data['alipay_number'] = I('alipay_number');
            $data['type'] = 0;
        }elseif(I('pay_type') == 1){
            $data['bank_name'] = I('bank_name');
            $data['bank_number'] = I('bank_number');
            $data['alipay_number'] = I('alipay_number');
            $data['type'] = 1;
        }
        $data['user_id'] = $this->user_id;
        $bank_res = M('user_bank') -> where("user_id = '$this->user_id' ")->find();
        if($bank_res == null){
            $res = M('user_bank') -> add($data);
        }else{
            $res = M('user_bank') ->where("bank_id = '$bank_res[bank_id]' ") -> save($data);
        }

        $money = $_POST['money'];
        $fee = M('user') -> getFieldById($this->user_id,'money');
        if($money > $fee){
            echo -1;
            exit;
        }
        $data1 = array(
            'user_id'=>$this->user_id,
            'time'=>time(),
            'money'=>$money,
			'state' => $data['type']
        );
        $res = M('tixian') -> add($data1);
        if($res){
            M('user') -> where(['id' => $this->user_id]) -> setDec('money',$money);
            echo 0;
        }else{
            echo -2;
        }
    }

    //提现记录
    public function tixianShow(){
        $this -> display();
    }

    public function tixianshowbb(){
        $page = $_GET['p'];
        $start = ($page -1) * 20;
        $data = M('tixian')
            -> alias('a')
            -> field('a.*,b.name')
            -> join("left join __USER__ as b on a.user_id = b.id")
            -> where(['a.user_id' => $this->user_id])
            -> limit($start,20)
            -> order('a.time desc')
            -> select();
        foreach ($data as $k => $v){
            $data[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
        }
        $this -> ajaxReturn($data);
    }

    //商品商城
    public function shop(){
        $data = M('shop') 
		//-> where(['type' => ['neq',2]])
		-> order('id asc')
		-> select();
        $type = M('user') -> getFieldById($this -> user_id,'type');
        $arr = array();
        $sj_userid = M('user') -> getFieldById($this->user_id,'sj_userid');
        if($sj_userid == 0){
            foreach ($data as $k => $v){
				
                $data[$k]['kucun'] = '10000';
				if($v['fee1'] == 0 && $v['fee2'] == 0 && $v['fee3'] == 0 && $v['fee4'] == 0){
					unset($data[$k]);
					continue;
				}
				if($v['type'] == 4 && $type != 1){
					unset($data[$k]);
				}
            }
        }else{
            $res = M('dl_kucun') -> where(['user_id' => $sj_userid]) -> select();
            foreach ($data as $k => $v){
				
				
				
				if($v['fee1'] == 0 && $v['fee2'] == 0 && $v['fee3'] == 0 && $v['fee4'] == 0){
					unset($data[$k]);
					continue;
				}
                //得到库存数量
                foreach ($res as $key => $val){
                    if($val['shop_id'] == $v['id']){
                        $data[$k]['kucun'] = $val['kucun'];
                    }
                }
				if($v['type'] == 4 && $type != 1){
					unset($data[$k]);
				}
            }
        }


        $this -> assign('data',$data);
        $this -> assign('type',$type);
        $this -> assign('isLogin',1);
        $this -> display();
    }

    //用户下单
    public function setOrder(){
        $data = $_GET['data'];
//        dump($data);
//        exit;
        //使用正则匹配
        $a = array();
        $pattern = '/\d+,[\x80-\xff]+,[a-zA-Z]+,\d+,/';
        $result = $res = preg_match_all($pattern,$data,$a);
        $a = $a[0];
//        dump($a);
//        exit;
        $str = "";
        //去掉没有选择的
        foreach ($a as $k => $v){
            if(strstr($v,'不')){

                unset($a[$k]);
            }
            if(strstr($v,'abc')){
                unset($a[$k]);
            }
            if($a[$k]){
                $str = $str . $v;
            }

        }
//        dump($str);
//        exit;
        $data = explode(',',$str);
        foreach ($data as $k => $v){
            if($v === ''){
                unset($data[$k]);
            }
        }
//        dump($data);
//        exit;
        $arr = array();
        $i = -1;
        $kucunnumber = 0;
        foreach ($data as $k => $v){
            if($k % 4 == 0){
                $i = $i + 1;
                $arr[$i]['shop_id'] = $v;
            }else if($k % 4 == 1){
                $arr[$i]['danwei'] = $v;
            }else if($k % 4 == 2){
                $arr[$i]['size'] = $v;
            }else if($k % 4 == 3){
                $arr[$i]['number'] = $v;
                $kucunnumber += $v;
            }
        }
        foreach ($arr as $k => $v){
            if($v['number'] == 0){
                unset($arr[$k]);
            }
        }

//        dump($arr);
//        exit;
        if(!$arr){
            $this -> error('请选择商品');
            exit;
        }

//        dump($arr);
//        exit;
        //判断是否有货
        $flag = false;
        $sj_userid = M('user') ->getFieldById($this->user_id,'sj_userid');
        if($sj_userid != 0){
            foreach ($arr as $k => $v){
                $a = M('dl_kucun') -> where(['user_id' => $sj_userid,'shop_id' => $v['shop_id']]) -> select();
                $a = $a[0]['kucun'];
                if($a < $v['number']){
                    $this -> sendTemplateShop($sj_userid,$v['shop_id']);
                    $flag = true;
                }
            }
            if($flag){
				//发送模板消息告诉上级没有货
                $this-> error('你购买的商品没有货，请通知上级发货','shop');
				//
                exit;
            }
        }
        $money = 0;
        $type = M('user') -> getFieldById($this->user_id,'type');
	
        
        $ress = array();
		//保存到订单表中
        $re = M('address') -> where(['user_id' => $this->user_id]) -> select();
		$data1['user_id'] = $this -> user_id;
		$data1['time'] = time();
		$data1['type'] = 0;
		$data1['address_id'] = $re[0]['id'];
		$order_id = M('order') -> add($data1);
		$lirun = 0;
        foreach ($arr as $k => $v){
            if($v['shop_id']!=null && $v['danwei'] != null && $v['size'] != null && $v['number'] !=null){
                $info = M('shop') -> find($v['shop_id']);
                $info['danwei'] = $v['danwei'];
                $info['size_name'] = $v['size'];
                $arr[$k]['shop_name'] = $info['title'];
                $ress[] = $info;
				//dump($info);
				if($type == 1){
					$money += $this -> setMoney($info['fee1'],$v['number']);
				}elseif($type == 2){
					$money += $this -> setMoney($info['fee2'],$v['number']);
				}elseif($type == 3){
					$money += $this -> setMoney($info['fee3'],$v['number']);
				}elseif($type == 4){
					$money += $this -> setMoney($info['fee4'],$v['number']);
				}
				
				//保存到表中
				$data2['order_id'] = $order_id;
				$data2['shop_name'] = $info['title'];
				if($type == 1){
					$data2['money']= $this -> setMoney($info['fee1'],$v['number']);
				}elseif($type == 2){
					$data2['money']= $this -> setMoney($info['fee2'],$v['number']);
				}elseif($type == 3){
					$data2['money']= $this -> setMoney($info['fee3'],$v['number']);
				}elseif($type == 4){
					$data2['money']= $this -> setMoney($info['fee4'],$v['number']);
				}
				$data2['size'] = $v['size'];
				$data2['danwei'] = $v['danwei'];
				$data2['gmnumber'] = $v['number'];
				$data2['pic_url'] = $info['img_url'];
				$data2['shop_id'] = $v['shop_id'];
				M('shopping') -> add($data2);
				unset($data2);
				//减少库存
//                dump($v['number']);
//                exit;
               // M('dl_kucun') -> where(['user_id' => $sj_userid,'shop_id' => $v['shop_id']]) -> setDec('kucun',$v['number']);
				//保存到转移表中
				//$c['user_id'] = $this -> user_id;
				//$c['sj_userid'] = $sj_userid;
				//$c['shop_id'] = $v['shop_id'];
				//$c['time'] = time();
				//$c['number'] = $v['number'];
				//M('zhuanyi') -> add($c);
				
			}
        }
		//保存到代理下单表中
		$d['user_id'] = $sj_userid;
		$d['order_id'] = $order_id;
		M('order_info') -> add($d);
        M('order') -> where(['id' => $order_id]) -> setField('shopmoney',$money);
        $this -> assign('addList',$re);
		$this -> assign('order_id',$order_id);
        $this -> assign('money',$money);
        $this -> assign('data',$arr);
        $this -> display();
    }

    //判断当前用户时候有收获地址
    public function isAddress(){
        $res = M('address') -> where(['user_id' => $this -> user_id]) -> select();
        if($res){
            echo 0;
        }else{
            echo -1;
        }
    }

    //额外的设置第一个收获地址
    public function setOneAddress(){
        if(IS_GET){
            $data1 = $_GET['data'];
            $this -> assign('data',$data1);
            $this -> display();
        }else{
            $ress = array();
            $data['name'] = $_POST['name'];
            $data['tel'] = $_POST['tel'];
            $data['address'] = $_POST['address'];
            $data1 = $_POST['data'];
            $data['user_id'] = $this -> user_id;
            $a = M('address') -> where($data) -> select();
            if($a){
                $ress['flag'] = -1;
            }else{
                $res = M('address') -> add($data);
                $ress['data'] = $data1;
                if($res){
                    $ress['flag'] = 0;
                }else{
                    $ress['flag'] = 1;
                }
            }
            $this -> ajaxReturn($ress);
        }
    }

    public function setMoney($money,$number){

        return $money * $number;
    }
	
	//付款
	public function fukuan(){
		$order_id = $_POST['order_id'];
		$address_id = $_POST['address_id'];
		$money = $_POST['money']*1;
		$content = $_POST['content'];
		
		$jifen = M('user') -> getFieldById($this->user_id,'jifen');
		$ae = M('order') -> where(['id' => $order_id]) -> select();
		if(!$ae){echo -2;exit;}
		if($money != $ae[0]['shopmoney']){echo -2;exit;}
		if($jifen < $money){
			echo -1;
			exit;
		}else{
			//先判断上级是否有余额
			$arr = M('shopping') -> where(['order_id' => $order_id]) -> select();
			if(!$arr){echo -2;exit;}
			$sj_userid = M('user') -> getFieldById($this->user_id,'sj_userid');
			$zt_userid = M('user') -> getFieldById($this->user_id,'zt_userid');
			$lirun = 0;
			$is_true = 0;
			if($sj_userid != 0 && $zt_userid != 0){
				$m = M('user') -> getFieldById($sj_userid,'money');
				
				foreach($arr as $k => $v){
					//得到商品的类型。根据类型返还佣金
					$ty = M('shop') -> getFieldById($v['shop_id'],'type');
					//只有0和1有其它的没有
					$is_flag = 0;
					if($ty == 1 || $ty == 0 || $ty == 5 || $ty == 6){
						$is_flag = 1;
						switch($ty){
							case 1:
								$lirun = $lirun + $v['gmnumber'] * 20;
								break;
							case 0:
								$lirun = $lirun + $v['gmnumber'] * 20;
								break;
							case 5:
								$lirun = $lirun + $v['gmnumber'] * 5;
								break;
							case 6:
								$lirun = $lirun + $v['gmnumber'] * 2.5;
								break;
						}
					}
				}
				
				if($lirun > $m){
				    
					//判断上级的货款是否够
					$n = M('user') -> getFieldById($sj_userid,'jifen');
					$is_true = 1;
					if($lirun > $n){
						$this -> sendTemplateMoney($sj_userid);
						echo -3;
						exit;
					}
					
				}
			}
			//file_put_contents('abc.txt',$lirun);
			foreach($arr as $k => $v){
				$res = M('dl_kucun') -> where(['user_id' => $this -> user_id,'shop_id' => $v['shop_id']]) -> select();
                if($res){
                    $b = M('dl_kucun') -> where(['user_id' => $this->user_id,'shop_id' => $v['shop_id']]) -> setInc('kucun',$v['gmnumber']);
                }else{
                    $da['user_id'] = $this->user_id;
                    $da['kucun'] = $v['gmnumber'];
                    $da['shop_id'] = $v['shop_id'];
                    $b = M('dl_kucun') -> add($da);
                }
				//转移上级库存
				//减少库存
//                dump($v['number']);
//                exit;
                M('dl_kucun') -> where(['user_id' => $sj_userid,'shop_id' => $v['shop_id']]) -> setDec('kucun',$v['gmnumber']);
				//保存到转移表中
				$c['user_id'] = $this -> user_id;
				$c['sj_userid'] = $sj_userid;
				$c['shop_id'] = $v['shop_id'];
				$c['time'] = time();
				$c['number'] = $v['gmnumber'];
				M('zhuanyi') -> add($c);
			}
			
            M('order') -> where(['id' => $order_id]) -> setField('address_id',$address_id);
            M('order') -> where(['id' => $order_id]) -> setField('state',1);
            M('order') -> where(['id' => $order_id]) -> setField('content',$content);
			M('user') -> where(" id = {$this->user_id}") -> setInc('dy_yeji',$money);
			$jifen1 = $jifen - $money;
			$res = M('user') -> where(['id' => $this->user_id]) -> setField('jifen',$jifen1);
            $sj_userid = M('user') -> getFieldById($this->user_id,'sj_userid');
			if($res){
				//查询所有的没有付款的订单,没用 删除掉 订单的商品信息表 和 代理下单表
				$data = M('order') -> where(['state' => 0]) -> select();
				M('order') -> where(['state' => 0]) -> delete();
				foreach($data as $k => $v){
					M('shopping') -> where(['order_id' => $v['id']]) -> delete();
					M('order_info') -> where(['order_id' => $v['id']]) -> delete();
				}
				//付款成功，转移上级库存
				if($sj_userid != 0 && $zt_userid != 0){
					//如果有直推的人，给直推的人推荐奖
					$zt_userid = M('user') -> getFieldById($this->user_id,'zt_userid');
					if($zt_userid != 0){
						if($is_flag){
							
						
							if($is_true == 0){
								M('user') -> where(['id' => $sj_userid]) -> setDec('money',$lirun);
							}else{
								M('user') -> where(['id' => $sj_userid]) -> setDec('jifen',$lirun);
							}
						
						//给上级发送模板信息
						$this -> sendSjKouFei($sj_userid,$lirun);
						M('user') -> where(['id' => $zt_userid]) -> setInc('money',$lirun);
						$name = M('user') -> getFieldById($this->user_id,'name');
						$this -> sendTemplateZtMoney($lirun,$name,$zt_userid);
						}
					}
					//记录到返佣表中
					if($is_flag){
						$c['user_id'] = $zt_userid;
						$c['money'] = $lirun;
						$c['time'] = time();
						$c['type'] = 1;
						M('fanyong') -> add($c);
						//记录到返佣表中
						
						if($is_true == 0){
							$c1['user_id'] = $sj_userid;
							$c1['money'] = $lirun;
							$c1['time'] = time();
							$c1['type'] = 3;
							M('fanyong') -> add($c1);
						}else{
							$c1['user_id'] = $sj_userid;
							$c1['number'] = $lirun;
							$c1['time'] = time();
							$c1['type'] = 3;
							M('jifen') -> add($c1);
						}
					}
					
				}
				//花费积分，保存到积分表中
				$data3['user_id'] = $this -> user_id;
				$data3['number'] = $money;
				$data3['time'] = time();
				M('jifen') -> add($data3);

                //发送模板消息
                //得到上级id
//                M('user') -> getFieldById($this->user_id,'sj_userid');
//                if($sj_userid == 0){
//
//                }else{
//                    //发送模板消息
//                    $u_id = M('user') -> getFieldById($sj_userid,'u_id');
//                    $openid = M('users') -> getFieldByUserId($u_id,'openid');
//                    $this -> sendTemplate1($openid,$order_id);
//                }
				echo 0;
			}else{
				echo -2;
			}
		}
		
	}

    //收货地址列表
    public function addressList(){
		$type = isset($_GET['type']) ? $_GET['type'] : 0;
		
		$order_id = $_GET['order_id'];
        $data = M('address') -> where(['user_id' => $this->user_id]) -> select();
        $this -> assign('data',$data);
        $this -> assign('type',$type);
        $this -> assign('order_id',$order_id);
        $this -> display();
    }

    //删除地址
    public function deleteAdd(){
        $res = M('address') -> delete($_POST['id']);
        if($res){
            echo 0;
        }else{
            echo -1;
        }
    }

    //添加新地址
    public function addNewAddress(){
        if(IS_GET){
            $this -> display();
        }else{
            $data = $_POST;
            if(!$data['name'] || !$data['tel'] || !$data['address']){
                echo -2;
                exit;
            }
            $data['user_id'] = $this -> user_id;
            $res = M('address') -> add($data);
            if($res){
                echo 0;
            }else{
                echo -1;
            }
        }
    }

    //通过订单号得到物流信息
    public function getWuliu(){
        $order_sn = $_GET['order_sn'];
        $kd1 = $_GET['kd'];
		//dump($kd);
        $kd = A("Wxapi/Kuaidi");
		/*if($kd1 == 'HHTT'){
				$arr = $kd -> getData($kd1,$order_sn);
				$arr['kd'] = '天天快递';
			}else{*/
				$data = $kd -> getMessage($kd1,$order_sn);
				$data = json_decode($data);
				$arr = $this -> infoToArray($data);
			//}
        
		$this -> assign('data',$arr[0]);
        $this -> assign('info',$arr[1]);
        $this -> display();

    }

    public function infoToArray($data){
        $arr = array();
        $arr['ddh'] = $data -> LogisticCode;
        $ress = array();
        switch ($data -> ShipperCode){
            case 'SF':
                $arr['kd'] = '顺丰快递';
                break;
            case 'STO':
                $arr['kd'] = '申通快递';
                break;
            case 'YD':
                $arr['kd'] = '韵达快递';
                break;
            case 'YTO':
                $arr['kd'] = '圆通速递';
                break;
            case 'ZJS':
                $arr['kd'] = '宅急送';
                break;
            case 'ZTO':
                $arr['kd'] = '中通速递';
                break;
            case 'AMAZON':
                $arr['kd'] = '亚马逊物流';
                break;
        }
        foreach ($data -> Traces as $k => $v){
            $info['time'] = $v -> AcceptTime;
            $info['info'] = $v -> AcceptStation;
            $ress[$k] = $info;
        }
        $re[0] = $arr;
        $re[1] = $ress;
        return $re;
    }

    //订单列表
    public function order1(){
        //显示待下单
        $data = M('order')
            -> where(['user_id' => $this -> user_id,'state' => 1])
            -> order('time desc')
            -> select();
		foreach($data as $k => $v){
			$res = M('shopping') -> where(['order_id' => $v['id']]) -> select();
			$data[$k]['count'] = count($res);
			unset($res);
		}
        $this -> assign('data',$data);
        $this -> display();
    }
	
	//订单列表
    public function order(){
         //显示已下单
        $data = M('order')
            -> where(['user_id' => $this -> user_id,'state' => 1,'type' => 0])
            -> order('time desc')
            -> select();
			
        foreach($data as $k => $v){
            $res = M('shopping') -> where(['order_id' => $v['id']]) -> select();
			//dump($res);
			$data[$k]['pic_url'] = $res[0]['pic_url'];
			$data[$k]['shop_name'] = $res[0]['shop_name'];
            $data[$k]['count'] = count($res);
            unset($res);
        }
        //dump($data);
        $this -> assign('data',$data);
        $this -> display();
    }

	//点击立即下单
	public function ljXiadan(){
		$order_id = $_GET['order_id'];
		$res = M('shopping') -> where(['order_id' => $order_id]) -> select();
		$money = M('order') -> getFieldById($order_id,'shopmoney');
		 $re = M('address') -> where(['user_id' => $this->user_id]) -> select();
		// dump($moeny);
		 //exit;
        $this -> assign('addList',$re);
		$this -> assign('order_id',$order_id);
		$this -> assign('money',$money);
		$this -> assign('data',$res);
		$this -> display();
		
	}

    //已付款并且已经提货的列表
    public function yifahuo(){
        //显示已下单
        $data = M('order')
            -> where(['user_id' => $this -> user_id,'state' => 1,'type' => 1])
            -> order('time desc')
            -> select();
        foreach($data as $k => $v){
            $res = M('shopping') -> where(['order_id' => $v['id']]) -> select();
            $data[$k]['count'] = count($res);
            unset($res);
        }

        $this -> assign('data',$data);
        $this -> display();
    }
	
	 //已付款并且已经提货的列表
    public function yifahuo1(){
        //显示已下单
        $data = M('order')
//            -> where(['user_id' => $this -> user_id,'state' => 1,'type' => ['in',[1,2]]])
            -> where("user_id = {$this -> user_id} and state = 1 and type != 0")
            -> order('time desc')
            -> select();
        foreach($data as $k => $v){
            $res = M('shopping') -> where(['order_id' => $v['id']]) -> select();
            $data[$k]['count'] = count($res);
			$u_id = M('user') -> getFieldById($v['user_id'],'u_id');
			$data[$k]['headimgurl'] = M('users') -> getFieldByUserId($u_id,'headimgurl');
			$data[$k]['shop_name'] = $res[0]['shop_name'];
            unset($res);
        }
        $this -> assign('data',$data);
        $this -> display();
    }

    public function ketihuo(){
        //显示已下单
        $data = M('order')
            -> where(['user_id' => $this -> user_id,'state' => 1,'type' => 0])
            -> order('time desc')
            -> select();
			
        foreach($data as $k => $v){
            $res = M('shopping') -> where(['order_id' => $v['id']]) -> select();
            $data[$k]['count'] = count($res);
            unset($res);
        }
//        dump($data);
        $this -> assign('data',$data);
        $this -> display();
    }

    //详情页
    public function xiangqing(){
        $order_id = $_GET['order_id'];
        $res = M('shopping') -> where(['order_id' => $order_id]) -> select();
        $info = M('order') -> find($order_id);
        $address = M('address') -> find($info['address_id']);
		if($_GET['address_id']){
			$address = M('address') -> find($_GET['address_id']);
		}
		//dump($address);
        $type = M('tihuo') -> getFieldByOrderId($order_id,'type');
		$addList = M('address') -> where(['user_id' => $this -> user_id]) -> select();
        $this -> assign('order_id',$order_id);
        $this -> assign('type',$type);
        $this -> assign('address',$address);
        $this -> assign('addList',$addList[0]);
        $this -> assign('info',$info);
        $this -> assign('money',$info['shopmoney']);
        $this -> assign('data',$res);
        $this -> display();
    }

    //进行提货
    public function tihuo(){
        $order_id = $_POST['order_id'];
        $address_id = $_POST['address_id'];
        $content = $_POST['content'];
		if($address_id != 0){
			M('order') -> where(['id' => $order_id]) -> setField('address_id',$address_id);
			M('order') -> where(['id' => $order_id]) -> setField('content',$content);
		}else{
			echo -3;
		}
        //判断当前的提货表中是否有这个数据
        $res = M('tihuo') -> where(['order_id' => $order_id,'user_id' => $this->user_id]) -> select();
        if($res){
            echo -2;
            exit;
        }
        $data['user_id'] = $this->user_id;
        $data['order_id'] = $order_id;
        $data['time'] = time();
        $res = M('tihuo') -> add($data);
        if($res){
            echo 0;
        }else{
            echo -1;
        }
    }

    //下级会员
    public function xiaji(){
		$res = M('user') -> where(" sj_userid= {$this->user_id} or yeji_userid = {$this->user_id} or zt_userid = {$this->user_id} ") -> sum('zongjifen');
		// $res = M('user') -> where(['sj_userid' => $this -> user_id]) -> sum('zongjifen');
		$this -> assign('zongyeji',$res);
        $this -> display();
    }
	
	 public function xiajibb(){
        $page = $_GET['p'];
        $start = ($page -1) * 8;
		$type1 = M('user') -> getFieldById($this->user_id,'type');
		if($type1 == 1){
			$data = M('user')
			-> alias('a')
			-> field('a.*,b.headimgurl')
			-> join("left join __USERS__ as b on a.u_id = b.user_id")
            -> where(" sj_userid= {$this->user_id} or yeji_userid = {$this->user_id} or zt_userid = {$this->user_id} or id = {$this->user_id} ")
			-> order('a.dy_yeji desc')
			-> limit($start,8)
            -> select();
		
			foreach($data as $k => $v){
				switch($v['type']){
					case 1:
					 $type = 'pic1.png';
					 break;
					case 2:
					 $type = 'pic2.png';
					 break;
					 case 3:
					 $type = 'pic3.png';
					 break;
					 case 4:
					 $type = 'pic4.png';
					 break;
				}
				$data[$k]['type'] = $type;
				//得到提货的订单的金额就是业绩
				//$data[$k]['yeji'] = $this -> getMoney($v['id']);
				//总代的显示全部的业绩  其它的显示下级的。
				if($v['id'] != $this -> user_id){
					$data[$k]['dy_yeji'] += $this -> getyeji1($v['id']);
				}
				
			}
		}else{
			$data = M('user')
			-> alias('a')
			-> field('a.*,b.headimgurl')
			-> join("left join __USERS__ as b on a.u_id = b.user_id")
            -> where(" sj_userid= {$this->user_id}")
			-> limit($start,8)
            -> select();
		
			foreach($data as $k => $v){
				switch($v['type']){
					case 1:
					 $type = 'pic1.png';
					 break;
					case 2:
					 $type = 'pic2.png';
					 break;
					 case 3:
					 $type = 'pic3.png';
					 break;
					 case 4:
					 $type = 'pic4.png';
					 break;
				}
				$data[$k]['type'] = $type;
				//得到提货的订单的金额就是业绩
				//$data[$k]['yeji'] = $this -> getMoney($v['id']);
				//总代的显示全部的业绩  其它的显示下级的。
				$data[$k]['dy_yeji'] += $this -> getyeji1($v['id']);
			}
		}
        $this -> ajaxReturn($data);
    }
	
	public function getyeji1($user_id){
		$type = M('user') -> getFieldById($user_id,'type');
		$data = M('user') -> where(" zt_userid = {$user_id} or yeji_userid = {$user_id} or sj_userid = {$user_id}") -> select();
		if(!$data){
			return 0;
		}
		$money = 0;
		if($type == 1){
			$dat = M('user')
				-> field('sum(dy_yeji) as yeji')
				-> where(" zt_userid = {$user_id} or yeji_userid = {$user_id} or sj_userid = {$user_id} and type = 1")
				-> select();
			$data = M('user') -> where(" zt_userid = {$user_id} or yeji_userid = {$user_id} or sj_userid = {$user_id} and type = 1") -> select();
		}else{
			$dat = M('user')
				-> field('sum(dy_yeji) as yeji')
				-> where(" sj_userid = {$user_id} ") 
				-> select();
			$data = M('user') -> where(" sj_userid = {$user_id}") -> select();
		}
		$money += $dat[0]['yeji'];
		foreach($data as $k => $v){
			$money += $this -> getyeji1($v['id']);
		}
		
		return $money;
	}
	
	//计算每个人的当月的提货订单金额数
	private function getMoney($user_id){
		$model = M('order');
		$a = $model -> where(" state = 1 and type = 1 and user_id = {$user_id} and year(from_unixtime(time,'%Y-%m-%d')) = year(current_date) and month(from_unixtime(time,'%Y-%m-%d')) = month(current_date)") -> sum('shopmoney');
		//file_put_contents('45.txt',$a);
		$a = ($a == null) ? 0 : $a;
		return $a;
	}

    //下级业绩
    public function xiajiyeji(){
        $user_id = $_GET['user_id'];
        $yeji = M('user') -> getFieldById($user_id,'yeji');

        $this -> assign('user_id',$user_id);
        $this -> assign('yeji',$yeji);
        $this -> display();
    }

    public function czjfList(){
        $this -> display();
    }

    public function czjfListbb(){
        $page = $_GET['p'];
        $start = ($page -1) * 20;
        $data = M('dl_jifen')
            -> alias('a')
            -> field('a.*,b.name')
            -> join("left join __USER__ as b on a.xj_id = b.id")
            -> where(['a.user_id' => $this->user_id])
            -> limit($start,20)
            -> order('a.time desc')
            -> select();
        foreach ($data as $k => $v){
            $data[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
        }
        $this -> ajaxReturn($data);
    }

    public function xiajiyejibb(){
        $page = $_GET['p'];
        $user_id = $_POST['user_id'];
        $start = ($page -1) * 20;
        $data = M('yeji')
            -> alias('a')
            -> field('a.time as yjtime,a.number as yjnumber,b.name')
            -> join("left join __USER__ as b on a.user_id = b.id")
            -> where(['a.user_id' => $user_id])
            -> limit($start,20)
            -> order('a.time desc')
            -> select();
        foreach ($data as $k => $v){
            $data[$k]['yjtime'] = date('Y-m-d H:i:s',$v['yjtime']);
        }
        $this -> ajaxReturn($data);
    }

    //给下级充值积分
    public function chongzhijifen(){
        $user_id = $_POST['user_id'];
        $jifen = $_POST['jifen'];

        $res = M('user') -> where(['id' => $user_id]) -> setInc('jifen',$jifen);
        M('user') -> where(['id' => $user_id]) -> setInc('zongjifen',$jifen);
        if($res){
            $data['user_id'] = $user_id;
            $data['number'] = $jifen;
            $data['time'] = time();
            $data['type'] = 1;
            M('jifen') -> add($data);

            //记录到代理充值积分中
            //记录到代理充值积分中
            $a['user_id'] = $this->user_id;
            $a['xj_id'] = $user_id;
            $a['jifen'] = $jifen;
            $a['time'] = time();
            M('dl_jifen') -> add($a);

            echo 0;
        }else{
            echo -1;
        }




    }


    //通过用户id得到他的没有发货的列表
    public function orderList(){
        $user_id = $_GET['user_id'];
        $data = M('order') -> where(['user_id' => $user_id,'state' => 1,'type' => 1])
            -> order("time desc")
            -> select();
        foreach($data as $k => $v){
            $res = M('shopping') -> where(['order_id' => $v['id']]) -> select();
            $data[$k]['count'] = count($res);
            unset($res);
        }
        $this -> assign('data',$data);
        $this -> display();
    }
	
	//通过用户id得到他的没有发货的列表
    public function xjorderList(){
        $this -> display();
    }
	
	public function xjorderListbb(){
		 $page = $_GET['p'];
         $start = ($page -1) * 8;
		 $sql = "select * from wx_order as a where a.user_id in (select id from wx_user where sj_userid = {$this->user_id}) and a.state = 1 and type = 0 order by a.time desc limit {$start},5 ;";
		 $mo = M();
		 $data = $mo -> query($sql);
		 foreach($data as $k => $v){
            $res = M('shopping') -> where(['order_id' => $v['id']]) -> select();
            $data[$k]['count'] = count($res);
            $data[$k]['pic_url'] = $res[0]['pic_url'];
            $data[$k]['shop_name'] = $res[0]['shop_name'];
            $data[$k]['atime'] = date('Y-m-d H:i:s',$v['time']);
            $data[$k]['name'] = M('user') -> getFieldById($v['user_id'],'name');
            unset($res);
        }
		$this -> ajaxReturn($data);
	}

    function xjfahuo(){
        $order_id = $_GET['order_id'];
        $res = M('shopping') -> where(['order_id' => $order_id]) -> select();
        $info = M('order') -> find($order_id);
        $address = M('address') -> find($info['address_id']);
        $this -> assign('order_id',$order_id);
        $this -> assign('address',$address);
        $this -> assign('info',$info);
        $this -> assign('money',$info['shopmoney']);
        $this -> assign('data',$res);
        $this -> display();
    }

    public function fahuo(){
        $order_id = $_POST['order_id'];
        $order_sn = $_POST['order_sn'];
        $kd_number = $_POST['kd_number'];
        $money = $_POST['money'];
//        $user_id = $_POST['user_id'];
        //发货
        $data['type'] = 1;
        $data['order_sn'] = $order_sn;
        $data['kd_number'] = $kd_number;
        $res = M('order') -> where(['id' => $order_id]) -> save($data);
        if($res){
            //把业绩添加到个人账户中去
//            M('user') -> where(['id' => $this-> user_id]) -> setInc('yeji',$money);
            //把用户发货记录保存到表中
            $info['user_id'] = $this->user_id;
            $info['order_id'] = $order_id;
            M('order_info') -> add($info);
            //发送发货通知
            $this -> sendTemplate($order_id);
            echo 0;
        }else{
            echo -1;
        }
    }


    public function sendTemplate($order_id){
        /* 发送模板消息通知 */
        //获取订单号
        $order_sn = M('order') -> getFieldById($order_id,'order_sn');
        $address_id = M('order') -> getFieldById($order_id,'address_id');
        $address = M('address') -> getFieldById($address_id,'address');

        //获取openid
        $user_id = M('order') -> getFieldById($order_id,'user_id');
        $u_id = M('user') -> getFieldById($user_id,'u_id');
        $openid = M('users') -> getFieldByUser_id($u_id,'openid');
        //获取收货信息
        $address_id = M('order') -> getFieldById($order_id,'address_id');
        $address_info = M('address') -> find($address_id);
        //获取快递名
        $kd_number = M('order') -> getFieldById($order_id,'kd_number');
        $kd_name = '';
        switch ($kd_number){
            case 'SF':
                $kd_name = '顺丰快递';
                break;
            case 'STO':
                $kd_name = '申通快递';
                break;
            case 'YD':
                $kd_name = '韵达快递';
                break;
            case 'YTO':
                $kd_name = '圆通速递';
                break;
            case 'ZJS':
                $kd_name = '宅急送';
                break;
            case 'ZTO':
                $kd_name = '中通速递';
                break;
            case 'AMAZON':
                $kd_name = '亚马逊物流';
                break;
        }
        $template = A("Pay/Template");
        $url = 'http://'.$_SERVER['SERVER_NAME'].U('/Home/Center/getWuliu')."?order_sn=$order_sn&kd=$kd_number";
        $template->send_shop($kd_name,$order_sn,$address_info['name'],$address_info['tel'],$address_info['address'],$openid,$url,$address);
    }


    function findWuliu(){
        $user_id = $this -> user_id;

        $data = M('order_info')
            ->alias("a")
            ->field("b.*")
            ->join("left join __ORDER__ as b on a.order_id = b.id")
            ->where(['a.user_id' => $user_id])
            ->order('a.time desc')
            ->select();
        foreach($data as $k => $v){
            $res = M('shopping') -> where(['order_id' => $v['id']]) -> select();
            $data[$k]['count'] = count($res);
            unset($res);
        }
//        dump($data);
//        exit;
        $this -> assign('data',$data);
        $this -> display();
    }

    function setUrl(){
        $user_id = $this -> user_id;
        $url = "http://" . $_SERVER['SERVER_NAME'];
		//得到自己的级别
		$type = M('user') -> getFieldById($this->user_id,'type');
		$this -> assign('type',$type);
        $this -> assign('url',$url);
        $this -> assign('user_id',$user_id);
        $this -> display();
    }
	
    //生成二维码
    function qr2(){
//        id=" + user_id + "&type=" + type + "&time=" + Math.round(new Date().getTime()/1000).toString();
        $id = $_GET['id'];
        $type = $_GET['type'];
        $time = $_GET['time'];
        if (! is_dir ( 'Public/webqr/' )) {
            mkdir ( 'Public/webqr/' );
        }
        import ( "Org.Util.Erweima" );
        $value = "http://" . $_SERVER['SERVER_NAME'] . "/Home/User/zhuce&id=" . $id . "&type=" . $type . "&time=" . $time;
        $errorCorrectionLevel = "L";
        $matrixPointSize = "6";
        $file_name = 'Public/webqr/' . $this -> user_id . '.png';
		if(file_exists($file_name)){
			unlink($file_name);
		}
        \QRcode::png ( $value,$file_name , $errorCorrectionLevel, $matrixPointSize, 1, true );
        $qrimg = A ( "Wxapi/Qrimg" );
        // $user_info = M('users') ->field("nickname,headimgurl") -> getByUser_id($this->user_id);
        // $user_info['headimgurl'] = substr($user_info['headimgurl'],1);
       // $qrimg->web_qr ( $this->user_id, '', '' );
        $this->assign ( 'name',$file_name);
        $url = "http://" . $_SERVER['SERVER_NAME'];
        //得到姓名
        $name = M('user') -> getFieldById($this->user_id,'name');
        $wxname = M('user') -> getFieldById($this->user_id,'wxname');
        $arr1['name'] = $name;
        $arr1['wxname'] = $wxname;
        //得到头像地址
        $u_id = M('user') -> getFieldById($this->user_id,'u_id');
        //$head_img = M('users') -> getFieldByUserId($u_id,'headimgurl');
		$head_img = M('qrset') -> getFieldById(1,'pic_url');
        $this -> assign('head',$head_img);
        $this -> assign('arr',$arr1);
        $this->assign('url', $url );
        $this->display ();
    }
	
	 //生成二维码
    function qr(){
//        id=" + user_id + "&type=" + type + "&time=" + Math.round(new Date().getTime()/1000).toString();
        $id = $_GET['id']*1;
        $type = $_GET['type']*1;
        $time = time();
        if (! is_dir ( 'Public/webqr/' )) {
            mkdir ( 'Public/webqr/' );
        }
        import ( "Org.Util.Erweima" );
        $value = "http://" . $_SERVER['SERVER_NAME'] . "/Home/User/zhuce&id=" . $id . "&type=" . $type . "&time=" . $time;
        $errorCorrectionLevel = "L";
        $matrixPointSize = "6";
        $flag = mt_rand(100,999);
        $file_name = 'Public/webqr/' . $this -> user_id . 'qr' . $flag .  '.png';
//		if(file_exists($file_name)){
//			unlink($file_name);
//		}
        \QRcode::png ( $value,$file_name , $errorCorrectionLevel, $matrixPointSize, 1, true );
        $qrimg = A ( "Wxapi/Qrimg" );
        // $user_info = M('users') ->field("nickname,headimgurl") -> getByUser_id($this->user_id);
        // $user_info['headimgurl'] = substr($user_info['headimgurl'],1);
       // $qrimg->web_qr ( $this->user_id, '', '' );
        $this->assign ('name',$file_name);
        $url = "http://" . $_SERVER['SERVER_NAME'];
        //得到姓名
        $name = M('user') -> getFieldById($this->user_id,'name');
        $wxname = M('user') -> getFieldById($this->user_id,'wxname');
        $arr1['name'] = $name;
        $arr1['wxname'] = $wxname;
        //得到头像地址
        $u_id = M('user') -> getFieldById($this->user_id,'u_id');
        $head_img = M('users') -> getFieldByUserId($u_id,'headimgurl');
		//$head_img = M('qrset') -> getFieldById(1,'pic_url');
        $this -> assign('head',$head_img);
        $this -> assign('arr',$arr1);
        $this->assign('url', $url );
        $this->display ();
    }
	
	//我的授权
    public function qr1(){
           $id = $_GET['id'];
        $type = $_GET['type'];
        $time = $_GET['time'];
        if (! is_dir ( 'Public/webqr/' )) {
            mkdir ( 'Public/webqr/' );
        }
        import ( "Org.Util.Erweima" );
        $value = "http://" . $_SERVER['SERVER_NAME'] . "/Home/User/zhuce&id=" . $id . "&type=" . $type . "&time=" . $time;
        $errorCorrectionLevel = "L";
        $matrixPointSize = "6";
        $a = mt_rand(100,999);
        $file_name = 'Public/webqr/' . $this -> user_id . 'qr' . $a .  '.jpg';
		if(file_exists($file_name)){
			unlink($file_name);
		}
        \QRcode::png ( $value,$file_name , $errorCorrectionLevel, $matrixPointSize, 1, true );
		$qrimg = A ( "Wxapi/Qrimg" );
		// $user_info = M('users') ->field("nickname,headimgurl") -> getByUser_id($this->user_id);
		// $user_info['headimgurl'] = substr($user_info['headimgurl'],1);
		//dump($data[0]['tel']);
		$name = M('user') -> getFieldById($this->user_id,'name');
		$wxname = M('user') -> getFieldById($this->user_id,'wxname');
		
		$qrimg->index1( $this->user_id,$name,$wxname,'30分钟有效期');
		$this->assign ( 'user_id', $this->user_id );
		$this->display ('shouquan');
    }
	
	// function qr() {
		// // 得到后台设定的域名
		
		// $yuming = $yuming [0] ['url'];
		
		
		// if (! is_dir ( 'Public/webqr/' )) {
			// mkdir ( 'Public/webqr/' );
		// }
		// //if (! file_exists ( 'Public/webqr/' . $this->user_id . '.png' )) {
		// import ( "Org.Util.Erweima" );
		// $value = 'http://' . $yuming . "/home/index/tiaozhuan?id=" . $this->user_id;
		// $errorCorrectionLevel = "L";
		// $matrixPointSize = "6";
		// \QRcode::png ( $value, 'Public/webqr/' . $this->user_id . '.png', $errorCorrectionLevel, $matrixPointSize, 1, true );
		// //}
		// $qrimg = A ( "Wxapi/Qrimg" );
		// // $user_info = M('users') ->field("nickname,headimgurl") -> getByUser_id($this->user_id);
		// // $user_info['headimgurl'] = substr($user_info['headimgurl'],1);
		// $qrimg->web_qr ( $this->user_id, '', '' );

		// $this->assign ( 'user_id', $this->user_id );
		// $this->display ();
	// }
	// public function qr(){
		// $users=M('users')->getByUser_id($this->user_id);
		// $ewm=A("Wxapi/Weixin");
		// if(!file_exists('public/head_pic/'.$this->user_id.'.jpg')){
			// $ewm->save_head_pic($this->user_id);
		// }
		// if (! file_exists ( "Public/qrimg/{$this->user_id}.jpg" )) {
		
		    // $ewm->get_qr_limit($this->user_id,$this->user_id);
		// }

			// if (! is_dir ( 'Public/qrimg/' )) {
				// mkdir ( 'Public/qrimg/' );
			// }
		// $qrimg = A ( "Wxapi/Qrimg" );
			//$user_info = M('users') ->field("nickname,headimgurl") -> getByUser_id($this->user_id);
			//$user_info['headimgurl'] = substr($user_info['headimgurl'],1);
			
			// $qrimg->index ( $this->user_id,$this->user_id );

		// $this->assign ( 'user_id', $this->user_id );
		// $this->display ();	
	// }
	
	

    public function setPage($count){
        $pagecount = 10;
        $Page = new Pageajax($count,$pagecount);
        $Page->setConfig('first','首页');
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('last','尾页');
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$pagecount.' 条/页 共 %TOTAL_ROW% 条)');
        return $Page;
    }

    //代理设置商品库存
    public function shopKuCun(){
        //$data = M('shop') -> where(['type' => ['neq',3]]) ->  order('id asc') -> select();
         $data = M('shop') -> where("type != 3") ->  order('id asc') -> select();
        $type = M('user') -> getFieldById($this -> user_id,'type');
		//$a = M('user') -> where(['sj_userid' => $this->user_id]) -> select();
        $arr = array();
        $res = M('dl_kucun') -> where(['user_id' => $this ->user_id]) -> select();
        foreach ($data as $k => $v){
            //得到库存数量
            foreach ($res as $key => $val){
                if($val['shop_id'] == $v['id']){
                    $data[$k]['kucun'] = $val['kucun'];
                }
            }
			if(!$data[$k]['kucun']){
				unset($data[$k]);
			}
        }
        $this -> assign('data',$data);
        $this -> assign('type',$type);
        //$this -> assign('arr',$a);
        $this -> assign('isLogin',1);
        $this -> display();
    }
	
	
	//库存
	public function syzTiHuo(){
		if($_GET['address_id']){
			$address_id = $_GET['address_id'];
			$address_id = M('address') -> find($address_id);
			$order_id = session('oid');
			$info = M('shopping') -> where(['order_id' => $order_id]) -> select();
			$info = $info[0];
			$arr[0]['shop_name'] = $info['shop_name'];
			$arr[0]['size'] = $info['size'];
			$arr[0]['danwei'] = $info['danwei'];
			$arr[0]['number'] = $info['gmnumber'];
		}else{
			$number = $_GET['number'];
			$shop_id = $_GET['shop_id'];
			$res = M('address') -> where(['user_id' => $this -> user_id]) -> select();
			if(!$res){
				$this -> error('请添加收货地址','addressList');
			}
			$data['user_id'] = $this -> user_id;
			$data['shopmoney'] = 0;
			$data['state'] = 1;
			//$data['type'] = 2;
			$data['time'] = time();
			$order_id = M('order') -> add($data);
			session('oid',$order_id);
			$info = M('shop') -> find($shop_id);
			//保存到商品表中shopping中
			$data1['order_id'] = $order_id;
			$data1['shop_name'] = $info['title'];
			$data1['money'] = 0;
			$data1['gmnumber'] = $number;
			$data1['size'] = $info['size'];
			$data1['danwei'] = $info['guige'];
			$data1['pic_url'] = $info['img_url'];
			$data1['shop_id'] = $shop_id;
			M('shopping') -> add($data1);
			M('dl_kucun') -> where(['user_id' => $this -> user_id,'shop_id' => $shop_id]) -> setDec('kucun',$number);
			$arr[0]['shop_name'] = $info['title'];
			$arr[0]['size'] = $info['size'];
			$arr[0]['danwei'] = $info['guige'];
			$arr[0]['number'] = $number;
			$address_id = $res[0];
		}
		$this -> assign('addList',$address_id);
		$this -> assign('order_id',$order_id);
        $this -> assign('money',0);
        $this -> assign('data',$arr);
        $this -> display();
	}
	public function yuncangtihuoajax(){
		$str = $_POST['str'];
		$data = array();
		$pattern = '/a,[0-9]+,[0-9]+/';
		$result = $res = preg_match_all($pattern,$str,$data);
		$data = $data[0];
		$a = array();
		foreach($data as $k => $v){
			$da = explode(',',$v);
			$c['shop_id'] = $da[2];
			$c['number'] = $da[1];
			//file_put_contents('shop.txt',$c['shop_id']);
			if($c['number'] != 0){
				$a[] = $c;
			}
			
		}
		$data = $a;

		session('thnum',$data);
		
		//$add = $res[0];
		//if(!session('oid')){
		$data2['user_id'] = $this -> user_id;
		$data2['shopmoney'] = 0;
		$data2['state'] = 0;
		//$data['type'] = 2;
		$data2['time'] = time();
		$res = $order_id = M('order') -> add($data2);
		session('oid',$order_id);
		//dump($data);
		$fee = 0;
		foreach($data as $k => $v){
			$number = $v['number'];
			$shop_id = $v['shop_id'];
			
			$info = M('shop') -> find($shop_id);
			//dump($v);
			//dump($info);
			//保存到商品表中shopping中
			$data1['order_id'] = $order_id;
			$data1['shop_name'] = $info['title'];
			
			$data1['gmnumber'] = $number;
			$data1['size'] = $info['size'];
			$data1['danwei'] = $info['guige'];
			$data1['pic_url'] = $info['img_url'];
			$data1['shop_id'] = $shop_id;
			//计算提货的金额
			$data1['money'] = $this -> setTiHuoMoney($shop_id,$number);
			$fee = $fee + $data1['money'];
			$re = M('shopping') -> add($data1);
			
			$arr1['shop_name'] = $info['title'];
			$arr1['size'] = $info['size'];
			$arr1['danwei'] = $info['guige'];
			$arr1['number'] = $number;
			$arr[] = $arr1;
			//$address_id = $res[0];
		}
		//把前保存到订单表中
		M('order') -> where(['id' => $order_id]) -> setField('shopmoney',$fee);
		if($res){
			echo 0;
		}
		
	}
	
	//根据当前人的级别的得到不同的钱数
	private function setTiHuoMoney($shop_id,$number){
		//得到上级的id
		//$sj_userid = M('user') -> getFieldById($this -> user_id,'sj_userid');
		// if($sj_userid != 0){
			// $a = $this -> getTiHuoSj($sj_userid);
			// $type = M('user') -> getFieldById($a,'type');
			// $info = M('shop') -> field('fee1,fee2,fee3,fee4') -> find($shop_id);
			// switch($type){
				// case 1:
				// return $info['fee1'] * $number;
				// break;
				// case 2:
				// return $info['fee2'] * $number;
				// break;
				// case 3:
				// return $info['fee3'] * $number;
				// break;
				// case 4:
				// return $info['fee4'] * $number;
				// break;
			// }
		// }else{
			$type = M('user') -> getFieldById($this->user_id,'type');
			$info = M('shop') -> field('fee1,fee2,fee3,fee4') -> find($shop_id);
			switch($type){
				case 1:
				return $info['fee1'] * $number;
				break;
				case 2:
				return $info['fee2'] * $number;
				break;
				case 3:
				return $info['fee3'] * $number;
				break;
				case 4:
				return $info['fee4'] * $number;
				break;
			}
		// }
	}
	
	private function getTiHuoSj($user_id){
		$a = 0;
		$sj_userid = M('user') -> getFieldById($user_id,'sj_userid');
		if($sj_userid == 0){
			return $user_id;
		}else{
			$a = $this -> getTiHuoSj($sj_userid);
		}
		return $a;
	}
	
	
	public function yuncangTiHuo(){
			if($_GET['address_id']){
				$data = session('thnum');
				$add = M('address') -> find($_GET['address_id']);
				$order_id = session('oid');
				//session('oid',null);
				//$arr = M('shopping') -> where(['order_id' => $order_id]) -> select();
			}else{
				$res = M('address') -> where(['user_id' => $this -> user_id]) -> select();
				if(!$res){
					$this -> error('请添加收货地址','addressList');
					exit;
				}
				$add = $res[0];
				$data = session('thnum');
				$order_id = session('oid');
				//}
				
			}
			$this -> assign('number',count($data));
			$this -> assign('order_id',$order_id);
			$this -> assign('add',$add);
			$this -> display();
    }
	
	//提货
	public function shiyongzhuangTh(){
		$order_id = $_POST['order_id'];
		$address_id = $_POST['address_id'];
        $address_name = M('address') -> getFieldById($address_id,'name');
        if(!$address_name){
            echo -4;exit;
        }
		$b = M('shopping') -> where(['order_id' => $order_id]) -> select();
		$b = count($b);
		if(!$b){
			echo -2;
			exit;
		}
		
		//减少库存
		$shopList = M('shopping') -> where(['order_id' => $order_id]) -> select();
		$flag = false;
		foreach($shopList as $k => $v){
			//判断是否有货了
			$num = M('dl_kucun') -> where(['user_id' => $this -> user_id,'shop_id' => $v['shop_id']]) -> select();
			$num = $num[0]['kucun'];
			if($num < $v['gmnumber']){
				$flag = true;
				break;
			}
			
		}
		if($flag){
			echo -3;
			exit;
		}
		foreach($shopList as $k => $v){
			M('dl_kucun') -> where(['user_id' => $this -> user_id,'shop_id' => $v['shop_id']]) -> setDec('kucun',$v['gmnumber']);
		}
		$data['address_id'] = $_POST['address_id'];
		$data['content'] = $_POST['content'];
		$data['state'] = 1;
		$data['type'] = 2;
		$res = M('order') -> where(['id' => $order_id]) -> save($data);
		//记录到提货表中
		$data1['user_id'] = $this -> user_id;
		$data1['order_id'] = $order_id;
		$data1['time'] = time();
		$res = M('tihuo') -> add($data1);
		if($res){
			echo 0;
		}else{
			echo -1;
		}
		
	}
	
    //查看下级的提货记录
    public function xjTihuo(){
        $this -> display();
    }

    public function xjTihuobb(){
        $page = $_GET['p'];
        $start = ($page -1) * 5;
        // $data = M('zhuanyi')
            // -> alias('a')
            // -> field('a.*,a.time as atime,a.number as anum,b.name,c.*')
            // -> join("left join __USER__ as b on a.user_id = b.id")
            // -> join("left join __SHOP__ as c on a.shop_id = c.id")
            // -> where(['a.sj_userid' => $this->user_id])
            // -> limit($start,20)
            // -> order('a.time desc')
            // -> select();
		$data = M('zyorder') 
			-> alias('a')
			-> field('a.*,b.name')
			-> join("left join __USER__ as b on a.user_id = b.id")
			-> where(" a.sj_userid = {$this->user_id}") 
			-> order('a.time desc')
			-> limit($start,5)
			-> select();
		$model = M('shop');
		$model1 = M('zhuanyi');
        foreach ($data as $k => $v){
            $data[$k]['atime'] = date('Y-m-d H:i:s',$v['time']);
			//拿第一个信息
			$shopInfo = M('zhuanyi')-> alias('c')
				-> field('b.*')
				-> join("left join __SHOP__ as b on c.shop_id = b.id")
				-> where(" c.order_id = {$v['id']} ")
				-> limit(0,1)
				-> select();
				$shopInfo = $shopInfo[0];
			$data[$k]['img_url'] = $shopInfo['img_url'];
			$data[$k]['title'] = $shopInfo['title'];
			$data[$k]['size'] = $shopInfo['size'];
			$data[$k]['guige'] = $shopInfo['guige'];
		}
        $this -> ajaxReturn($data);
    }
	
	public function zhuanyiXiangQing(){
		$order_id = $_GET['order_id'];
		//得到转移订单表的信息展示订单信息
		$data = M('zhuanyi')
			-> alias('a')
			-> field('a.number,b.title,b.size,b.guige')
			-> join("left join __SHOP__ as b on a.shop_id = b.id")
			-> where(" a.order_id = {$order_id}")
			-> select();
		$this -> assign('data',$data);
		$this -> display();
	}
	
	//转移库存到下级人员中
	public function zhuanyiKuCun(){
		$shop_id = $_POST['shop_id'];
		$number = $_POST['number'];
		$user_id = $_POST['user_id'];
		$a = M('dl_kucun') -> where(['shop_id' => $shop_id,'user_id' => $this->user_id]) -> select();
		if($a[0]['kucun'] < $number){
			echo -1;
			exit;
		}
		M('dl_kucun') -> where(['shop_id' => $shop_id,'user_id' => $this->user_id]) -> setDec('kucun',$number);
		$res = M('dl_kucun') -> where(['user_id' => $user_id,'shop_id' => $shop_id]) -> select();
		if($res){
			$b = M('dl_kucun') -> where(['user_id' => $user_id ,'shop_id' => $shop_id]) -> setInc('kucun',$number);
		}else{
			$da['user_id'] = $user_id;
			$da['kucun'] = $number;
			$da['shop_id'] = $shop_id;
			$b = M('dl_kucun') -> add($da);
		}
		if($b){
			//记录到转移表中
			$a['user_id'] = $user_id;
			$a['sj_userid'] = $this -> user_id;
			$a['shop_id'] = $shop_id;
			$a['time'] = time();
			$a['number'] = $number;
			M('zhuanyi') -> add($a);
			echo 0;
		}else{
			echo -2;
		}
	}
	
	public function testXj(){
		$res = M('user') -> where(['sj_userid' => $this -> user_id]) -> select();
		if($res){
			echo 0;
		}else{echo -1;}
	}
	
	//新的转移
	public function zhuanyi(){
		$str = $_GET['str'];
		$data = array();
        $pattern = '/a,[0-9]+,[0-9]+/';
        $result = $res = preg_match_all($pattern,$str,$data);
		$data = $data[0];
		$a = array();
		$mo = 0;
		foreach($data as $k => $v){
			$da = explode(',',$v);
			$c['shop_id'] = $da[2];
			
			$c['number'] = $da[1];
			if($c['number'] != 0){
				$a[] = $c;
			}
			
			$mo += $c['number'];
			
		}
		if($mo == 0){
			$this -> error('请选择商品','shopKuCun');
		}
		$data = $a;
		session('zyarr',$data);
		//查找自己的直属下级
		$userList = M('user') -> field("id,name") ->  where(['sj_userid' => $this->user_id]) -> select();
        //$this -> assign('data',$data);
        $this -> assign('userList',$userList);
		$this -> display();
	}
	
	//新的转移
	public function zhuanyibb(){
		$data = session('zyarr');
		$user_id = $_POST['user_id'];
		$flag1 = false;
		$flag2 = false;
		$flag3 = false;
		//保存到转移订单表
		$data2['user_id'] = $user_id;
		$data2['sj_userid'] = $this -> user_id;
		$data2['time'] = time();
		$order_id = M('zyorder') -> add($data2);
		$numb = 0;
		foreach($data as $k => $v){
			$shop_id = $v['shop_id'];
			$number = $v['number'];
			$a = M('dl_kucun') -> where(['shop_id' => $shop_id,'user_id' => $this->user_id]) -> select();
			if($a[0]['kucun'] < $number){
				$flag1 = true;
				break;
			}
			M('dl_kucun') -> where(['shop_id' => $shop_id,'user_id' => $this->user_id]) -> setDec('kucun',$number);
			$res = M('dl_kucun') -> where(['user_id' => $user_id,'shop_id' => $shop_id]) -> select();
			if($res){
				$b = M('dl_kucun') -> where(['user_id' => $user_id ,'shop_id' => $shop_id]) -> setInc('kucun',$number);
			}else{
				$da['user_id'] = $user_id;
				$da['kucun'] = $number;
				$da['shop_id'] = $shop_id;
				$b = M('dl_kucun') -> add($da);
			}
			if($b){
				//记录到转移表中
				$a['user_id'] = $user_id;
				$a['sj_userid'] = $this -> user_id;
				$a['shop_id'] = $shop_id;
				$a['time'] = time();
				$a['number'] = $number;
				$a['order_id'] = $order_id;
				M('zhuanyi') -> add($a);
				$numb +=  $number;
				$str = M('shop') -> getFieldById($shop_id,'title');
				$flag2 = true;
			}else{
				$flag3 = true;
			}
			
		}
		if($flag1){
				echo -1;
		}else if($flag2){
			//发送模板提醒
			$this -> send_sjToXj($user_id,$str,$numb);
				echo 0;
		}else if($flag3){
				echo -2;
		}
	}
	
	private function send_sjToXj($user_id,$name,$number){
		$u_id = M('user') -> getFieldById($user_id,'u_id');
        $openid = M('users') -> getFieldByUserId($u_id,'openid');
		$template = A("Pay/Template");
        $url = 'http://'.$_SERVER['SERVER_NAME'].U('/Home/Center/Index');
        $template->send_sjToXj($number,$name,$openid,$url);
	}
	

    //个人业绩
    public function yeji(){
        $yeji = M('user') -> getFieldById($this->user_id,'yeji');
//        $data = M('yeji')
//            -> alias('a')
//            -> field('a.time as yjtime,a.number as yjnumber,b.name')
//            -> join("left join __USER__ as b on a.user_id = b.id")
//            -> where(['a.sj_userid' => $this->user_id])
//            -> order('a.time desc')
//            -> select();
//        dump($data);

        $this -> assign('yeji',$yeji);
//        $this -> assign('data',$data);
        $this -> display();
    }

    public function yejibb(){
        $page = $_GET['p'];
        $start = ($page -1) * 20;
        $data = M('yeji')
            -> alias('a')
            -> field('a.time as yjtime,a.number as yjnumber,b.name')
            -> join("left join __USER__ as b on a.user_id = b.id")
            -> where(['a.sj_userid' => $this->user_id])
            -> limit($start,20)
            -> order('a.time desc')
            -> select();
        foreach ($data as $k => $v){
            $data[$k]['yjtime'] = date('Y-m-d H:i:s',$v['yjtime']);
        }
        $this -> ajaxReturn($data);
    }

    public function chongzhiJf(){
		$type = M('user') -> getFieldById($this->user_id,'type');
		$ty = '';
		switch($type){
			case 1:
				$ty = '总代';
				break;
			case 2:
				$ty = '特级';
				break;
			case 3:
				$ty = '一级';
				break;
			case 4:
				$ty = '初级';
				break;
				
		}
	
		$this -> assign('type',$ty);
        $this -> display();

    }

    //充值信息提交到充值表中
    public function setChongzhi(){
        // if($_FILES['file']['error'] != 0){
            // $this -> error('请上传凭证','chongzhiJf');
            // exit;
        // }
		$c = M('chongzhi') -> where(" user_id = {$this -> user_id} and state = 0") -> select();
		if($c){
			$this -> error('你有未成功充值的订单，请稍候再试','chongzhiJf');
			exit;
		}
		
        $data = $_POST;
		$data['user_id'] = $this -> user_id;
		$data['name'] = M('user') -> getFieldById($this->user_id,'name');
        $data['pic_url'] = $this -> uploadPic('pingzheng');
        $res = M('chongzhi') -> add($data);
        if($res){
            $this -> success('后台审核中','index');
        }else{
            $this -> error('出错了','chongzhiJf');
        }
    }

    //上传图片信息，并且保存图片到服务器
    private function uploadPic($file){
        $upload = new Upload();
        $upload -> maxSize = 10145728;
        $upload -> exts = array('jpg','png','jpeg');
        $upload -> rootPath = './Uploads/';
        $upload -> savePath = $file . '/';
        $upload -> saveName = 'time';
        $info = $upload -> upload();
        if(!$info){
            $this -> error($upload -> getError());
        }else{
            $xw_img = '/Uploads/' . $info['file']['savepath'] . $info['file']['savename'];
            $image = new Image();
            $image -> open('.' . $xw_img);
            $image -> thumb(720,540) -> save('.' . $xw_img);
            return $xw_img;
        }
    }
	
	//我要升级页面
	public function shengji(){
		$type = M('user') -> getFieldById($this->user_id,'type');
		switch($type){
			case 1:
				$type = '总代';
				break;
			case 2:
				$type = '特级';
				break;
			case 3:
				$type = '一级';
				break;
			case 4:
				$type = '初级';
				break;
		}
		$money = M('user') -> getFieldById($this -> user_id,'jifen');
		$this -> assign('money',$money);
		$this -> assign('type',$type);
		$this -> display();
		
	}
	
	//上传升级信息
	public function setShengji(){
		$type3 = M('user') -> getFieldById($this -> user_id,'type');
		if($type3 == 1){
			$this -> error('你已经是总代，不能再升级了','Index');
			exit;
		}
		//判断是否有为升级的处理
		$c = M('shengji') -> where(" user_id = {$this -> user_id} and state = 0") -> select();
		if($c){
			$this -> error('你有未操作升级，请稍候再试','Index');
			exit;
		}
		
		
		
		if($_FILES['file']['error'] != 0){
            $this -> error('请上传凭证','shengji');
            exit;
        }
		switch($_POST['type']){
			case '总代':
				$_POST['type'] = 1;
				break;
			case '特级':
				$_POST['type'] = 2;
				break;
			case '一级':
				$_POST['type'] = 3;
				break;
			case '请选择':
				$this -> error('请选择升级级别','shengji');
				exit;
				breakk;
		}
        $data = $_POST;
		$data['user_id'] = $this -> user_id;
		$data['name'] = M('user') -> getFieldById($this->user_id,'name');
        $data['pic_url'] = $this -> uploadPic('pingzheng');
        $res = M('shengji') -> add($data);
        if($res){
            $this -> success('后台审核中','Index');
        }else{
            $this -> error('出错了','shengji');
        }
	}

    public function sendTemplate1($openid,$order_id){
        /* 发送模板消息通知 */
        //获取订单号
        $order_sn = M('order') -> getFieldById($order_id,'id');
        $address_id = M('order') -> getFieldById($order_id,'address_id');
        $info = M('address') -> find($address_id);
        $money = M('order') -> getFieldById($order_id,'shopmoney');
        $user_id = M('order') -> getFieldById($order_id,'user_id');
        $name = M('user') -> getFieldById($user_id,'name');
//        $order_sn,$name,$money,$url,$openid
        $template = A("Pay/Template");
        $url = 'http://'.$_SERVER['SERVER_NAME'].U('/Home/Center/ljfahuo')."?order_id=$order_sn";
        $template->send_order($order_sn,$name,$money,$url,$openid,$info['address'],$info['tel']);
    }

    //发送上级没商品库存模板消息
    public function sendTemplateShop($sj_userid,$shop_id){
        $u_id = M('user') -> getFieldById($sj_userid,'u_id');
        $openid = M('users') -> getFieldByUserId($u_id,'openid');
        $kucun = M('dl_kucun') -> where(['user_id' => $sj_userid,'shop_id' => $shop_id]) -> select();
        $kucun = $kucun[0]['kucun'];
        $shop_name = M('shop') -> getFieldById($shop_id,'title');
        $template = A("Pay/Template");
        $url = 'http://'.$_SERVER['SERVER_NAME'].U('/Home/Center/shop');
        $template->send_shopKucun($kucun,$shop_name,$openid,$url);
    }

    //给直推的人发送佣金，上级余额不足提醒
    public function sendTemplateMoney($sj_userid){
        $u_id = M('user') -> getFieldById($sj_userid,'u_id');
        $openid = M('users') -> getFieldByUserId($u_id,'openid');
        $money = M('user') -> getFieldById($sj_userid,'jifen');
        $template = A("Pay/Template");
        $url = 'http://'.$_SERVER['SERVER_NAME'].U('/Home/Center/index');
		$name = M('user') -> getFieldById($this->user_id,'name');
        $template->sendTemplateMoney($name,$money,$openid,$url);
    }

    //给直推的人发送推荐奖
    public function sendTemplateZtMoney($money,$name,$zt_userid){
        $u_id = M('user') -> getFieldById($zt_userid,'u_id');
        $openid = M('users') -> getFieldByUserId($u_id,'openid');
        $info = "{$name}购买商品返还推荐奖";
        $template = A("Pay/Template");
        $url = 'http://'.$_SERVER['SERVER_NAME'].U('/Home/Center/yongjinList');
        $template->send_money($money,$info,$openid,$url);
    }

	//我的钱包的页面
	public function wodeqianbao(){
		$money = M('user') -> getFieldById($this -> user_id,'money');
		$yeji = M('user') -> getFieldById($this -> user_id,'jifen');
		$type = M('user') -> getFieldById($this -> user_id,'type');
		//佣金记录
		$data = M('fanyong') -> where(['user_id' => $this -> user_id]) -> order('time desc') -> select();
		//积分记录
		$da = M('jifen') -> where(['user_id' => $this->user_id]) -> order('time desc') -> select();
		$this -> assign('data',$data);
		$this -> assign('da',$da);
		$this -> assign('money',$money);
		$this -> assign('type',$type);
		$this -> assign('yeji',$yeji);
		$this -> display();
	}
	
	public function sendSjKouFei($user_id,$money){
		$u_id = M('user') -> getFieldById($user_id,'u_id');
        $openid = M('users') -> getFieldByUserId($u_id,'openid');
		$name = M('user') -> getFieldById($this->user_id,'name');
        $info = "下级" . $name . "购买商品,发放推荐奖";
        $template = A("Pay/Template");
        $url = 'http://'.$_SERVER['SERVER_NAME'].U('/Home/Center/Index');
        $template->send_sjKouFei($money,$info,$openid,$url);
	}








}