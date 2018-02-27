<?php
namespace Home\Controller;
use Think\Controller;

class NoticeController extends Controller{
    //
    public function buy_yz(){
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $id = trim($postObj->attach); //当前用户押注存入数据库的id号；
        $order_sn = trim($postObj->out_trade_no); //订单号
        $total_fee = trim($postObj->cash_fee)/100; //金额
        $model =M('chongzhi');
        $model->where("id = '$id'")->setField('state',1);
        $model->where("id = '$id'")->setField('order_sn',$order_sn);

        echo '<xml>
		   <return_code><![CDATA[SUCCESS]]></return_code>
		   <return_msg><![CDATA[OK]]></return_msg>
		</xml>';
        exit;
   }
}