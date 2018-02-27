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
    <div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>链接生成</div>
    <div class="main-content well" style="height: 700px;">
        <h5 class="alert alert-success" style="padding:5px 10px;line-height:30px;">链接生成</h5>
        <div class="form-horizontal" style="height: 400px">
            <form action="#" method="post" id="dx" enctype="multipart/form-data" onsubmit="return false">

                <div class="form-group" style="display: none;">
                    <label for="inputEmail3" class="col-sm-2 control-label">链接生成</label>
                    <div class="col-sm-6">
                        <!--<input type="text" class="form-control"  style="width:30%" name="qf_jine" value="<?php echo ($data['qf_jine']); ?>" placeholder="">-->
                        <input style="width: 80%;" id="url1"  type="text" name="url" class="inputstyle jifen"  value="<?php echo ($url); ?>"  placeholder="请填写域名地址如http://www.baidu.com" />
                        <br/>
                    </div>

                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">链接生成</label>
                    <div class="col-sm-6">
                        <select class="inputstyle jifen" style="height: 39px;" id="type" name="type">
                            <option value="1">总代</option>
                            <option value="2">特级</option>
                            <option value="3">一级</option>
                        </select>
                    </div>

                </div>
                <div class="col-sm-offset-2 col-sm-10" style="margin-bottom: 20px;">
                    <button onclick="setUrl()" class="btn btn-default">生成链接</button>
                </div>
                <div class="form-group" style="margin-top: 20px;">
                    <label for="inputEmail3" class="col-sm-2 control-label">链接地址</label>
                    <div class="col-sm-6">
                        <!--<input type="text" class="form-control"  style="width:30%" name="qf_jine" value="<?php echo ($data['qf_jine']); ?>" placeholder="">-->
                        <input style="width: 150%;" id="url" type="text" name="url" class="inputstyle jifen"  value=""  placeholder="" />
                        <br/>
                    </div>

                </div>
                <input type="hidden" name="id" value="<?php echo ($bili["id"]); ?>"/>
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
 function setUrl() {
     var url = $('#url1').val();
     var type = $('#type').val();
     var data = new Date();
     var url1 = url + "/Home/User/zhuce?id=0&type="+type+"&time=" + Math.round(new Date().getTime()/1000).toString();
     $('#url').val(url1);
 }
</script>