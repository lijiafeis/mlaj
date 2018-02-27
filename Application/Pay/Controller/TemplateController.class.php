<?php
namespace Pay\Controller;
use Think\Controller;

class TemplateController extends Controller{
	function __construct(){
		parent::__construct();
		$this->template_info = F('template_info','',DATA_ROOT);
	}
	/* 发送订单追踪相关通知 */
//$template->send_shop($order_sn,$address_info['name'],$address_info['tel'],$address_info['address'],$openid,$url);
    function send_shop($kd_name,$order_sn,$name,$tel,$address,$openid,$url,$address){
		$tem_data = '{
		   "touser":"'.$openid.'",
		   "template_id":"h_8qUsFhvkYCefdL8NQZjYYubg2oCmIIcsVTgbJn8uM",
		   "url":"'.$url.'",            
		   "data":{
			       "keyword1": {
					   "value":"'.$kd_name.'",
					   "color":""
				   },
				   "keyword2": {
					   "value":"'.$order_sn.'",
					   "color":""
				   },
				   "keyword3": {
					   "value":"'.$name.'",
					   "color":""
				   },
				   "keyword4": {
					   "value":"'.$tel.'",
					   "color":""
				   },
				   "keyword5": {
					   "value":"'.$address.'",
					   "color":""
				   },
				   "remark":{
					   "value":"时间：'.date("Y-m-d H:i:s",time()).'",
					   "color":""
				   }
		   }
	   }';
	   $weixin = A("Wxapi/Weixin");
	   $a = $weixin ->send_template($openid,$tem_data);
		file_put_contents('345a.txt',$a);
	}

	/* 新订单 */
	function send_order($order_sn,$name,$money,$url,$openid,$address,$tel){
		$tem_data = '{
		   "touser":"'.$openid.'",
		   "template_id":"trD2sQA4WAhVBiM-NNilsmBrzTnZ8ra-3uN64PYohco",
		   "url":"'.$url.'",         
		   "data":{
				  "keyword1": {
					   "value":"'.$address.'",
					   "color":""
				   },
				   "keyword2": {
					   "value":"'.$name.'",
					   "color":""
				   },
				   "keyword3": {
					   "value":"'.$tel.'",
					   "color":""
				   },
				   "remark":{
					   "value":"时间：'.date("Y-m-d H:i:s",time()).'",
					   "color":""
				   }
		   }
	   }';
	   $weixin = A("Wxapi/Weixin");
	   $weixin ->send_template($openid,$tem_data);
	}

    /* 会员注册审核通过模板消息 */
    function send_shenHe($user_id,$openid,$url){
        $tem_data = '{
		   "touser":"'.$openid.'",
		   "template_id":"Aek-ZF4hVjbwyDPnSO_51kv5Y6d6KMzCrMms18XWIlU",
		   "url":"'.$url.'",         
		   "data":{
				  "keyword1": {
					   "value":"'.$user_id.'",
					   "color":""
				   },
				   "keyword2": {
					   "value":"时间：'.date("Y-m-d H:i:s",time()).'",
					   "color":""
				   }
				
		   }
	   }';
        $weixin = A("Wxapi/Weixin");
        $a = $weixin ->send_template($openid,$tem_data);
		file_put_contents('a.txt',$a);
	}

	/**
     * 上级商品库存不足模板消息
     * 商品编码：{{keyword1.DATA}}
    商品名称：{{keyword2.DATA}}
    库存数量：{{keyword3.DATA}}
     */
	public function send_shopKucun($kucun,$shop_name,$openid,$url){
        $tem_data = '{
		   "touser":"'.$openid.'",
		   "template_id":"juvYJbxcymzURnp4kJ1-N0T5y1YwNMVxaVpUmEQC8cs",
		   "url":"'.$url.'",
		   "data":{
				   "first": {
					   "value":"你的商品库存不足，请及时补充库存",
					   "color":""
				   },
				   "keyword1":{
					   "value":"'.time().'",
					   "color":""
				   },
				   "keyword2": {
					   "value":"'.$shop_name.'",
					   "color":""
				   },
				   "keyword3": {
					   "value":"'.$kucun.'",
					   "color":""
				   },
				   "remark":{
					   "value":"时间：'.date("Y-m-d H:i:s",time()).'",
					   "color":""
				   }
		   }
	   }';
        $weixin = A("Wxapi/Weixin");
        $weixin ->send_template($openid,$tem_data);
    }


    /**
     * 上级扣除推荐奖余额不足。
     * {{first.DATA}}
    账号：{{keyword1.DATA}}
    当前余额：{{keyword2.DATA}}
    {{remark.DATA}}
     */
    public function sendTemplateMoney($name,$money,$openid,$url){
        $tem_data = '{
		   "touser":"'.$openid.'",
		   "template_id":"FkOkdkY0wUSUgy4Rfb8krgD9hPIV09CvZZiBJkakJos",
		   "url":"'.$url.'",
		   "data":{
				   "first": {
					   "value":"下级'.$name.'购买商品，推荐奖无法发放，请充值积分",
					   "color":""
				   },
				   "keyword1":{
					   "value":"当前账号",
					   "color":""
				   },
				   "keyword2": {
					   "value":"'.$money.'",
					   "color":""
				   },
				   "remark":{
					   "value":"时间：'.date("Y-m-d H:i:s",time()).'",
					   "color":""
				   }
		   }
	   }';
        $weixin = A("Wxapi/Weixin");
        $weixin ->send_template($openid,$tem_data);
    }

    //直推的人得到推荐奖信息
    function send_money($money,$info,$openid,$url){
        $tem_data = '{
		   "touser":"'.$openid.'",
		   "template_id":"2OgBpMtdmddARs9mvn0W8hexkKsyxdINMFDWW674SPw",
		   "url":"'.$url.'",         
		   "data":{
				  "keyword1": {
					   "value":"'.$money.'元",
					   "color":""
				   },
				   "keyword2": {
					   "value":"'.date("Y-m-d H:i:s",time()).'",
					   "color":""
				   },
				   "keyword3": {
					   "value":"'.$info.'",
					   "color":""
				   },
				   "remark":{
					   "value":"时间：'.date("Y-m-d H:i:s",time()).'",
					   "color":""
				   }
		   }
	   }';
        $weixin = A("Wxapi/Weixin");
        $a = $weixin ->send_template($openid,$tem_data);
