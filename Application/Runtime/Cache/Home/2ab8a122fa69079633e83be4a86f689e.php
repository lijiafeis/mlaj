<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>推广记录</title>
		<link rel="stylesheet" type="text/css" href="/Public/css/default.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/css/font-awesome.min.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/css/qianbao.css"/>	
		
		<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css"/>
		
        <link rel="stylesheet" type="text/css" href="/Public/css/user-style2.css"/>

		<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
		<script src="/Public/layer-mobile/layer.js"></script>
		<script src="/Public/admin/js/bootstrap.min.js"></script>		
	</head>
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
			height: 30px;
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
	</style>
	<body style="background: #fff;">
		<div class="wrap" style="background: #fff;">
			<div class="top_bar top_bar1" style="background: url(/Public/img/bg.jpg);position:relative;">
				<span class="left" onclick="history.go(-1)"><i class="icon-angle-left" style="font-size: 25px;"></i></span>
				<div class="tit" style="font-size: 16px;text-align: center;color:#333;">推广记录 </div>
				<a href="<?php echo U('tixian');?>">
				<span style="position:absolute;right:10px;top:13px;padding:3px 10px 1px;font-size:14px;background: #d9534f;color:#fff;border-radius:4px;">
				申请提现
				</span>
				</a>
				<div class="clear"></div>
			</div>
			<div class="table_wrap">
			<table border="" cellspacing="" cellpadding="">
				<tr>
					<th>第1级</th>
					<th class="jy">id</th>
				</tr>
				<?php if(is_array($one)): $i = 0; $__LIST__ = $one;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><tr>
						<td>第1级</td>
						<td class="jy"><?php echo ($row['user_id']); ?></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				<tr>
					<th>第2级</th>
					<th class="jy">id</th>
				</tr>
				<?php if(is_array($two)): $i = 0; $__LIST__ = $two;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><tr>
						<td>第2级</td>
						<td class="jy"><?php echo ($row['user_id']); ?></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				<tr>
					<th>第3级</th>
					<th class="jy">id</th>
				</tr>
				<?php if(is_array($three)): $i = 0; $__LIST__ = $three;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><tr>
						<td>第3级</td>
						<td class="jy"><?php echo ($row['user_id']); ?></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				<tr>
					<th>第4级</th>
					<th class="jy">id</th>
				</tr>
				<?php if(is_array($four)): $i = 0; $__LIST__ = $four;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><tr>
						<td>第4级</td>
						<td class="jy"><?php echo ($row['user_id']); ?></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				<tr>
					<th>第5级</th>
					<th class="jy">id</th>
				</tr>
				<?php if(is_array($five)): $i = 0; $__LIST__ = $five;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><tr>
						<td>第5级</td>
						<td class="jy"><?php echo ($row['user_id']); ?></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				<tr>
					<th>第6级</th>
					<th class="jy">id</th>
				</tr>
				<?php if(is_array($six)): $i = 0; $__LIST__ = $six;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><tr>
						<td>第6级</td>
						<td class="jy"><?php echo ($row['user_id']); ?></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>


				<!--<tr>-->
					<!--<th>期数</th>-->
					<!--<th class="lx">点数</th>-->
					<!--<th class="jy">结果</th>-->
				<!--</tr>-->
				<!--<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?>-->
					<!--<tr>-->
						<!--<td>第<?php echo ($row['kj_id']); ?>期</td>-->
						<!--<td class="lx"><?php echo ($row['kj_num']); ?></td>-->
						<!--<?php if($row['kj_dx'] == 1): ?>-->
							<!--<td class="jy" style="color: #2466D0;"><span style="color: red;">大</span></td>-->
						<!--<?php else: ?>-->
							<!--<td class="jy" style="color: #2466D0;"><span style="color: red;">小</span></td>-->
						<!--<?php endif; ?>-->
					<!--</tr>-->
				<!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
			</table>
			</div>
			<div class="footer_wrap">
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
					<a href="<?php echo U('center');?>">
					<li class="tab" style="border-right: 0;">						
						个人中心
					</li>
					</a>
					<div class="clear"></div>
				</ul>				
			</div>			
		</div>
	<center><?php echo ($page); ?></center>
	</body>
<script src="/Public/js/jquery-1.8.3.min.js"></script>
<script>
//	getpage(1);
//	function getpage(p){
//		$.ajax({
//		    type:"get",
//			url:"<?php echo U('history');?>" + "?p=" + p,
//			dataType:'json',
//			success:function (data) {
//				console.log(data);
//            }
//
//		});
//	}

</script>
</html>