<?php if (!defined('THINK_PATH')) exit();?><link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/Public/bootstrap/css/font-awesome.min.css">
<link rel="stylesheet" href="/Public/admin/css/base.css">
<link href="/Public/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>微信管理</div>
	<div class="main-content well">

		
		
				
				<h5 class="alert alert-success" style="padding:5px 10px;line-height:30px;">您可以在这里创建一些必要的关键词进行自动的快捷回复</h5>
				
			<div style="margin-bottom:20px;"><a href="<?php echo U('newsadd');?>"><button type="button" class="btn btn-warning">新增图文回复</button></a></div>
				<table class="table table-striped table-hover" style="font-size:14px;">
					<th>编号</th>
					<th>关键词</th>
					<th>图文标题</th>
					<th>创建时间</th>
					<th>调用</th>
					<th>转发</th>
					<th>阅读</th>
					<th>点赞</th>
					<th>评论</th>
					<th>赞赏/元</th>
					<th>操作</th>				
					<?php if(is_array($info)): $kk = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($kk % 2 );++$kk;?><tr>
						<td><?php echo ($kk); ?></td>
						<td><?php echo ($vv["keyword"]); ?></td>
						<td><button class="btn btn-link"><?php echo ($vv["title"]); ?></button></td>
						<td><?php echo ($vv["createtime"]); ?></td>
						<td><?php echo ($vv["click"]); ?></td>
						<td><?php echo ($vv["share"]); ?></td>
						<td><?php echo ($vv["read_num"]); ?></td>
						<td><?php echo ($vv["zan"]); ?></td>
						<td><a href="<?php echo U('news_comment');?>?new_id=<?php echo ($vv["id"]); ?>"><?php echo ($vv["comment"]); ?></a></td>
						<td><a href="<?php echo U('news_shang');?>?new_id=<?php echo ($vv["id"]); ?>"><?php echo ($vv["shang"]); ?></a></td>
						<td><a href="<?php echo U('newsadd');?>?news_id=<?php echo ($vv["id"]); ?>"><button type="button" class="btn btn-warning btn-sm">修改</button></a>
					<button type="button" class="btn btn-danger btn-sm" onclick="del(<?php echo ($vv["id"]); ?>)">删除</button></td>						
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				</table>
				<div class="pagelist"><?php echo ($page); ?></div>
		    
		
		
		
	</div>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/Public/admin/js/bootstrap.min.js"></script>

<script src="/Public/admin/js/fileinput.js" type="text/javascript"></script>
<script src="/Public/admin/js/fileinput_locale_zh.js" type="text/javascript"></script>
<script src="/Public/admin/layer/layer.js"></script>
<script>
$('#add').click(function(){
		layer.open({
	        type: 2,
	        //skin: 'layui-layer-lan',
	        title: '绑定公众号信息',
	        fix: false,
	        shadeClose: true,
	        maxmin: true,
	        area: ['800px', '500px'],
	        content: '<?php echo U(textadd);?>',
	        end: function(){
	        }
	    });
	})
	function del(i){
			layer.confirm('确定删除？', {
				btn: ['是，确认','否，再看看'] //按钮
			}, function(){
				layer.msg('正在删除，请稍候', {icon: 16});
				$.ajax({
						type: "POST",
						url: "<?php echo U('delnews');?>",
						dataType: "json",
						data: {"id":i},
						success: function(json){
							if(json.success==1){
								window.location.href = "<?php echo U('news');?>";
								
							}else{
								layer.msg("处理失败，请重新尝试");				
							}
						},
						error:function(){
							layer.msg("发生异常！");
						}
					});
				
			}, function(){
				
			});
		}

</script>