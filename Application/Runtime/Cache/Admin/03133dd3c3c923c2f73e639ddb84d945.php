<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title></title>
    <link rel="stylesheet" type="text/css" href="/Public/home/css5/default.css"/>
    <link rel="stylesheet" type="text/css" href="/Public/home/css5/commom.css"/>
    <link rel="stylesheet" type="text/css" href="/Public/home/css5/user.css"/>
</head>
<body>
<div class="wrap">
    <header class="header-top">
        <div class="fixed fixed-top">
            <div class="head">
                <span class="l-icon" onclick="javascript:history.go(-1)"><img src="/Public/home/images5/xiangzuo.png"/></span>
                <span>积分</span>
                <div class="clear"></div>
            </div>
        </div>
    </header>
    <table border="" cellspacing="" cellpadding="" class="table">

    </table>
    <div class="jf-total">总积分:<?php echo ($jifen); ?>分</div>
</div>
</body>
</html>
<link rel="stylesheet" href="/Public/css/weui.new.css">
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/Public/js/xigua_pageload.js"></script>
<script>
    /*初始化相关参数，请求url地址时用的$.post()方法*/
    var scroll_data = {
        'url':"<?php echo U('test1');?>?",//如果不传token值，直接后加一个 ？，当前&后为get参数p，在后台接收处理
        'parentId':$('.table'),//要加载的父元素DOM
        't_data':{'name1':'一些必要的判断条件1','name2':'一些必要的判断条件2','name3':'一些必要的判断条件3'}//异步要传的参数，json
    };
</script>
</html>