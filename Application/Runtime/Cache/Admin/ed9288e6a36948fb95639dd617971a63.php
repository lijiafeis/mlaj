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
		<th>微信号</th>
		<th>电话</th>
		<th>地址</th>
		<th>身份证号码</th>
		<th>上级姓名</th>
		<th>积分</th>
		<th>佣金</th>
		<!--<th>业绩</th>-->
		<th>级别</th>
		<!--<th>操作</th>-->
		<?php if(is_array($arr)): $kk = 0; $__LIST__ = $arr;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($kk % 2 );++$kk;?><tr id="<?php echo ($kk); ?>" style="font-size:13px;">
				<td><input type="checkbox" class="one_check" value="<?php echo ($vv["id"]); ?>"></td>
				<td><?php echo ($vv["id"]); ?></td>
				<td><?php echo ($vv["name"]); ?></td>
				<td><?php echo ($vv["wxname"]); ?></td>
				<td><?php echo ($vv["tel"]); ?></td>
				<td><?php echo ($vv["address"]); ?></td>
				<td><?php echo ($vv["card"]); ?></td>
				<td><?php echo ($vv["sj_name"]); ?></td>
				<td><?php echo ($vv["jifen"]); ?></td>
				<td><?php echo ($vv["money"]); ?></td>
				<!--<button onclick="daoru(<?php echo ($vv["id"]); ?>)" style="margin-left: 10px;border: 0px;border-radius: 3px;background: #44B549;">导入之前业绩</button>-->
				<!--<td><?php echo ($vv["yeji"]); ?></td>-->
				<?php if($vv["type"] == 1): ?><td>总代</td>
					<?php elseif($vv["type"] == 2): ?>
					<td>特级</td>
					<?php elseif($vv["type"] == 3): ?>
					<td>一级</td><?php endif; ?>
				<!--<?php if($vv["type"] == 1): ?>-->
					<!--<td>-->
						<!--&lt;!&ndash;<a href="<?php echo U('update');?>?id=<?php echo ($vv["id"]); ?>"><button type="button" class="btn btn-warning " style = "background: #44B549">修改</button></a>&ndash;&gt;-->
						<!--<button type="button" class="btn btn-warning" onclick="fanyong(<?php echo ($vv["id"]); ?>)" style = "background: #44B549">返佣</button>-->
						<!--<button type="button" class="btn btn-warning" onclick="clearFee(<?php echo ($vv["id"]); ?>)" style = "background: #44B549">清除佣金</button>-->
					<!--</td>-->
					<!--<?php else: ?>-->
					<!--<td>  </td>-->
				<!--<?php endif; ?>-->

			</tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
</table>
<div class="pagelist"><?php echo ($page); ?></div>
<script src="/Public/Admin/layer/layer.js"></script>
<script>
    openid = '';
    function fanyong(user_id) {
        openid = layer.open({
            type: 1,
            skin: '', //加上边框
            area: ['300px', '200px'], //宽高
            content: '<div  style="margin-left: 0px;"><div >' +
            '<div style="text-align: center;margin-top: 5px;font-size: 15px;"><div><p>输入返佣金额</p>' +
            '<input style="border-radius: 4px;border: 1px solid #D9DADC;"  placeholder="元" id="money" type="text" />' +
            '<div class="" style="margin-top: 5px;">' +
            '<button style="background:#44B549;" type="submit"  class="btn btn-default btn-default1" onclick="fymoney('+user_id+')">确定</button>' +
            '</div></div></div></div></div>'
        });
    }
    var open = 0;
    function daoru(user_id) {
        open = layer.open({
            type: 1,
            skin: '', //加上边框
            area: ['300px', '200px'], //宽高
            content: '<div  style="margin-left: 0px;"><div >' +
            '<div style="text-align: center;margin-top: 5px;font-size: 15px;"><div><p>输入之前业绩</p>' +
            '<input style="border-radius: 4px;border: 1px solid #D9DADC;"  placeholder="" id="zqjf" type="text" />' +
            '<div class="" style="margin-top: 5px;">' +
            '<button style="background:#44B549;" type="submit"  class="btn btn-default btn-default1" onclick="daorujifen(' + user_id + ')">确定</button>' +
            '</div></div></div></div></div>'
        });
    }
    
    function daorujifen(user_id) {
		jifen = $("#zqjf").val();
        $.ajax({
            type:'post',
            url:"<?php echo U('daoru');?>",
            dataType:'json',
            data:{
                jifen:jifen,
				user_id:user_id
            },
            success:function (data) {
               if(data == 0){
                   layer.msg('导入成功');
                   setTimeout(function () {
                       layer.close(open);
                   },1000);

			   }else{
                   layer.msg('导入失败');
                   setTimeout(function () {
                       layer.close(open);
                   },1000);
			   }

            }
        });
    }

    function fymoney(user_id){
		money = $("#money").val();
		if(isNaN(money)){
		    alert('请输入数字');
		    return;
		}
		$.ajax({
		   type:'post',
			url:"<?php echo U('fanyong');?>",
			dataType:'json',
			data:{
		       money:money,
				user_id:user_id
			},
			success:function (data) {
				if(data['code'] == -1){
				    layer.msg(data['msg']);
				}else{
				    layer.close(openid);
                    layer.msg(data['msg']);
				}

            }
		});
    }

    function clearFee(user_id) {
		$.ajax({
			type:"post",
			url:"<?php echo U('clearFee');?>",
			dataType:"json",
			data:{
			    user_id:user_id,
			},
			success:function (data) {
				if(data == 0){
				    layer.msg('清除成功');
				}else{
				    layer.msg('清除失败');
				}
				setTimeout(function () {
					history.go(0);
                },1000);
            }
		});
    }
    
</script>