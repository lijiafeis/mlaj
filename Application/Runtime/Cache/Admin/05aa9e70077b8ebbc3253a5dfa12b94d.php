<?php if (!defined('THINK_PATH')) exit();?><link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/Public/bootstrap/css/font-awesome.min.css">
<link rel="stylesheet" href="/Public/admin/css/base.css">
<link href="/Public/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>订单管理</div>
	<div class="main-content">
<style>
.col-sm-6{margin:5px 0;border-bottom:1px solid #f8f8f8;font-size:14px;}
.col-sm-6 span{font-weight:bold;color:#777}
</style>
		<div>
		  <!-- Nav tabs -->
		  <ul class="nav nav-tabs" role="tablist">
		    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">订单详情</a></li>		    
		    <li role="presentation"><a href="javascript:void(0);" onclick="history.go(-1);">返回上一页</a></li>		    
		  </ul>
		  
		  <!-- Tab panes -->
		  <div class="tab-content" style="margin-top:30px;border:1px solid #dddddd;padding:10px 2%;">
		    <div role="tabpanel" class="tab-pane active" id="home">
				<div class="bg-success" style="padding:10px;margin:5px 0;">基本信息</div>
				<div class="col-sm-6"><span>订单号：</span><span><?php echo ($order["time"]); ?></span></div>
				<div class="col-sm-6"><span>订单状态：</span><?php if($order["type"] == 0 ): ?><font color="red" size="4">未转移</font><?php endif; ?>
					<?php if($order["type"] == 1 ): ?><font color="#000" size="4">未提货</font><?php endif; ?>
					<?php if($order["type"] == 2 ): ?><font color="#555">已发货</font><?php endif; ?></div>
				<div class="col-sm-6"><span>购买人ID：</span><?php echo ($order["user_id"]); ?></div>
				<div class="col-sm-6"><span>下单时间：</span><?php echo (date("Y-m-d H:i:s",$order['time'])); ?></div>
				<div style="clear:both;"></div>
				<div class="bg-success" style="padding:10px;margin:5px 0;">收货人信息</div>
				<div class="col-sm-6"><span>姓名：</span><?php echo ($user_address['name']); ?></div>
				<div class="col-sm-6"><span>手机号：</span><?php echo ($user_address['tel']); ?></div>
				<div class="col-sm-6"><span>通讯地址：</span><?php echo ($user_address['address']); ?></div>
				<div style="clear:both;"></div>
				<div class="bg-success" style="padding:10px;margin:5px 0;">商品信息</div>
				<table class="table table-striped" style="font-size:14px;">
					<!--<th>商品ID</th>-->
					<th>商品名称</th>
					<th>缩略图</th>
					<!--<th>单价</th>-->
					<th>数量</th>
					<th>尺寸</th>
					<th>规格</th>
					<th>金额</th>
					<th></th>
					<?php if(is_array($order_info)): $i = 0; $__LIST__ = $order_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
						<!--<td><?php echo ($v["good_id"]); ?></td>-->
						<td><?php echo ($v["shop_name"]); ?></td>
						<td><img src="<?php echo ($v["pic_url"]); ?>" width="30px"></td>
						<!--<td>￥<?php echo ($v["money"]); ?></td>-->
						<td><?php echo ($v["gmnumber"]); ?></td>
						<td><?php echo ($v["size"]); ?></td>
						<td><?php echo ($v["danwei"]); ?></td>
						<td>￥<?php echo ($v["money"]); ?></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					<tr><td style="color:red">订单总金额 ￥<?php echo ($order["shopmoney"]); ?></td></tr>
					<!--<?php if($info != null): ?>-->
						<!--<tr><td style="color:red">代金卷</td><td><?php echo ($info["title"]); ?></td><td><?php echo ($info["money"]); ?></td></tr>-->
					<!--<?php endif; ?>-->
				</table>
				<div class="form-horizontal" style="height: 400px">

						<div class="form-group">
							<label for="inputEmail3" class="col-sm-2 control-label">物流订单号</label>
							<div class="col-sm-6">
								<input type="text"  class="form-control" value="<?php echo ($order["order_sn"]); ?>" name="order_sn" id="order_sn" />
							</div>
						</div>

						<div class="form-group">
							<label for="inputEmail3" class="col-sm-2 control-label">物流选择</label>
							<div class="col-sm-6">
								<select name="kd" class="form-control" id="kd">
									<option value="-1" >快递选择</option>
									<option value="SF" <?php if($order["kd_number"] == SF): ?>selected<?php endif; ?>>顺丰快递</option>
									<option value="STO" <?php if($order["kd_number"] == STO): ?>selected<?php endif; ?>>申通快递</option>
									<option value="YD" <?php if($order["kd_number"] == YD): ?>selected<?php endif; ?>>韵达快递</option>
									<option value="YTO" <?php if($order["kd_number"] == YTO): ?>selected<?php endif; ?>>圆通速递</option>
									<option value="ZJS" <?php if($order["kd_number"] == ZJS): ?>selected<?php endif; ?>>宅急送</option>
									<option value="ZTO" <?php if($order["kd_number"] == ZTO): ?>selected<?php endif; ?>>中通速递</option>
									<option value="AMAZON" <?php if($order["kd_number"] == AMAZON): ?>selected<?php endif; ?>>亚马逊物流</option>
								</select>
							</div>
						</div>
							<div style="width: 100%;text-align: center;">
								<button onclick="tihuofahuo(<?php echo ($order["id"]); ?>,<?php echo ($order["type"]); ?>)" style="width: 90px;height: 40px;border: 0px; border-radius: 4px;">确认发货</button>
								<!--<button onclick="shouhuo(<?php echo ($order["id"]); ?>,<?php echo ($order["type"]); ?>)" style="width: 90px;height: 40px;border: 0px; border-radius: 4px;">确认收货</button>-->
								<!--<button onclick="wuliu(<?php echo ($order["id"]); ?>,<?php echo ($order["type"]); ?>)" style="width: 90px;height: 40px;border: 0px; border-radius: 4px;">查看物流</button>-->
							</div>



				</div>
				<!--<div class="bg-success" style="padding:10px;margin:5px 0;">操作信息</div>-->

		    </div>
		
		  </div>
		</div>
		
	</div>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/Public/admin/js/bootstrap.min.js"></script>
<script src="/Public/admin/js/fileinput.js" type="text/javascript"></script>
<script src="/Public/admin/js/fileinput_locale_zh.js" type="text/javascript"></script>
<script src="/Public/admin/layer/layer.js"></script>
<script>
	$('#myTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})
	function tihuofahuo(order_id,type) {
//		if(type != 0){
//		    layer.msg('你已经发过货，不能重新发货');
//			return;
//		}
		order_sn = $('#order_sn').val();
		kd_number = $('#kd').val();
		if(order_sn == null || kd_number == -1){
            layer.msg('请填写物流信息');
            return;
		}
		$.ajax({
		    type:'post',
			url:"<?php echo U('tihuofahuo');?>",
			dataType:'json',
			data:{
		        order_sn:order_sn,
				kd_number:kd_number,
				order_id:order_id
			},
			success:function (data) {
				if(data == 0){
				    layer.msg('发货成功');
                    setTimeout(function () {
                        history.go(0);
                    },1000);
				}else{
				    layer.msg('发货失败');
				}
            }
		});
    }

    function wuliu(order_id,type) {
//		if(type == 0){
//		    layer.msg('请先发货');
//			return;
//		}
        location.href = "<?php echo U('showWuliu');?>?order_id=" + order_id;
    }
    
    function zhuanyi(order_id) {
        $.ajax({
            type:'post',
            url:"<?php echo U('zhuanyi');?>",
            dataType:'json',
            data:{
                order_id:order_id
            },
            success:function (data) {
                if(data == 0){
                    layer.msg('转移成功');
                    setTimeout(function () {
                        history.go(0);
                    },1000);
                }
                if(data == -2){
                    layer.msg('他的上级没有货，无法转移');
                }
                if(data == -1){
                    layer.msg('转移失败');
				}
				if(data == -3){
                    layer.msg('无法转移');
				}
            }
        });
    }

</script>