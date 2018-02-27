<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/21 0021
 * Time: 18:05
 */
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller{

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

//        $yjdh = M('yjdh') -> find(1);
//        $gsjj = M('gsjj') -> find(1);
//        $kfzx = M('kfzx') -> find(1);
//        $yjbh = M('yjbh') -> find(1);
//        $this -> assign('yjdh',$yjdh);
//        $this -> assign('gsjj',$gsjj);
//        $this -> assign('kfzx',$kfzx);
//        $this -> assign('yjbh',$yjbh);
		$res = M('sck') ->  select();
        $res = $res[0];
		
		$yjbh = M('yjbh') -> select();
        $yjbh = $yjbh[0];
        $this -> assign('yjbh',$yjbh);
      
		
        $this -> assign('data',$res);
        $this -> display();
    }

    //一键拨号
    public function yjbh(){
        $yjbh = M('yjbh') -> select();
        $yjbh = $yjbh[0];
        $this -> assign('yjbh',$yjbh);
        $this -> display();
    }

    //客服咨询
    public function kfzx(){
        $kfzx = M('kfzx') -> select();
        $kfzx = $kfzx[0];
		//dump($kfzx);
		//exit;
       // $kfzx =
        $this -> assign('kfzx',$kfzx);
        $this -> display();
    }
	
	//公司简介
	public function gsjj(){
		$gsjj = M('gsjj') -> select();
		$gsjj = $gsjj[0];
        $this -> assign('gsjj',$gsjj);
        $this -> display();
	}

	public function cpjs(){
        $cpjs = M('cpjs') -> select();
        $cpjs = $cpjs[0];
//        dump($cpjs);
        $this -> assign('cpjs',$cpjs);
        $this -> display();
    }

    //加入我们
    public function jrwm(){
        $jrwm = M('jrwm') -> select();
        $jrwm = $jrwm[0];
//        dump($cpjs);
        $this -> assign('jrwm',$jrwm);
        $this -> display();
    }

    public function sck(){
        $res = M('sck') ->  select();
        $res = $res[0];
        $this -> assign('data',$res);
        $this -> display();
    }

	//商品展示
    public function showShop(){
	    $data = M('shop') -> select();
//	    $sizeList = explode(',',$data['size_id']);
//        $ress = array();
	    foreach ($data as $k => $v){
            $sizeList = explode(',',$data[$k]['size_id']);
            foreach ($sizeList as $key => $val){
                $info = M('size') -> find($val);
                $data[$k]['size'. $key] = $info['name'];
            }
//	        $data[]
        }
//        dump($data);
        $this -> assign('data',$data);
        $this -> assign('isLogin',1);
//        $this -> assign('ress',$ress);
	    $this -> display();
    }

    //得到商品id
    public function getShopId(){
        $data = M('shop') -> field('id') -> select();
        $this -> AjaxReturn($data);
    }





}