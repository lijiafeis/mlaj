<?php if (!defined('THINK_PATH')) exit();?><style>.layui-layer{top:50px!important;}
.content{
	/*width: 500px;*/
	/*color: red;*/
	/*word-break:keep-all;!* 不换行 *!*/
	/*white-space: nowrap;*/
	text-overflow: ellipsis;
	overflow: hidden;

}
</style>
<table class="table table-striped"  style="font-size:14px;margin-top: 20px;">
	<th style="width: 5px;">
	<input type="checkbox" class="all_check">
	</th>
		<th>代理商id</th>
		<th>代理商</th>
		<th>订单号</th>
		<th>价格</th>
		<!--<th>状态</th>-->
		<!--<th>商品价格</th>-->
		<th>状态</th>
		<th>时间</th>
		<th>姓名</th>
		<!--<th>操作</th>-->
		<?php if(is_array($arr)): $kk = 0; $__LIST__ = $arr;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($kk % 2 );++$kk;?><tr id="<?php echo ($kk); ?>" style="font-size:13px;">
				<td><input type="checkbox" class="one_check" value="<?php echo ($vv["id"]); ?>"></td>
				<td><?php echo ($vv["dlid"]); ?></td>
				<?php if($vv["dlid"] == 0): ?><td>平台</td>
					<?php else: ?>
					<td><?php echo ($vv["dlname"]); ?></td><?php endif; ?>

				<td><?php echo ($vv["order_sn"]); ?></td>
				<td><?php echo ($vv["shopmoney"]); ?></td>
				<?php if($vv["type"] == 0): ?><td style="color: red">未发货</td>
					<?php elseif($vv["type"] == 1): ?>
					<td>已发货</td><?php endif; ?>
				<td><?php echo ($vv["dalitime"]); ?></td>
				<!--<td><?php echo (date("Y-m-d H:i:s",$vv["dalitime"])); ?></td>-->
				<td><?php echo ($vv["name"]); ?></td>
				<!--<td>-->
					<!--<a href="<?php echo U('xiangqing');?>?id=<?php echo ($vv["order_id"]); ?>"><button type="button" class="btn btn-warning " style = "background: #44B549">详情</button></a>-->
					<!--<button type="button" class="btn btn-warning" onclick="wuliu(<?php echo ($vv["order_id"]); ?>)" style = "background: orange">物流信息</button>-->
				<!--</td>-->
			</tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
</table>
<div class="pagelist"><?php echo ($page); ?></div>
<script>
    function wuliu(order_id) {
//        $.ajax({
//            type:'post',
//			url:"<?php echo U('isFH');?>",
//			dataType:'json',
//			data:{
//                order_id:order_id,
//			},
//			success:function (data) {
//				if(data == 0){
//				    layer.msg('该订单没有发货');
//				}else{
//                    location.href = "<?php echo U('showWuliu');?>?order_id=" + order_id;
//				}
//            }
//		});
		location.href = "<?php echo U('showWuliu');?>?order_id=" + order_id;
    }
    

    
</script>