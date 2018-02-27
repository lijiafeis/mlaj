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
		<th>user_id</th>
		<th>姓名</th>
		<th>电话</th>
		<th>级别</th>
		<th>金额</th>
		<th>开户行</th>
		<th>银行卡号</th>
		<th>支付宝账号</th>
		<th>打款方式</th>
		<th>操作</th>
		<?php if(is_array($arr)): $kk = 0; $__LIST__ = $arr;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($kk % 2 );++$kk;?><tr id="<?php echo ($kk); ?>" style="font-size:13px;">
				<td><input type="checkbox" class="one_check" value="<?php echo ($vv["id"]); ?>"></td>
				<td><?php echo ($vv["uid"]); ?></td>
				<td><?php echo ($vv["user_name"]); ?></td>
				<td><?php echo ($vv["banktel"]); ?></td>
				<?php if($vv["type"] == 1): ?><td>总代</td>
					<?php elseif($vv["type"] == 2): ?>
					<td>特级</td>
					<?php elseif($vv["type"] == 3): ?>
					<td>一级</td><?php endif; ?>
				<td><?php echo ($vv["txmoney"]); ?></td>
				<?php if($vv["type"] == 0): ?><td><?php echo ($vv["bank_name"]); ?></td>
					<td><?php echo ($vv["bank_number"]); ?></td>
					<td></td>
					<td>银行卡</td>
					<?php elseif($vv["type"] == 1): ?>
					<td></td>
					<td></td>
					<td><?php echo ($vv["alipay_number"]); ?></td>
					<td>支付宝</td><?php endif; ?>
				<td>
					<button type="button" onclick="setType(<?php echo ($vv["aid"]); ?>)" class="btn btn-warning " style = "background: #44B549">成功</button>
					<!--<button type="button" class="btn btn-warning" onclick="del(<?php echo ($vv["id"]); ?>)" style = "background: #D84F4B">删除</button>-->
				</td>
			</tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
</table>
<input type="hidden" name="bili" id="bili" value="<?php echo ($bili); ?>"/>
<div class="pagelist"><?php echo ($page); ?></div>
<script src="/Public/Admin/layer/layer.js"></script>
<script src="/Public/Admin/js/jquery.js"></script>
<script>
	function setType(tx_id) {
	    $.ajax({
	       type:"post",
			url:"<?php echo U('setType');?>",
			dataType:"json",
			data:{
	           tx_id:tx_id
			},
			success:function (data) {
				if(data == 0){
				    layer.msg('修改状态成功');
				    setTimeout(function () {
						history.go(0);
                    },1000);
				}else{
				    layer.msg('修改状态失败');
				}
            }
		});
    }

</script>