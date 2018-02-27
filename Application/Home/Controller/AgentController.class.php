<?php
/**
 * 代理商查找
 * User: Administrator
 * Date: 2017/4/25 0025
 * Time: 15:10
 */
namespace Home\Controller;

use Think\Controller;

class AgentController extends Controller {
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
            $id = M('user') -> where(['u_id' => $this -> u_id]) -> getField('id');
            $this -> user_id = $id;
        }
    }

    public function index(){
        $this -> display();
    }

    public function getAgentForNumber(){
        $number = $_GET['number'];
        $res = M('user') 
			-> alias("a")
			-> field("a.*,b.headimgurl")
			-> join("left join __USERS__ as b on a.u_id = b.user_id")
			-> where(['a.tel' => $number]) -> select();
        if($res){
            $this -> assign('data',$res);
        }else{
            $res = M('user')
			-> alias("a")
			-> field("a.*,b.headimgurl")
			-> join("left join __USERS__ as b on a.u_id = b.user_id")
			-> where(['a.wxname' => $number]) 
			-> select();
            if($res){
                $this -> assign('data',$res);
            }else{
                $this -> assign('data',0);
            }
        }
        $this -> display();
    }
	
	public function test(){
		$number = $_POST['number'];
		 $res = M('user') -> where(['tel' => $number]) -> select();
		 if($res){
           echo 0;
        }else{
           $res = M('user') -> where(['wxname' => $number]) -> select();
            if($res){
                echo 0;
            }else{
                echo -1;
            }
        
        }
	}

}