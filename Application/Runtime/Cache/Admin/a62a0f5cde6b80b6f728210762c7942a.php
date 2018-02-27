<?php if (!defined('THINK_PATH')) exit();?><link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/Public/css/font-awesome.min.css">
<link rel="stylesheet" href="/Public/admin/css/base.css">
    <script type="text/javascript" charset="utf-8" src="/Public/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/Public/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="/Public/ueditor/lang/zh-cn/zh-cn.js"></script>
<style>
</style>
<link href="/Public/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>公司简介</div>
	<div class="main-content well">
	<h5 class="alert alert-success" style="padding:5px 10px;line-height:30px;">公司简介</h5>
		<form class="form-horizontal" id="gsjj"  action="<?php echo U('gsjj');?>"  method="post" enctype="multipart/form-data" >
		 <div class="form-group">
			<label class="col-sm-2   col-lg-2 control-label" for="exampleInputEmail1">公司简介</label>
			<div class="col-sm-6 col-lg-4">
			<script id="editor" type="text/plain" style="width:800px;height:400px;" name="content"><?php echo ($data["content"]); ?></script>
			</div>
			<input type="hidden" name="id" value="<?php echo ($data["id"]); ?>"/>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				   <button type="submit" class="btn btn-default">保存</button>
				</div>
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
$("#file-3").fileinput({
	showUpload: false,
	showCaption: false,
	browseClass: "btn btn-info",
	fileType: "any",
    previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
});
    var ue = UE.getEditor('editor');
    var ue = UE.getEditor('editor1');
</script>