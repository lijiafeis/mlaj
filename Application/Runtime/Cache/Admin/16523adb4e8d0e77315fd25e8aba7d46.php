<?php if (!defined('THINK_PATH')) exit();?><!--<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
<link rel="stylesheet" href="/Public/bootstrap/css/font-awesome.min.css">
<link rel="stylesheet" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" href="/Public/admin/css/base.css">
<link href="/Public/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<div class="container-fluid main">
    <div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>商品增加</div>
    <div class="main-content well" style="height: 700px;">
        <h5 class="alert alert-success" style="padding:5px 10px;line-height:30px;">商品增加</h5>
        <div class="form-horizontal" style="height: 400px">
            <form action="<?php echo U('update');?>" method="post" id="dx" enctype="multipart/form-data" onsubmit="return true">

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">标题</label>
                    <div class="col-sm-6">
                        <input class="form-control" class="form-control" value="<?php echo ($data["title"]); ?>"  name="title" id="title"/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">总代价格</label>
                    <div class="col-sm-6">
                        <input type="text" style="width: 100px;" class="form-control" value="<?php echo ($data["fee1"]); ?>" name="fee1" id="fee1" placeholder="单位积分"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">特级价格</label>
                    <div class="col-sm-6">
                        <input type="text" style="width: 100px;" class="form-control" value="<?php echo ($data["fee2"]); ?>" name="fee2" id="fee2" placeholder="单位积分"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">一级价格</label>
                    <div class="col-sm-6">
                        <input type="text" style="width: 100px;" class="form-control" value="<?php echo ($data["fee3"]); ?>" name="fee3" id="fee3" placeholder="单位积分"/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">数量</label>
                    <div class="col-sm-6">
                        <input type="text" style="width: 100px;" class="form-control" value="<?php echo ($data["number"]); ?>" name="number" id="number"/>
                    </div>
                </div>
                <input type="hidden" name="url" value="<?php echo ($data["img_url"]); ?>"/>
                <input type="hidden" name="id" value="<?php echo ($data["id"]); ?>"/>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">尺寸</label>
                    <div class="col-sm-6">
                        <input type="text" style="width: 100px;" class="form-control" value="<?php echo ($data["size"]); ?>" name="size" id="size" placeholder="X,L,M"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">规格</label>
                    <div class="col-sm-6">
                        <input type="text" style="width: 100px;" class="form-control" value="<?php echo ($data["guige"]); ?>" name="guige" id="guige" placeholder="包,箱"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">图片</label>
                    <div class="col-sm-6">
                        <img style="margin-bottom: 10px;" src="<?php echo ($data["img_url"]); ?>" width="150px" height="135px"/>
                        <input type="file" name="file" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">修改</button>
                </div>
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
 $("#cate_id").change(function () {
     cate_id = $('#cate_id').val();
     $.ajax({
        type:"post",
        url:"<?php echo U(getSize);?>",
         dataType:'json',
         data:{
            cate_id:cate_id
         },
         success:function (data) {
            $('#size_id').empty();
            for(var i = 0;i < data.length;i++){
//                alert(data[i]['type']);
                $('#size_id').append("<option value=" + data[i]['id'] + ">" + data[i]['type'] + "</option>");
            }
         }
     });
 });
</script>