//        file_put_contents('a.txt',$a);
    }

    //会员升级提醒
    //直推的人得到推荐奖信息
    function send_shengji($name,$type1,$type,$openid,$url){
        $tem_data = '{
		   "touser":"'.$openid.'",
		   "template_id":"nPzGoMamrxmY3j2h9WPXpBRU0ywpJXdO870lX90wM2Q",
		   "url":"'.$url.'",         
		   "data":{
				  "keyword1": {
					   "value":"'.$name.'",
					   "color":""
				   },
				   "keyword2": {
					   "value":"'.$type1.'",
					   "color":""
				   },
				   "keyword3": {
					   "value":"'.$type.'",
					   "color":""
				   },
				    "keyword4": {
					   "value":"'.date("Y-m-d H:i:s",time()).'",
					   "color":""
				   },
				   "remark":{
					   "value":"时间：'.date("Y-m-d H:i:s",time()).'",
					   "color":""
				   }
		   }
	   }';
        $weixin = A("Wxapi/Weixin");
        $a = $weixin ->send_template($openid,$tem_data);
        file_put_contents('a.txt',$a);
    }

    //积分到账提醒
    public function send_jifen($name,$jifen,$zjifen,$openid,$url){
        $tem_data = '{
		   "touser":"'.$openid.'",
		   "template_id":"8lRoJnpjfi4huR2A9Y944dj8Puwx39RC9cAlSTCJSkY",
		   "url":"'.$url.'",
		   "data":{
				   "first": {
					   "value":"你有积分已到账",
					   "color":""
				   },
				   "keyword1":{
					   "value":"'.$name.'",
					   "color":""
				   },
				   "keyword2": {
					   "value":"'.date("Y-m-d H:i:s",time()).'",
					   "color":""
				   },
				   "keyword3": {
					   "value":"充值积分",
					   "color":""
				   },
				    "keyword4": {
					   "value":"'.$jifen.'",
					   "color":""
				   },
				  
				   "remark":{
					   "value":"时间：'.date("Y-m-d H:i:s",time()).'",
					   "color":""
				   }
		   }
	   }';
        $weixin = A("Wxapi/Weixin");
        $weixin ->send_template($openid,$tem_data);
    }

    //提现金额到账提醒
    public function send_tixian($money,$info,$openid,$url){
        $tem_data = '{
		   "touser":"'.$openid.'",
		   "template_id":"CX6O807rYVh05PTL082O8MUljEB-yIZyrA2gqo7xkQ4",
		   "url":"'.$url.'",         
		   "data":{
		           "first": {
					   "value":"你提现的金额已经到账,请查收",
					   "color":""
				   },
				  "keyword1": {
					   "value":"'.$money.'元",
					   "color":""
				   },
				   "keyword2": {
					   "value":"'.date("Y-m-d H:i:s",time()).'",
					   "color":""
				   },
				   "keyword3": {
					   "value":"你提现金额已到账，请查收",
					   "color":""
				   },
				   "remark":{
					   "value":"时间：'.date("Y-m-d H:i:s",time()).'",
					   "color":""
				   }
		   }
	   }';
        $weixin = A("Wxapi/Weixin");
        $a = $weixin ->send_template($openid,$tem_data);

    }
	
	/**
	*购买商品的时候，扣除上级的钱，给上级扣费通知
	*/
    public function send_sjKouFei($money,$info,$openid,$url){
        $tem_data = '{
		   "touser":"'.$openid.'",
		   "template_id":"b_CUV1gWxepyedNW9GKwopQXQITvsUuehoS3NXOpNGk",
		   "url":"'.$url.'",         
		   "data":{
		           
				  "keyword1": {
					   "value":"'.$money.'元",
					   "color":""
				   },
				   "keyword2": {
					   "value":"'.$info.'",
					   "color":""
				   },
				   "remark":{
					   "value":"时间：'.date("Y-m-d H:i:s",time()).'",
					   "color":""
				   }
		   }
	   }';
        $weixin = A("Wxapi/Weixin");
        $a = $weixin ->send_template($openid,$tem_data);

    }
	
	
	/**
     * 上级转移商品给下级
     * 商品编码：{{keyword1.DATA}}
    商品名称：{{keyword2.DATA}}
    库存数量：{{keyword3.DATA}}
     */
	public function send_sjToXj($kucun,$shop_name,$openid,$url){
        $tem_data = '{
		   "touser":"'.$openid.'",
		   "template_id":"rqe1zup7eHhuxoKtGloj-xDEi4G-zFNdlWxNheDCYzM",
		   "url":"'.$url.'",
		   "data":{
				   "first": {
					   "value":"上级转移商品,库存增加",
					   "color":""
				   },
				   "keyword1":{
					   "value":"'.time().'",
					   "color":""
				   },
				   "keyword2": {
					   "value":"'.$shop_name.'",
					   "color":""
				   },
				   "keyword3": {
					   "value":"'.$kucun.'",
					   "color":""
				   },
				   "remark":{
					   "value":"时间：'.date("Y-m-d H:i:s",time()).'",
					   "color":""
				   }
		   }
	   }';
        $weixin = A("Wxapi/Weixin");
        $weixin ->send_template($openid,$tem_data);
    }

}