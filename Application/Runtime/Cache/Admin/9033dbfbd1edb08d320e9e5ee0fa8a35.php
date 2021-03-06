<?php if (!defined('THINK_PATH')) exit();?>﻿ <!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="renderer" content="webkit">
  <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
 <title>跳转提示</title>
<link rel="stylesheet" href="/mlaj/Public/css/weui.min.css"/>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/mlaj/Public/admin/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="/mlaj/Public/admin/css/base.css">

<link rel="stylesheet" type="text/css" href="/mlaj/Public/admin/css/style.css" tppabs="css/style.css" />
<style>
body{height:100%;background:#16a085;overflow:hidden;}
canvas{z-index:-1;position:absolute;}
.system-message .jump{ padding-top: 10px}
.system-message .jump a{ color: #999;}
.system-message .success,.system-message .error{ line-height: 1.8em; font-size: 20px ;text-align: center;}
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
</style>
<script src="/mlaj/Public/admin/js/Particleground.js" tppabs="js/Particleground.js"></script>
<script src="/mlaj/Public/admin/layer/layer.js"></script>
<script>
$(document).ready(function() {
  //粒子背景特效
  $('body').particleground({
    dotColor: '#5cbdaa',
    lineColor: '#5cbdaa'
  });

  var wait = document.getElementById('wait');
var href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time == 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000)
});
</script>
</head>
<body>
<dl class="admin_login" style="background:#fff;">
 <div class="system-message">	
		<?php if(isset($message)): ?><div class="weui_icon_msg weui_icon_success" style="margin:6px auto;text-align:center;"></div>	
		<div class="success"><span><?php echo($message); ?></span></div>
		<?php else: ?>		
		<div class="weui_icon_msg weui_icon_warn" style="margin:10px auto;text-align:center;"></div>
		<div class="error"><span style="padding-top:0px;"><?php echo($error); ?></div><?php endif; ?>
<div class="jump" style="text-align:right;margin-top:20px;color:#999;">
页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
</div>
<div sytle="clear:both"></div>
</div>
</dl>
</body>
</html>