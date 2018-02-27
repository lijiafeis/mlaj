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
		<th>标题</th>
		<th>数量</th>
		<th>时间</th>
		<th>总代价格</th>
		<th>特级价格</th>
		<th>一级价格</th>
		<th>图片</th>
		<th>尺寸</th>
		<th>规格</th>
		<th style="text-align: center;">操作</th>
		<?php if(is_array($arr)): $kk = 0; $__LIST__ = $arr;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($kk % 2 );++$kk;?><tr id="<?php echo ($kk); ?>" style="font-size:13px;">
				<td><input type="checkbox" class="one_check" value="<?php echo ($vv["id"]); ?>"></td>
				<td class="content"><?php echo ($vv["title"]); ?></td>
				<td><?php echo ($vv["number"]); ?></td>
				<td><?php echo ($vv["time"]); ?></td>
				<td><?php echo ($vv["fee1"]); ?></td>
				<td><?php echo ($vv["fee2"]); ?></td>
				<td><?php echo ($vv["fee3"]); ?></td>
				<td><img width="90px" height="100px" src="<?php echo ($vv["img_url"]); ?>"/></td>
				<td><?php echo ($vv["size"]); ?></td>
				<td><?php echo ($vv["guige"]); ?></td>
				<td style="text-align: center;">
					<a href="<?php echo U('update');?>?id=<?php echo ($vv["id"]); ?>"><button type="button" class="btn btn-warning " style = "background: #44B549">修改</button></a>
					<button type="button" class="btn btn-warning" onclick="del(<?php echo ($vv["id"]); ?>)" style = "background: #D84F4B">删除</button>
				</td>
			</tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
</table>
<div class="pagelist"><?php echo ($page); ?></div>
<script>
    function del(id){
        layer.confirm('删除后无法恢复，确认删除吗', {
            btn: ['确认','取消'] //按钮
        }, function(){
			$.ajax({
			    type:'post',
				url:"<?php echo U(delete);?>",
				dataType:"json",
				data:{
			        id:id
				},
				success:function (data) {
					if(data == 0){
					    layer.msg('删除成功');
					    history.go(0);
					}else{
					    layer.msg('删除失败');
					}
                }
			});
        }, function(){

        });
    }
    
    function setURL(type) {
		layer.confirm('确定生成链接吗？', {
			btn: ['确认','取消'] //按钮
		}, function(){
			var idlist = '';
			$('body .one_check').each(function(){
				if($(this).is(':checked')){
                    idlist = idlist+$(this).val()+','
				}
			})
			if(idlist == ''){layer.closeAll();layer.msg('未选中任何试题');exit;}
//			$.post("<?php echo U('delhy');?>",{del_id,del_id},function(){
//
//				layer.msg("删除成功");
//				$('body .one_check').each(function(){
//					if($(this).is(':checked')){
//						$(this).parent().parent().css("display","none");
//					}
//				})
//				// var td = $(obj).parent();//alert(a);
//				// td.parent().css("display","none");
//			});
			location.href="<?php echo U('setURLForTest');?>?type=" + type + "&idlist=" + idlist;
//			$.ajax({
//				type:'post',
//				url:"<?php echo U('setURLForTest');?>",
//				dataType:'json',
//				data:{
//				    idlist:idlist,
//				},
//				success:function (data) {
//					console.log(data);
//                }
//			});
		}, function(){

		});

    }
    
</script>