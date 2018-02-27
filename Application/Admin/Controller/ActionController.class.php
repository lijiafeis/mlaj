<?php
namespace Admin\Controller;
use Think\Controller;

class ActionController extends Controller{
	function __construct(){
		parent::__construct();
		// if(!file_exists('data/install.lock')){
		// 	$install_url = 'http://'.$_SERVER['SERVER_NAME'].'/install';
		// 	redirect($install_url);exit;
		// }
		
		// if($_GET['xigua']){dump($data);}
		// $res = $this -> http_request($url,$data);
		// $result = json_decode($res, true);
		// $admin = D('admin');
		// $admin->check_admin();
	}
	
	function GetIP(){
		if('/'==DIRECTORY_SEPARATOR){
			$server_ip=$_SERVER['SERVER_ADDR'];
		}else{
			$server_ip=@gethostbyname($_SERVER['SERVER_NAME']);
		}
		return $server_ip;
	}
   
	//https请求(支持GET和POST)
	 function http_request($url,$data = null){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if(!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		//var_dump(curl_error($curl));
		curl_close($curl);
		return $output;
	}
	
    

}

 ?>