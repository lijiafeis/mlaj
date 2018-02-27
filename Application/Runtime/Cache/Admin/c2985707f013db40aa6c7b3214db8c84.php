<?php if (!defined('THINK_PATH')) exit();?><link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/Public/bootstrap/css/font-awesome.min.css">
<link rel="stylesheet" href="/Public/admin/css/base.css">
<link href="/Public/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>微信管理</div>
	<div class="main-content well">
	<h5 class="alert alert-success" style="padding:5px 10px;line-height:30px;">当用户发送内容不能触发文本回复、图文回复及其它内容时自动回复给用户该内容</h5>
		<form class="form-horizontal" action="/Admin/Base/custom.html" method="post" onsubmit="return check()">
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">启用模式</label>
				<div class="col-sm-6">
					<select class="form-control" name="switch">					
					  <option value="2" <?php if($info[0]['switch'] == 2): ?>selected<?php endif; ?> >开启多客服</option>
					  <option value="1" <?php if($info[0]['switch'] == 1): ?>selected<?php endif; ?> >开启自动回复</option>
					  <option value="0" <?php if($info[0]['switch'] == 0): ?>selected<?php endif; ?> >关闭</option>					
					</select>
					<span id="" class="help-block" style="color:red">注意：三种状态均可使用。详细介绍请点击查看<a href="http://jifen.xiguakeji.cc/index.php/Home/news/index.html?news_id=9&token=7028f011">［使用说明］</a></span>
				</div>
				
			</div>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">图文关键词</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="" name="keyword" value="<?php echo ($info[0]['keyword']); ?>" placeholder="">
					<span id="" class="help-block" style="color:red">当留空时回复下方文本内容，填写则回复该关键词创建的图文回复</span>
				</div>
			</div>
			
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">文本内容</label>
				<div class="col-sm-6">
					<textarea class="form-control" rows="7" name="content" id="content"><?php echo ($info[0]['content']); ?></textarea>
				</div>
			</div>
			 <input type="hidden" class="form-control" id="" name="id" value="<?php echo ($info[0]['id']); ?>" placeholder="">
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				   <button type="submit" class="btn btn-default">确认保存</button>
				</div>
			 </div>
		</form>
	</div>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/Public/admin/js/bootstrap.min.js"></script>

<script src="/Public/admin/js/fileinput.js" type="text/javascript"></script>
<script src="/Public/admin/js/fileinput_locale_zh.js" type="text/javascript"></script>
<script src="/Public/admin/layer/layer.js"></script>
<script>
	function check(){		
		if($('#content').val()==""){
			layer.msg("回复内容不能为空");
			return false;
		}
	}

</script>