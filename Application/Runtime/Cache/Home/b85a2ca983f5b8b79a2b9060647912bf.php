<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>提现</title>
		<link rel="stylesheet" type="text/css" href="/Public/css/default.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/css/font-awesome.min.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/css/qianbao.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/weui.new.css"/>
	</head>
	<body>
	
		<div class="wrap">
			<div class="top_bar top_bar3" style="background: #FF6766">
				<a onclick="history.go(-1)"><span class="left"><i class="icon-angle-left"></i></span></a>
				<div class="tit" style="font-size: 16px;">提现</div>				
			</div>
			<div class="tixian_bg bg">
				<div>可提现金额</div>				
				<span style="font-size: 25px;">¥</span>
				<span style="font-size: 40px;font-weight: bold;"><?php echo ($money); ?></span>
			</div>
			<div class="tx">提现方式</div>
			<div class="tx_weixin">				
				<img class="weixin_logo" src="/Public/img/weixinlogo.png"/>
				<span>微信支付</span>
				<img class="money_img" src="/Public/img/money.png"/>										
			</div>
			<div class="tx_jine">				
				<span style="font-size: 16px;">提现金额</span>
				<span class="input_wrap">
					<input type="" name="" id="money" value="" placeholder="最低1元提现"/>
				</span>				
				<span class="yuan">元</span>
			</div>
			<div style="width: 100%;text-align: center;line-height: 40px;margin-top:40px;">
				<button onclick="tixian()" style="text-align: center;font-size: 16px; background: #FF6766;width: 200px;height: 40px;border: 1px solid #FF6766;color:#fff;border-radius:4px;">提现</button>
			</div>
		</div>
	</body>
	<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
	<script src="/Public/admin/js/bootstrap.min.js"></script>
	<script src="/Public/layer/layer.js"></script>
<script>

	function tixian() {
		var money = $('#money').val();
		if(money == null || money < 1){
		    layer.msg('输入金额必须大于等于1元');
		    exit;
		}
		if(money > <?php echo ($money); ?>){
            layer.msg('请填写正确的金额');
		   exit;
		}
        pass(money);
	}

    function pass(money){
//        alert(money);
//        return;
        $.ajax({
            type: "POST",
            url: "<?php echo U('txzhifu');?>",
            dataType: "json",
            data: {"money":money},
            success: function(json) {
				if(json == 1){
				    layer.msg('提现成功');
				}else if(json==2){
                    layer.msg('提现失败');
				}else if(json==3){
				    layer.msg('提现数低于最低提现数');
				}else if(json==4){
				    layer.msg('账户余额不足');
				}
				if(json == -1){
                    layer.msg('后台审核中...');
                }
            }
        });
    }



</script>
</html>