<?php
namespace Wxapi\Controller;
use Think\Controller;

class ZhifuController extends Controller{
	function __construct(){
		parent::__construct();
		if(F('config_info','',DATA_ROOT)){
			$config_info = F('config_info','',DATA_ROOT);
		}else{
			$config_info = M('config') ->select();F('config_info',$config_info,DATA_ROOT);
		}
		$this->appid=$config_info[0]['appid'];
		$this->appsecret=$config_info[0]['appsecret'];
		$this->mkey=$config_info[0]['mkey'];
		$this->mch_id=$config_info[0]['machid'];
		$this->parter=$config_info[0]['parter'];
		$this->key=$config_info[0]['key'];
		//$this->parter = 1630;
	}
	//获取支付参数
	public function index($type,$fee,$dingdan,$url,$success_url,$attach){	
		$string1 = "parter=".$this->parter."&type=".$type."&value=".$fee."&orderid=".$dingdan."&callbackurl=".$url;
		$sign= MD5($string1.$this->key);	
		$url1 = "http://pay.admin523.cn/bank";
		$data = "parter=".$this->parter."&type=".$type."&value=".$fee."&orderid=".$dingdan."&callbackurl=".$url."&payerIp=127.0.0.1&attach=".$attach."&sign=".$sign;
		//最终url
		$url2= $url1."?".$data."&hrefbackurl=".$success_url;					
		//页面跳转
		header("location:" .$url2);
	}
}