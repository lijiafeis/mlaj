<?php if (!defined('THINK_PATH')) exit();?><!--<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
<link rel="stylesheet" href="/Public/bootstrap/css/font-awesome.min.css">
<link rel="stylesheet" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" href="/Public/admin/css/base.css">
<link href="/Public/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<div class="container-fluid main">
    <div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>订单详情</div>
    <div class="main-content well" style="height: 1400px;">
        <h5 class="alert alert-success" style="padding:5px 10px;line-height:30px;">订单详情</h5>
        <div class="form-horizontal" style="height: 400px">
            <form action="<?php echo U('findWuliu');?>" method="post"  id="dx" enctype="multipart/form-data" onsubmit="return isorder_sn(<?php echo ($data['is_fh']); ?>)">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">物流单号</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="order_sn"/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">物流选择</label>
                    <div class="col-sm-6">
                        <select name="kd_number" class="form-control" id="kd">
                            <option value="-1" >快递选择</option>
                            <option value="SF" <?php if($data["kd_number"] == SF): ?>selected<?php endif; ?>>顺丰快递</option>
                            <option value="STO" <?php if($data["kd_number"] == STO): ?>selected<?php endif; ?>>申通快递</option>
                            <option value="YD" <?php if($data["kd_number"] == YD): ?>selected<?php endif; ?>>韵达快递</option>
                            <option value="YTO" <?php if($data["kd_number"] == YTO): ?>selected<?php endif; ?>>圆通速递</option>
                            <option value="ZJS" <?php if($data["kd_number"] == ZJS): ?>selected<?php endif; ?>>宅急送</option>
                            <option value="ZTO" <?php if($data["kd_number"] == ZTO): ?>selected<?php endif; ?>>中通速递</option>
                            <option value="AMAZON" <?php if($data["kd_number"] == AMAZON): ?>selected<?php endif; ?>>亚马逊物流</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">查询</button>
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
    function isorder_sn(is_fh) {
	return true;
        if(is_fh == 0){
            alert('你不能发货');
            return false;
        }
        $kd = $('#kd').val();
        if($kd == -1){
            alert('请选择快递');
            return false;
        }
        $order_sn = $('#order_sn').val();
        if(!$order_sn){
            alert('请填写物流订单号');
            return false;
        }
        return true;
    }
</script>