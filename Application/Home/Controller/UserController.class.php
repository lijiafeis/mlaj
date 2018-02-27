<?php
namespace Home\Controller;
use Think\Controller;

class UserController extends Controller {


    // /*用户来，判断session存不存在，*/
    function __construct(){
		if(I('id') || I('type')){
			session('sj_userid',$_GET['id']);
			session('type',$_GET['type']);
			
		}
        parent::__construct();
        $time = $_GET['time'];
//        dump(date('Y-m-d H:i:s',$time));
        if($time){
            if(time() - $time > 1800){
                echo '链接已失效，请重新生成二维码';
                exit;
            }else{
		
              //保存信息
				
                if(!session('xigua_user_id')){
                    /*去判断用户身份并缓存id*/
                    $redirect_uri='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                    redirect('http://'.$_SERVER['HTTP_HOST'].U('/wxapi/oauth/index/')."?surl=".$redirect_uri);exit;
                }else{
                    $this -> user_id = session('xigua_user_id');
                }
            }
        }else{
          
            if(!session('xigua_user_id')){
                /*去判断用户身份并缓存id*/
                $redirect_uri='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                redirect('http://'.$_SERVER['HTTP_HOST'].U('/wxapi/oauth/index/')."?surl=".$redirect_uri);exit;
            }else{
                $this -> user_id = session('xigua_user_id');
				$this -> u_id = M('user') -> getFieldByUId($this->user_id,'id');
            }
        }





    }



    //登录验证
    public function loginValidate(){
        $tel = $_POST['tel'];
        $password = $_POST['password'];
        $res = M('user') -> where(['tel' => $tel,'password' => $password]) -> select();
        if($res){
			if($res[0]['state'] == 0){
				echo -2;
			}else{
			    //判断是否在自己的微信号上登录
//                if($res[0]['u_id'] == $this->user_id){
                    session('flag',1);
					cookie('username',$tel);
					cookie('password',$password);
                    echo 0;
//                }else{
//                    echo -3;
//                }
			}
           
        }else{
            echo -1;
        }
    }

    //用户注册
    public function zhuce(){
        if(IS_GET){
            
            $sj_userid = $_GET['id'];
            $sj_userid = intval($sj_userid);
//            dump(session('sj_userid'));
//            dump(session('type'));
//            exit;
            $type = $_GET['type'];
			//dump($sj_userid);
			//exit;
            $code = A('Wxapi/ImageCode');
            $img_url = $code -> makeImage();
            session('img_url',$img_url);
//            dump($img_url);
//            dump($_SERVER['SERVER_NAME'] . '/' .$img_url);
            $img_url = "http://" . $_SERVER['SERVER_NAME'] . '/' .$img_url;
            $this -> assign('img',$img_url);
            $this -> assign('sj_userid',$sj_userid);
            $this -> assign('type',$type);
            $this -> display();
        }else{
           $tel = $_POST['tel'];
           $yz_number = $_POST['yz_number'];
//           $img_code = $_POST['code'];
//           $img = A('Wxapi/ImageCode');
//           $re = $img -> checkCode($img_code);
//           if(!$re){
//               echo -3;
//           }else{
               //删除验证码图片
//                $img_url = session('img_url');
//               $file_name = dirname(dirname(dirname(__DIR__))) . $img_url;
//               @unlink($file_name);
               $code = session('xigua_code');
               if($yz_number != $code){
                   echo -1;
                   exit;
               }
               $res = M('user') -> where(['tel' => $tel]) -> select();
               if($res){
                   echo -2;
               }else{
                   echo 0;
               }
//           }


        }

    }

//    public function getImgUrl(){
//        file_put_contents('111.txt','a');
//        $code = A('Wxapi/ImageCode');
//        $img_url1 = $code -> makeImage();
//        $img_url = session('img_url');
//        $file_name = dirname(dirname(dirname(__DIR__))) . '\\' . $img_url;
//        file_put_contents('222.txt',$file_name);
//        @unlink($file_name);
//        session('img_url',$img_url1);
////            dump($img_url);
////            dump($_SERVER['SERVER_NAME'] . '/' .$img_url);
//        $img_url1 = "http://" . $_SERVER['SERVER_NAME'] . '/' .$img_url1;
//        $this -> ajaxReturn($img_url1);
//    }
    //用户注册填写信息
    public function zhuce_two(){
        $tel = $_GET['tel'];
        $sj_userid = session('sj_userid');
        $type = session('type');
        $this -> assign('tel',$tel);
        $this -> assign('sj_userid',$sj_userid);
        $this -> assign('type',$type);
        $this -> display();
    }

