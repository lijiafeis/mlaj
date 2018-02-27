<?php if (!defined('THINK_PATH')) exit();?><!--<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
<link rel="stylesheet" href="/Public/admin/css/base.css">
<link rel="stylesheet" href="/Public/css/bootstrap.min.css">
<link href="/Public/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
<link rel="stylesheet" href="/Public/admin/css/base.css">
<!--<link href="/Public/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />-->
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>商品列表</div>
		<div class="main-content">
			<div>
				<!--<div class="well">-->
					<!--<div id="city_list" style="display:none;"></div>-->
					<!--<div class="btn-group" style="margin-top:20px;position: relative">-->

						<!--<div class="btn-group">-->
							<!--<select name = 'fenlei' id = "fenlei" class="form-control" >-->
								<!--<option value="-1">所有分类</option>-->
								<!--<?php if(is_array($cateList)): $i = 0; $__LIST__ = $cateList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?>-->
									<!--<option value = "<?php echo ($row['id']); ?>" ><?php echo ($row['type']); ?></option>-->
								<!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
							<!--</select>-->
						<!--</div>-->
						<!--<div class="btn-group">-->
							<!--<select name = 'fenlei' id = "chicun" class="form-control" >-->
								<!--<option value="-1">所有尺寸</option>-->
								<!--<?php if(is_array($ccList)): $i = 0; $__LIST__ = $ccList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?>-->
									<!--<option value = "<?php echo ($row['id']); ?>" ><?php echo ($row['type']); ?></option>-->
								<!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
							<!--</select>-->
						<!--</div>-->
						<!--<div style="position: relative;left: 175px;top:-34px">　　-->
							<!--&lt;!&ndash;<div class="btn-group" style = "margin-left: 15px;">&ndash;&gt;-->
								<!--&lt;!&ndash;<input type="text" placeholder = "按题目搜索" class="form-control" id="content" style = "width: 250px">&ndash;&gt;-->
							<!--&lt;!&ndash;</div>&ndash;&gt;-->
							<!--<button type="button" class="btn btn-warning" onclick="getpage(1)">查询</button>-->
							<!--<button type="button" class="btn btn-warning" onclick="createMan()">增加产品</button>-->
							<!--&lt;!&ndash;<a href="<?php echo U('createTest');?>"><button type="button" id = "add" class="btn btn-warning" >增加试题</button></a></div>&ndash;&gt;-->
					<!--</div>-->
				<!--</div>-->
			<!--</div>-->
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">列表</a></li>
				<button onclick="create()" style="width: 100px;height: 33px;border: 0px;border-radius: 4px;background: orange;margin-left: 5px;">创建商品</button>
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
//    //得到选中的是什么分类和类型
//	var fenlei = $('#fenlei').val();
//	var leibie =  $('#chicun').val();
//	alert(fenlei);
//	alert(leibie);
//	alert(content);
	$('#list').html('<div style="text-align:center;margin-top:30px;"><img src="/Public/admin/images/loading.gif" width="60px" ></div>');
	$("#list").load(
		"<?php echo U('shopbb');?>?p="+p,
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

function create() {
	location.href="<?php echo U('create');?>"
}
</script>