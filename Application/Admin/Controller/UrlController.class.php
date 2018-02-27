<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/26 0026
 * Time: 18:07
 */
namespace Admin\Controller;
use Think\Controller;

class UrlController extends Controller{
    public function lianjie(){
        $url = "http://" . $_SERVER['SERVER_NAME'];
        $this -> assign('url',$url);
        $this -> display();
    }
}