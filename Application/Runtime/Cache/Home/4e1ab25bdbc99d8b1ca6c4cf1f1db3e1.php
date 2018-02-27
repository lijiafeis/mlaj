<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
	<meta content="telephone=no" name="format-detection">
    <title></title>
   <!--  <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="/Public/css/font-awesome.min.css">
    <link rel="stylesheet" href="/Public/css/weui.min.css"/>
    <link rel="stylesheet" href="/Public/css/style.css"/>
	<link rel="stylesheet" type="text/css" href="/Public/css/default1.css"/>
	<link rel="stylesheet" type="text/css" href="/Public/css/style1.css"/>
	<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
	<script src="/Public/js/base.js"></script>
	<link rel="stylesheet" href="/Public/css/slide.css">
	<style>
		.footer_wrap {
			max-width: 640px;
			min-width: 320px;
			margin: 0 auto;
			width: 100%;
			position: fixed;
			bottom: 0;
			z-index: 9999;
			height: 30px;
		}

		.footer_wrap li {
			float: left;
			width: 25%;
			text-align: center;
			height: 40px;
			list-style: none;
			line-height: 40px;
			font-size: 12px;
			background: #FEEDA8;
			border-right: 1px #fff solid;
			box-sizing: border-box;
		}

		.footer_wrap a {
			color: black;
			font-size: 12px;
		}
	</style>
	<style>
		.group-header{background:#FF6666;width:100%;padding-bottom:20px;}
		.group-header .group{width:33.3%;color:#fff;text-align:center;padding:20px 0 0 0;font-size:14px;}
		.group-header .weui_cell_bd span{font-size:14px;color:#fff}
		.weui_cell{font-size:14px;}
		.weui_cell:focus{background:#efedf1}
	</style>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
wx.config({
			debug: false,
			appId: '<?php echo $signPackage["appId"];?>',
			timestamp: <?php echo $signPackage["timestamp"];?>,
			nonceStr: '<?php echo $signPackage["nonceStr"];?>',
			signature: '<?php echo $signPackage["signature"];?>',
			jsApiList: [
				// 所有要调用的 API 都要加到这个列表中
				'checkJsApi',
				'addCard',
				'chooseCard',
				'openCard',
				'onMenuShareTimeline',
				'onMenuShareAppMessage',
				'closeWindow',
				'hideOptionMenu',
				'hideAllNonBaseMenuItem',
				'menuItem:profile'
			  ]
		});
wx.ready(function(){
	wx.hideAllNonBaseMenuItem();//alert('<?php echo $paysign["timeStamp"];?>');
});
</script>
</head>
<body  style="background: url("/Public/img/bg.jpg");margin-bottom:70px;color:#fff;">
	<img src="/Public/web_path/<?php echo ($user_id); ?>.jpg" width="100%">
	<p style="padding-left:5%;">您可以：</p>
	<section style="padding-left:15%;">
		<p>长按海报——识别——关注平台</p>
		<p>长按海报——保存至相册</p>
		<p></p>
	</section>
</body>
<script>

</script>
</html>