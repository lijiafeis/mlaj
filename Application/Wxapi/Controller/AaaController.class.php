<?php
namespace Wxapi\Controller;
use Think\Controller;

class AaaController extends Controller{

function index(){
	$a=A("weixin/get_access_token");
	echo $a;
}	
}	