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
										<span class="input-group-addon" id="sizing-addon1">用户id</span>
										<input type="text" class="form-control" name="user_id" id="user_id" placeholder="" aria-describedby="sizing-addon1">
									</div>
								</div>
								<div class="col-lg-3 col-md-3" style="padding:0;margin:3px;">
									<div class="input-group input-group">
										<span class="input-group-addon" id="sizing-addon1">用户名</span>
										<input type="text" class="form-control" name="name" id="name" placeholder="" aria-describedby="sizing-addon1">
									</div>
								</div>


								<style>
									.table th{font-size:14px;}
									.table tr td{font-size:10px;color:#777;}
								</style>

								<div class="col-lg-2 col-md-2" style="padding:0;margin:3px;">
									<div class="input-group input-group">
										<input class="btn btn-default" type="submit" value="搜索" onclick="getpage(1)">
									</div>
								</div>
							</div>
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


function getpage(p){
    var user_id = $('#user_id').val();
    var name = $('#name').val();
	$('#list').html('<div style="text-align:center;margin-top:30px;"><img src="/Public/admin/images/loading.gif" width="60px" ></div>');
	$("#list").load(
		"<?php echo U('dailibb');?>?p="+p+"&user_id="+user_id+"&name="+name,
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