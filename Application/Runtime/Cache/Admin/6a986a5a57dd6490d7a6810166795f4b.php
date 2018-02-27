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
		<th>佣金</th>
		<th>类别</th>
		<th>时间</th>
		<?php if(is_array($arr)): $kk = 0; $__LIST__ = $arr;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($kk % 2 );++$kk;?><tr id="<?php echo ($kk); ?>" style="font-size:13px;">
				<td><input type="checkbox" class="one_check" value="<?php echo ($vv["id"]); ?>"></td>
				<td><?php echo ($vv["user_id"]); ?></td>
				<td><?php echo ($vv["name"]); ?></td>
				<td><?php echo ($vv["tel"]); ?></td>
				<?php if($vv["type"] == 1): ?><td>总代</td>
					<?php elseif($vv["type"] == 2): ?>
					<td>特级</td>
					<?php elseif($vv["type"] == 3): ?>
					<td>一级</td><?php endif; ?>
				<td><?php echo ($vv["fy_money"]); ?></td>
				<?php if($vv["atype"] == 0): ?><td>推荐奖</td>
					<?php elseif($vv["atype"] == 1): ?>
					<td>差价</td>
					<?php elseif($vv["atype"] == 2): ?>
					<td>返利</td><?php endif; ?>
				<td><?php echo (date("Y-m-d H:i:s",$vv["jftime"])); ?></td>
			</tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
</table>
<input type="hidden" name="bili" id="bili" value="<?php echo ($bili); ?>"/>
<div class="pagelist"><?php echo ($page); ?></div>
<script src="/Public/Admin/layer/layer.js"></script>
<script src="/Public/Admin/js/jquery.js"></script>
<script>
	var openid = '';
    function chongzhijifen(money,user_id,cz_id) {
        bili = $("#bili").val();
        openid = layer.open({
            type: 0,
            skin: '', //加上边框
            area: ['300px', '300px'], //宽高
            content: '<div class="zzsc-container" style="margin-left: 0px;"><div class="container">' +
			'<div class="row"><div class="col-md-6 content" ><p>按照设定的比例，需要充值的积分是'+money * bili + '</p>' +
			'<p>你可以输入你想充值的积分</p>' +
			'<input style="border-radius: 4px;border: 1px solid black;" onclick="setDate()" id="jifen" type="text" value="' + money * bili + '"/>' +
			'<div class="" style="margin-top: 5px;">' +
			'<button style="background:#44B549;" type="submit" onclick="chongzhi('+user_id+','+cz_id+')" class="btn btn-default btn-default1" >充值</button>' +
			'</div></div></div></div></div>'
        });
    }

    //异步请求充值积分
    function chongzhi(user_id,cz_id) {
        jifen = $("#jifen").val();
		$.ajax({
			type:'post',
			url:"<?php echo U('chongzhi');?>",
			dataType:'json',
			data:{
				jifen:jifen,
				user_id:user_id,
				cz_id:cz_id
			},
			success:function (data) {
				if(data == 0){
					layer.close(openid);

					layer.msg('充值成功');
					setTimeout(function () {
                        history.go(0);
                    },1000);
				}else{
				    layer.close(openid);
				    layer.msg('充值失败');
				}
            }

		});
    }
    
</script>