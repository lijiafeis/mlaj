<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>历史记录</title>
		<link rel="stylesheet" type="text/css" href="/Public/css/default.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/css/font-awesome.min.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/css/qianbao.css"/>		
	</head>
	<body style="background: #fff;">
		<div class="wrap">
			<div class="top_bar top_bar1">
				<span class="left"><i class="icon-angle-left" style="font-size: 25px;"></i></span>
				<div class="tit" style="font-size: 16px;">历史记录</div>				
			</div>	
			<div class="table_wrap">
			<table border="" cellspacing="" cellpadding="">
				<tr>
					<th>期数</th>
					<th class="lx">钱数</th>
					<th class="jy">结果</th>
				</tr>
				<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><tr>
						<td>第<?php echo ($row['kj_id']); ?>期</td>
						<td class="lx"><?php echo ($row['fee']); ?></td>
						<?php if($row['state'] == 1): if($row['iszhong'] == 1): ?><td class="jy" style="color: #2466D0;"><span style="color: red;">中奖</span></td>
						<?php else: ?>
							<td class="jy" style="color: #2466D0;"><span style="color: green;">未中奖</span></td><?php endif; ?>
					    <?php else: ?>
						<td class="jy" style="color: #2466D0;"><span style="color: green;">无效</span></td><?php endif; ?>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</table>
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