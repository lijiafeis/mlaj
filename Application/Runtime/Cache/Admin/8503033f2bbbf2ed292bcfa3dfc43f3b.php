<?php if (!defined('THINK_PATH')) exit();?><!--<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
<link rel="stylesheet" href="/Public/bootstrap/css/font-awesome.min.css">
<link rel="stylesheet" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" href="/Public/admin/css/base.css">
<link href="/Public/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<style>
    .jifen{
        border: 0px;
        border-radius: 4px;
        padding:10px;
        width: 100px;
        height: 30px;
    }
</style>
<div class="container-fluid main">
    <div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>一键导航</div>
    <div class="main-content well" style="height: 700px;">
        <h5 class="alert alert-success" style="padding:5px 10px;line-height:30px;">一键导航</h5>
        <div class="form-horizontal" style="height: 400px">
            <form action="<?php echo U('yjdh');?>" method="post" id="dx" enctype="multipart/form-data" onsubmit="return true">

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">一键导航</label>
                    <div class="col-sm-6">
                        <!--<input type="text" class="form-control"  style="width:30%" name="qf_jine" value="<?php echo ($data['qf_jine']); ?>" placeholder="">-->
                        <input  type="text" name="url"  class="form-control"  value="<?php echo ($data["url"]); ?>" placeholder="请填写地图的url" />　
                        <br/>
                        <br/>
                    </div>

                </div>
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">修改</button>
                </div>
                <input type="hidden" name="id" value="<?php echo ($data["id"]); ?>"/>
            </form>

        </div>
    </div>
</div>
<!--<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>-->
<script src="/Public/admin/js/bootstrap.min.js"></script>
<script src="/Public/admin/js/fileinput.js" type="text/javascript"></script>
<script src="/Public/admin/js/fileinput_locale_zh.js" type="text/javascript"></script>
<script src="/Public/admin/layer/layer.js"></script>
<script src="/Public/admin/js/jquery.js"></script>
<script>

</script>