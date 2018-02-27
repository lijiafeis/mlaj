<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>佣金记录</title>
		<link rel="stylesheet" type="text/css" href="/Public/css/default.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/css/font-awesome.min.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/css/qianbao.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/web/css/bootstrap.min.css"/>
		
        <link rel="stylesheet" type="text/css" href="/Public/web/css/user-style.css"/>		
	</head>
	<body style="background: #fff;">
		<div class="wrap" style="background: #fff;">
			<div class="top_bar top_bar1">
				<span class="left" onclick="history.go(-1)"><i class="icon-angle-left" style="font-size: 25px;"></i></span>
				<div class="tit" style="font-size: 16px;">佣金记录(共:<?php echo ($money); ?>)</div>
				<a href="<?php echo U('tixian');?>"><span style="position:absolute;right:10px;top:13px;padding:3px 10px 1px;font-size:14px;background: #d9534f;color:#fff;border-radius:4px;">
				申请提现
				</span></a>
			</div>	
			<div class="table_wrap">
			<table border="" cellspacing="" cellpadding="">
				<?php if($data != null): ?><tr>
						<th>类别</th>
						<th class="lx">金钱</th>
						<th class="jy">时间</th>
					</tr><?php endif; ?>

				<?php if($data == null): ?><span>你还没有佣金记录，快让你的小伙伴扫你的二维码把！</span>
				<?php else: ?>
					<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><tr>
							<td>返佣</td>
							<td class="lx"><?php echo ($row['fee']); ?></td>
							<td class="jy" style="color: #2466D0;"><?php echo ($row['time']); ?></td>
						</tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>

			</table>
	<center><?php echo ($page); ?></center>			
			</div>
			<!--<div class="footer_wrap">				-->
				<!--<ul class="footer">	-->
					<!--<a href="<?php echo U('index');?>">-->
					<!--<li class="tab">						-->
						<!--玩一玩-->
					<!--</li>-->
					<!--</a>-->
					<!--<a href="<?php echo U('qr');?>">-->
					<!--<li class="tab">						-->
						<!--免费代理-->
					<!--</li>-->
					<!--</a>-->
					<!--<a href="<?php echo U('tuiguangjl');?>">-->
					<!--<li class="tab">						-->
						<!--代理业绩-->
					<!--</li>-->
					<!--</a>-->
					<!--<a href="<?php echo U('center');?>">-->
					<!--<li class="tab" style="border-right: 0;">						-->
						<!--个人中心-->
					<!--</li>-->
					<!--</a>-->
					<!--<div class="clear"></div>-->
				<!--</ul>				-->
			<!--</div>				-->
		</div>

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