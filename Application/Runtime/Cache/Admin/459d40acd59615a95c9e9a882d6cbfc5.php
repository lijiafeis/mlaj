<?php if (!defined('THINK_PATH')) exit();?><!--<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
<link rel="stylesheet" href="/Public/admin/css/base.css">
<link rel="stylesheet" href="/Public/css/bootstrap.min.css">
<link href="/Public/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
<link rel="stylesheet" href="/Public/admin/css/base.css">
<!--<link href="/Public/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />-->
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>会员列表</div>
		<div class="main-content">
			<div>

			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">列表</a></li>
			</ul>

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
<script>
var cid = '';
var city_name = '';
$(document).ready(function(){
getpage(1)
});


function getpage(p){
    //得到选中的是什么分类和类型
	var fenlei = $('#fenlei').val();
	var leibie =  $('#chicun').val();
	if(!fenlei){
	    fenlei = -1;
	}
	if(!leibie){
	    leibie = -1;
	}
	$('#list').html('<div style="text-align:center;margin-top:30px;"><img src="/Public/admin/images/loading.gif" width="60px" ></div>');
	$("#list").load(
		"<?php echo U('shMemberbb');?>?p="+p,
		function() {}
	);
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


//function del(){
//	//alert(id);
//	layer.confirm('删除后无法恢复，确认删除吗', {
//		btn: ['确认','取消'] //按钮
//	}, function(){
//
//		var del_id = '';
//		$('body .one_check').each(function(){
//			if($(this).is(':checked')){
//				del_id = del_id+$(this).val()+','
//			}
//		})
//		if(del_id == ''){layer.closeAll();layer.msg('未选中任何内容');exit;}
//		$.post("<?php echo U('delhy');?>",{del_id,del_id},function(){
//
//			layer.msg("删除成功");
//			$('body .one_check').each(function(){
//			if($(this).is(':checked')){
//				$(this).parent().parent().css("display","none");
//			}
//		})
//			// var td = $(obj).parent();//alert(a);
//			// td.parent().css("display","none");
//		});
//	}, function(){
//
//	});
//}
function createMan() {
	location.href="<?php echo U('create');?>"
}
</script>