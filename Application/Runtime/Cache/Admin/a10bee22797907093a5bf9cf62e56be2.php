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
		<th>身份证号码</th>
		<th>电话</th>
		<th>地址</th>
		<th>上级姓名</th>
		<th>级别</th>
		<th>操作</th>
		<?php if(is_array($arr)): $kk = 0; $__LIST__ = $arr;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($kk % 2 );++$kk;?><tr id="<?php echo ($kk); ?>" style="font-size:13px;">
				<td><input type="checkbox" class="one_check" value="<?php echo ($vv["id"]); ?>"></td>
				<td><?php echo ($vv["id"]); ?></td>
				<td><?php echo ($vv["name"]); ?></td>
				<td><?php echo ($vv["card"]); ?></td>

				<td><?php echo ($vv["tel"]); ?></td>
				<td><?php echo ($vv["address"]); ?></td>
				<td><?php echo ($vv["sj_name"]); ?></td>
				<?php if($vv["type"] == 1): ?><td>总代</td>
					<?php elseif($vv["type"] == 2): ?>
					<td>特级</td>
					<?php elseif($vv["type"] == 3): ?>
					<td>一级</td><?php endif; ?>
				<td>
					<a href="<?php echo U('shType');?>?id=<?php echo ($vv["id"]); ?>&type=1"><button type="button" class="btn btn-warning " style = "background: #44B549">通过</button></a>
					<a href="<?php echo U('shType');?>?id=<?php echo ($vv["id"]); ?>&type=2"><button type="button" class="btn btn-warning " style = "background: #D84F4B">不通过</button></a>
				</td>
			</tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
</table>
<div class="pagelist"><?php echo ($page); ?></div>
<script>
</script>