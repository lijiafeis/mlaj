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
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>客服咨询</div>
	<div class="main-content well">
	<h5 class="alert alert-success" style="padding:5px 10px;line-height:30px;">客服咨询</h5>
		<form class="form-horizontal" id="kfzx"  action="<?php echo U('kfzx');?>"  method="post" enctype="multipart/form-data" >
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-2 control-label" style="margin-left:15px;">客服&二维码</label>
				<img src="<?php echo ($data["img_url"]); ?>" style="height:70px;width:70px">
				<div class="col-sm-10" style="margin-left:15%;margin-top:10px;">
					<input id="file-3" type="file" name="file">
					<span style="color:red">上传封面图片为正方形效果更佳，建议320px * 320px</span>
				</div>
			</div>
			<input type="hidden" name="id" value="<?php echo ($data["id"]); ?>"/>
			<input type="hidden" name="img_url" value="<?php echo ($data["img_url"]); ?>"/>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-default">保存</button>
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