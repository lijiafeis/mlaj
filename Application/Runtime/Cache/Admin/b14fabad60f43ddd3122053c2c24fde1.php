<?php if (!defined('THINK_PATH')) exit();?><!--<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
<link rel="stylesheet" href="/Public/admin/css/base.css">
<link rel="stylesheet" href="/Public/css/bootstrap.min.css">
<link href="/Public/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
<link rel="stylesheet" href="/Public/admin/css/base.css">
<!--<link href="/Public/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />-->
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>订单列表</div>
		<div class="main-content">
			<div>
				<div class="well">
					<div class="btn-group" style="">

						<!--<button type="button" class="btn btn-default province all" style="margin:3px 0;" onclick="select_province(this,'17')">所有会员</button>-->
					</div><br/>
					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="home">
							<div class="alert alert-success" style="padding:5px 10px;margin:15px 0;line-height:30px;">
								<div class="col-lg-3 col-md-3" style="padding:0;margin:3px;">
									<div class="input-group input-group">
										<span class="input-group-addon" id="sizing-addon1">订单号</span>
										<input type="text" class="form-control" name="order_sn" id="order_sn" placeholder="输入订单号查询" aria-describedby="sizing-addon1">
									</div>
								</div>
								<div class="col-lg-3 col-md-3" style="padding:0;margin:3px;">
									<div class="input-group input-group">
										<span class="input-group-addon" id="sizing-addon1">支付状态</span>
										<select class="form-control" name="state" id="state">
											<option value="-1">全部</option>
											<option value="0">未支付</option>
											<option value="1">已支付</option>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-3" style="padding:0;margin:3px;">
									<div class="input-group input-group">
										<span class="input-group-addon" id="sizing-addon1">订单状态</span>
										<select class="form-control" name="is_fh" id="is_fh">
											<option value="-1">全部</option>
											<option value="0">未发货</option>
											<option value="1">已发货</option>
										</select>
									</div>
								</div>


								<style>
									.table th{font-size:14px;}
									.table tr td{font-size:10px;color:#777;}
								</style>

								<div class="col-lg-2 col-md-2" style="padding:0;margin:3px;">
									<div class="input-group input-group">
										<input class="btn btn-default" type="submit" value="搜索" onclick="getpage(1)">
										<button type="button" class="btn btn-warning" onclick="dcExcel()" style="margin-left: 15px;">导出订单</button>
									</div>
								</div>
								<div class="clearfix visible-xs-block"></div><div style="clear:both"></div>
							</div>
							<input style="border:0px;border-radius: 4px;height: 34px;" placeholder="下订单时间"  onclick="laydate({istime: true, format: 'YYYY-MM-DD'});" id="starttime" type="text"/>
							<input style="border:0px;border-radius: 4px;height: 34px;" placeholder="下订单时间" onclick="laydate({istime: true, format: 'YYYY-MM-DD'});" id="endtime" type="text"/>
							<button style="width: 60px;height: 34px;background: orange;border:0px;border-radius: 4px;" onclick="getDataForDate()">
								查看</button>
				</div>
					</div>
				</div>
				<input type="hidden" name="order" id="order"/>
			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="home">
					<div id="list"></div>
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
<script src="/Public/admin/laydate/laydate.js"></script>
<script>
var cid = '';
var city_name = '';
$(document).ready(function(){
getpage(1)
});


var openid = null;
function dcExcel() {
	openid = layer.open({
		type: 1,
		skin: '', //加上边框
		area: ['400px', '350px'], //宽高
		content: '<div style="text-align: center;margin-top:10px;"><div ><div ><div ><p>选择开始导出时间</p>' +
		'<input onclick="setDate()" style="border-radius: 4px;border: 1px solid #D9DADC;" id="time1" type="text"/>' +
		'<p style="margin-top:7px;">选择结束导出时间</p>' +
		'<input onclick="setDate()" style="border-radius: 4px;border: 1px solid #D9DADC;" id="time2" type="text"/>' +
		'<br/>' +
        '<p style="margin-top:7px;">选择导出类型</p>' +
		'<select id="type" style="width:150px;height:25px;border-radius: 4px;border: 1px solid #D9DADC;">' +
		'<option value="-1">全部</option><option value="0">未发货</option><option value="1">已发货</option></select>' +
		'<p style="margin-top:7px;">导出文件名</p>' +
		'<input style="border-radius: 4px;border: 1px solid #D9DADC;" type="text" id="filename" placeholder="默认是Excel"/>' +
		'<div class="">' +
		'<button style="margin-top: 7px;" type="submit" onclick="dcExcel1()" class="btn btn-default btn-default1" >导出</button>' +
		'</div></div></div></div></div>'
});
}

function setDate() {
    laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'});
}

function dcExcel1() {
    var time1 = $('#time1').val();
    var time2 = $('#time2').val();
    var type = $('#type').val();
    var filename = $('#filename').val();
    layer.close(openid);
    location.href = "<?php echo U('dchuExcel');?>?time1=" + time1 + "&time2=" + time2 + "&filename=" + filename + "&type=" + type;

}

function getDataForDate() {
    var startTime= $('#starttime').val();
    var endTime= $('#endtime').val();
    $('#list').html('<div style="text-align:center;margin-top:30px;"><img src="/Public/admin/images/loading.gif" width="60px" ></div>');
    $("#list").load(
        "<?php echo U('getDataForDate');?>?starttime=" + startTime + "&endtime=" + endTime,
        function() {}
    );
}



function getOrder() {
	$('#order').val(1);
	getpage(1)
}

function getpage(p){
    var order_sn = $('#order_sn').val();
    var state = $('#state').val();
    var type = $('#is_fh').val();
    var order = $("#order").val();
	$('#list').html('<div style="text-align:center;margin-top:30px;"><img src="/Public/admin/images/loading.gif" width="60px" ></div>');
	$("#list").load(
		"<?php echo U('orderbb');?>?p="+p+"&order_sn="+order_sn+"&state="+state + "&type=" + type,
		function() {}
	);
	$("#order").val('');
}

$('.all').click()
$('body').on('click','.all_check',function(){
    if($(this).is(':checked')){
        $('body .one_check').each(function(){
            $(this).prop('checked','checked')
        })
    }else{
        $('body .one_check').each(function(){
            $(this).removeAttr('checked')
        })
    }
});

function createMan() {
	location.href="<?php echo U('create');?>"
}
</script>