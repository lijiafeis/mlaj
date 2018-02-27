<?php if (!defined('THINK_PATH')) exit();?><link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/Public/bootstrap/css/font-awesome.min.css">
<link rel="stylesheet" href="/Public/admin/css/base.css">
<link href="/Public/admin/css/jquery.minicolors.css" rel="stylesheet" type="text/css" />
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>管理员信息</div>
	<div class="main-content">
<style>
.table tr td{font-size:12px;vertical-align:middle;}
.form-control{font-size:8px;}
</style>
<div>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">权限组</a></li>		    
	<li role="presentation"><a href="#admin" aria-controls="admin" role="tab" data-toggle="tab" onclick="list()">管理员</a></li>		    	    
  </ul>
  
  <!-- Tab panes -->
  <div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="home">
		<div class="alert alert-success" style="padding:5px 10px;margin:15px 0;line-height:30px;">
			请先设置权限组，然后再添加管理员
		</div>
		<div class="well">
			<a href="<?php echo U('admin_add');?>" class="btn btn-warning" >添加权限组</button></a><br/><br/>
			
			<table class="table table-striped" style="font-size:14px;">
			<th>组名</th>
			<th>状态</th>
			<th>管理员数</th>
			<th>操作</th>
			<?php if(is_array($group_info)): $i = 0; $__LIST__ = $group_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><tr>
				<td><?php echo ($vv["title"]); ?></td>
				<td>
				<button class="btn btn-link btn-sm">
				<?php if($vv['status'] == 1): ?>启用中
				<?php else: ?>
				禁用中<?php endif; ?>
				</button>
				</td>
				<td><?php echo ($vv["num"]); ?></td>
				<td>
				<a href="<?php echo U('admin_add');?>?id=<?php echo ($vv["id"]); ?>"><button class="btn btn-success btn-sm">修改</button></a>
				<button class="btn btn-danger btn-sm" onclick="del(this,'<?php echo ($vv["id"]); ?>','admin_del')">删除</button>
				</td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</table>
			<div class="pagelist"><?php echo ($page); ?></div>
		</div>
		
		
	</div>
	<div role="tabpanel" class="tab-pane" id="admin">
		<div class="alert alert-success" style="padding:5px 10px;margin:15px 0;line-height:30px;">
			请先设置权限组，然后再添加管理员
		</div>
		<div class="well" id="list">
		</div>
	</div>
  </div>
</div>
		
	</div>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/Public/admin/js/bootstrap.min.js"></script>
<script src="/Public/admin/layer/layer.js"></script>
<script src="/Public/admin/js/jquery.minicolors.min.js" type="text/javascript"></script>
<script>
	$('#myTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})

function list() {
	$('#list').html('<div style="text-align:center;margin-top:30px;"><img src="/Public/admin/images/loading.gif" width="60px" ></div>');
	$('#list').load("<?php echo U('user_list');?>",function(){});
	
}
function del(obj,id,url){
	if(id == 1){layer.msg('默认管理员和超级管理员群组不能删除！');exit;}
	layer.confirm('删除后无法恢复，确认删除吗', {
		btn: ['确认','取消'] //按钮
	}, function(){
		$.ajax({
			type: "POST",
			url: "<?php echo U('"+url+"');?>?time="+new Date(),
			dataType: "json",
			data: {
				"id":id,
			},
			success: function(json){
				if(json.success == 1){
					layer.msg("删除成功");
					var td = $(obj).parent();//alert(a);
					td.parent().css("display","none");	
				}else{
					layer.msg(json.info);
				}
				

			},
			error:function(){
				alert('发生错误');
			}
		});
	}, function(){
		
	});	
}

</script>