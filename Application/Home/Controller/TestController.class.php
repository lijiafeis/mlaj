<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/31 0031
 * Time: 17:59
 */
namespace Home\Controller;
use Think\Controller;

class TestController extends Controller{
    public function test(){
        dump(strtotime('today'));
        dump(strtotime('yesterday'));
        dump(strtotime('yesterday') - 86400);
    }
}