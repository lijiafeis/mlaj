<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title></title>
		<link rel="stylesheet" type="text/css" href="/Public/css/default2.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/css/user-style2.css"/>
		<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<style>
.user-list-wrap {
	border-top: 1px #f0f0f0 solid;
	color: #333;
	display: block;
	padding:0 10px;
}

.user-list {
	height:40px;
	line-height:40px;
}

.l-info {
	vertical-align: middle;
	font-size: 14px;
	float:left;
	/*margin-top:2px;*/
}

.r-img {
	float: right;
	margin-top: -1px;
}

.r-img img {
	width: 20px;
	vertical-align: middle;
}
.footer_wrap{height: 30px;}
.footer_wrap li {
	float: left;
	width: 25%;
	text-align: center;
	line-height: 30px;
	font-size: 12px;
	background: rgba(0,0,0,0.5);
	border-right: 1px #fff solid;
	box-sizing: border-box;
}
.footer_wrap a {
	color: black;
	font-size: 12px;
}
/*body{background: url(/Public/img/bg.jpg)repeat 50% 50%;background-size: 100%;}
.wrap{background: url(/Public/img/bg.jpg)repeat 50% 50%;background-size: 100%;}*/
</style>
	</head>
	<body style="background:#fff;">
		<div class="wrap" style="background:#fff;">
			<!--<div class="top-info">
				<span class="good-luck">好运</span>
				<span>今日佣金达100以上的  有额外惊喜</span>
			</div>-->
			<!--<div class="user-id">会员ID： 100</div>-->
			<div class="user-info" style="background: url(/Public/img/tixian_bg.jpg) repeat;padding:20px 0;">				
				<div class="title">会员中心</div>
				<!--<div class="header-img"><img src="img/header.jpg"/></div>
				<div class="user-name">张三</div>-->
				<div class="user-id">会员ID： <?php echo ($arr["id"]); ?></div>
				<div class="yu-e">(余额:<?php echo ($arr["money"]); ?>元)</div>
			</div>
			
			<a class="user-list-wrap" href="<?php echo U('qr');?>" style="display:block;">
				<div class="user-list" >
					<span class="l-info">点击生成赚钱二维码</span>
					<span class="r-img"><img src="/Public/img/right-icon.png"/></span>
					<div class="clear"></div>
				</div>
			</a>
			<a class="user-list-wrap" href="<?php echo U('tuiguangjl');?>">
				<div class="user-list">					
					<span class="l-info">代理业绩</span>
					<span class="r-img"><img src="/Public/img/right-icon.png"/></span>
					<div class="clear"></div>
				</div>				
			</a>
			<a class="user-list-wrap" href="<?php echo U('youxijl');?>">
				<div class="user-list">					
					<span class="l-info">游戏记录</span>
					<span class="r-img"><img src="/Public/img/right-icon.png"/></span>
					<div class="clear"></div>
				</div>				
			</a>
			<a class="user-list-wrap" href="<?php echo U('yongjinjl');?>">
				<div class="user-list">					
					<span class="l-info">佣金记录</span>
					<span class="r-img"><img src="/Public/img/right-icon.png"/></span>
					<div class="clear"></div>
				</div>				
			</a>
			<a class="user-list-wrap" href="" >
				<div class="user-list">					
					<span class="l-info">佣金说明</span>
					<span class="r-img"><img src="/Public/img/right-icon.png"/></span>
					<div class="clear"></div>
				</div>				
			</a>
			<a class="user-list-wrap" href="<?php echo U(tixian);?>">
				<div class="user-list">					
					<span class="l-info">申请提现</span>
					<span class="r-img"><img src="/Public/img/right-icon.png"/></span>
					<div class="clear"></div>
				</div>				
			</a>
			<a class="user-list-wrap" href="<?php echo U('index');?>" style="border-bottom: 1px #f0f0f0 solid">
				<div class="user-list">					
					<span class="l-info">开始游戏</span>
					<span class="r-img"><img src="/Public/img/right-icon.png"/></span>
					<div class="clear"></div>
				</div>				
			</a>	
			<div class="footer_wrap" style="">
				<ul class="footer">	
					<a href="<?php echo U('index');?>">
					<li class="tab">						
						玩一玩
					</li>
					</a>
					<a href="<?php echo U('qr');?>">
					<li class="tab">						
						免费代理
					</li>
					</a>
					<a href="<?php echo U('tuiguangjl');?>">
					<li class="tab">						
						代理业绩
					</li>
					</a>
					<a href="#">
					<li class="tab" style="border-right: 0;">						
						个人中心
					</li>
					</a>
					<div class="clear"></div>
				</ul>				
			</div>
		</div>	
		<script src="/Public/js/layer/layer.js"></script>		
		<script>
		function pay(){
		alert(1);
		layer.prompt({title: '输入提现金额', formType: 1}, function(pass, index){
		  layer.close(index);
		  alert(2);
		});
		alert(3);
		}
		</script>
	</body>
</html>