    //发送验证码
    public function getCode(){
		//file_put_contents('a.txt','12dfsa');
        $juhe = A("Xigua/Juhe");
        $code = rand(100000,999999);
        $tel = $_POST['tel'];
        if (!$tel){$data = 0;}
        $appname = "信息完善";
        $time = session('time');
        if(time() - $time < 60){
            $data = -1;
        }else{
            $juhe-> msg_everify($code,$tel,$appname);
            session('xigua_code',$code);
            $data = 1;
            session('time',time());
        }
        $this->ajaxReturn($data);

    }

    //保存注册信息到数据库
    public function setZhuce(){
        $data = $_POST;
        $data['u_id'] = $this -> user_id;
		//保存信息
		$res = M('user') -> where(['u_id' => $this->user_id]) -> select();
		if($res){
			echo -2;
			exit;
		}
		$data['zt_userid'] = session('sj_userid');
		if($data['zt_userid'] == 0){
			//平台给的链接
			$data['sj_userid'] = 0;
		}else{
			$type = session('type');
			$sjType = M('user') -> getFieldById(session('sj_userid'),'type');
			if($type == $sjType){
				//两个级别一样
				$data['sj_userid'] = M('user') -> getFieldById(session('sj_userid'),'sj_userid');
			
			}else if($type  - $sjType > 0){
				$data['sj_userid'] = session('sj_userid');
				$data['zt_userid'] = 0;
			}else if($type - $sjType < 0){
			    $id = M('user') -> getFieldById(session('sj_userid'),'sj_userid');
				$data['sj_userid'] = $this -> setSjUser($type,$id);
			}
		}
		$data['type'] = session('type');
		$res = M('user') -> add($data);
        if($res){
			cookie('username',$data['tel']);
			cookie('password',$data['password']);
            echo 0;
        }else{
            echo -1;
        }


    }

    private function setSjUser($type,$sj_userid){
        if($sj_userid == 0){
            return 0;
        }
        //得到上级的级别
        $sjType = M('user') -> getFieldById($sj_userid,'type');
        if($type == $sjType){
            //两个级别一样
            return M('user') -> getFieldById($sj_userid,'sj_userid');
        }else if($type  - $sjType > 0){
            return $sj_userid;
        }else if($type - $sjType < 0){
            $id = M('user') -> getFieldById($sj_userid,'sj_userid');
            return $this -> setSjUser($type,$id);
        }
    }

    //忘记密码
    public function wangjiPass(){
        if(IS_GET){
            $this -> display();
        }else{
            $tel = $_POST['tel'];
            $yz_number = $_POST['yz_number'];
            $code = session('xigua_code');
            if($yz_number != $code){
                echo -1;
                exit;
            }
            $res = M('user') -> where(['tel' => $tel]) -> select();
            if($res){
                $this -> ajaxReturn($res[0]['password']);
            }else{
                echo 0;
            }

        }
    }

    //修改密码
    public function updatePass(){
        if(IS_GET){
            $this -> display();
        }else{
            $oldPass = $_POST['oldPass'];
            $newPass = $_POST['newPass'];
            $res = M('user') -> where(['id' => $this -> u_id,'password' => $oldPass]) -> select();
            if($res){
                $res = M('user') -> where(['id' => $this -> u_id,'password' => $oldPass]) -> setField('password',$newPass);
                if($res){
                    echo 0;
                }else{
                    echo -2;
                }
            }else{
                echo -1;
            }
        }

    }
	
	
	//我的授权书
	public function setMyShouQuan(){
		//姓名，微信号 和级别
		$data = M('user') -> find($this -> u_id);
		$name = $data['name'];
		$wxname = $data['wxname'];
		$type = $data['type'];
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
		
		//生成每个人的授权书
		$qrimg = A('Wxapi/Qrimg');
		$qrimg -> setShouQuan($this -> u_id,$name,$wxname,$type);
		//生成后显示
		$this -> assign('user_id',$this->u_id);
		$this -> display();
		
	}
	
}