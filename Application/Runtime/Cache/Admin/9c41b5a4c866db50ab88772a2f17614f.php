<?php if (!defined('THINK_PATH')) exit();?><!--<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
<link rel="stylesheet" href="/Public/Admin/css/base.css">
<link rel="stylesheet" href="/Public/css/bootstrap.min.css">
<link href="/Public/Admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
<link rel="stylesheet" href="/Public/Admin/css/base.css">
<!--<link href="/Public/Admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />-->
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>会员列表</div>
		<div class="main-content">
			<div>
				<!--<div class="well">-->
					<!--<div class="btn-group" style="">-->

						<!--&lt;!&ndash;<button type="button" class="btn btn-default province all" style="margin:3px 0;" onclick="select_province(this,'17')">所有会员</button>&ndash;&gt;-->
					<!--</div><br/>-->
					<!--<div class="btn-group" style="margin-top:20px;">-->

						<!--<button type="button" class="btn btn-default">会员id</button>-->
						<!--<div class="btn-group">-->
							<!--<input type="text" id="user_id" class="form-control">-->
						<!--</div>-->
						<!--<button type="button"  class="btn btn-default">会员姓名</button>-->
						<!--<div class="btn-group">-->
							<!--<input type="text" id="name"  class="form-control">-->
						<!--</div>-->
						<!--&lt;!&ndash;<button type="button"  class="btn btn-default">积分充值</button>&ndash;&gt;-->
						<!--&lt;!&ndash;<div class="btn-group">&ndash;&gt;-->
							<!--&lt;!&ndash;<select name="cz" id="cz" class="form-control">&ndash;&gt;-->
								<!--&lt;!&ndash;<option value="-1">全部</option>&ndash;&gt;-->
								<!--&lt;!&ndash;<option value="0">未充值</option>&ndash;&gt;-->
								<!--&lt;!&ndash;<option value="1">已充值</option>&ndash;&gt;-->
							<!--&lt;!&ndash;</select>&ndash;&gt;-->
						<!--&lt;!&ndash;</div>&ndash;&gt;-->


						<!--<button type="button" class="btn btn-warning" onclick="getpage(1)">查询</button>-->
					<!--</div>-->
				<!--</div>-->
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
<script src="/Public/Admin/js/bootstrap.min.js"></script>

<script src="/Public/Admin/js/fileinput.js" type="text/javascript"></script>
<script src="/Public/Admin/js/fileinput_locale_zh.js" type="text/javascript"></script>
<script src="/Public/Admin/layer/layer.js"></script>
<script>
var cid = '';
var city_name = '';
$(document).ready(function(){
getpage(1)
});


function getpage(p){
    //得到选中的是什么分类和类型
//	var name = $('#name').val();
//	var id =  $('#user_id').val();
//	var cz =  $('#cz').val();
	$('#list').html('<div style="text-align:center;margin-top:30px;"><img src="/Public/Admin/images/loading.gif" width="60px" ></div>');
	$("#list").load(
		"<?php echo U('tixianSqbb');?>?p="+p,
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