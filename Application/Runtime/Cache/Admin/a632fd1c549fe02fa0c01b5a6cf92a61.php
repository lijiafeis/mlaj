<?php if (!defined('THINK_PATH')) exit();?><!--<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
<link rel="stylesheet" href="/Public/admin/css/base.css">
<link rel="stylesheet" href="/Public/css/bootstrap.min.css">
<link href="/Public/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
<link rel="stylesheet" href="/Public/admin/css/base.css">
<!--<link href="/Public/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />-->
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>尺寸设置</div>
		<div class="main-content">

			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">列表</a></li>
				<button type="button" class="btn btn-warning" onclick="create()">增加尺寸</button>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="home">
					<div id="list"></div>
				</div>
			</div>
		</div>
	<table class="table table-striped"  style="font-size:14px;margin-top: 20px;">
		<th style="width: 5px;">
			<input type="checkbox" class="all_check">
		</th>
		<th>尺寸</th>
		<th style="text-align: center;">操作</th>
		<?php if(is_array($data)): $kk = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($kk % 2 );++$kk;?><tr id="<?php echo ($kk); ?>" style="font-size:13px;">
				<td><input type="checkbox" class="one_check" value="<?php echo ($vv["id"]); ?>"></td>
				<td><?php echo ($vv['name']); ?></td>

				<td style="text-align: center;">
					<a href="<?php echo U('update');?>?id=<?php echo ($vv["id"]); ?>"><button type="button" class="btn btn-warning " style = "background: #44B549">修改</button></a>
					<button type="button" class="btn btn-warning" onclick="del(<?php echo ($vv["id"]); ?>)" style = "background: #D84F4B">删除</button>
				</td>
			</tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
	</table>
</div>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/Public/admin/js/bootstrap.min.js"></script>

<script src="/Public/admin/js/fileinput.js" type="text/javascript"></script>
<script src="/Public/admin/js/fileinput_locale_zh.js" type="text/javascript"></script>
<script src="/Public/admin/layer/layer.js"></script>
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
                        location.href= "<?php echo U('size');?>"
                    }else{
                        layer.msg('删除失败');
                    }
                }
            });
        }, function(){

        });
    }
function create() {
	location.href="<?php echo U('create');?>"
}
</script>