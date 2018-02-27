<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title></title>
		<link rel="stylesheet" type="text/css" href="/Public/css/default.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/css/style1.css"/>
		<!--<link rel="stylesheet" type="text/css" href="/Public/css/footer.css">-->
		<!--<link rel="stylesheet" type="text/css" href="/Public/css/weui.new.css">-->
		<!--<link rel="stylesheet" type="text/css" href="/Public/css/user-style.css">-->	
		<link rel="stylesheet" href="https://res.wx.qq.com/open/libs/weui/0.4.3/weui.min.css">
		<style>
		body{background: url(/Public/img/bg.jpg) repeat 50% 50%;background-size: 100%;}
			*{
				margin: 0px;
				padding:0px;
			}

			.footer_wrap {
				max-width: 640px;
				min-width: 320px;
				margin: 0 auto;
				width: 100%;
				position: fixed;
				bottom: 0;
				z-index: 9999;
				
			}

			.footer_wrap li {
				float: left;
				width: 25%;
				text-align: center;
				height: 30px;
				line-height: 30px;
				font-size: 12px;
				background:#fff;
				border-right: 1px #ccc solid;
				box-sizing: border-box;
			}
			.footer_wrap a {
				color: black;
				font-size: 12px;
			}
			
			.top-nav{
				background: url(/Public/img/tixian_bg.jpg) repeat;
			}
			.money{
				width: 80px;
				margin-top:5px;
				margin-bottom: 5px;
				margin-left:10px;
				height:50px;
				background: deepskyblue;
				color: black;
				border:none;
				/*border-radius: 2;*/
				border-radius: 15px;
				/*background:#ccc;*/
				/*display:block;*/
			}
		#dialog3{display: none;}
		.weui_dialog{border-radius: 5px;max-width: 640px;min-width: 300px;width: 90%;background: rgba(0,0,0,0);}
			.hongbao{
				padding: 20px;
				background:-webkit-gradient(linear, 0% 0%, 0% 100%,from(#f6ecb6), to(#f04e46));
			}
		.weui_btn_default{background: #F6ECB6;}
		.hongbao{text-align: center;color:#f6ecb6 ;border-radius:10px;}
		.hongbao .tit{font-size: 25px;color: #ea2338;}
		.hongbao .change{margin-top: 20px;margin-bottom: 20px;color: #ea2338;}
		.hongbao .change span{font-size: 30px;}
		.hongbao .detail{color: #fff;margin-bottom: 20px;}
		.hongbao .enter{border:1px #fff solid;color: #fff;background:rgba(0,0,0,0);}
		.hongbao .again{margin-top: 25px;color: red;}
		.weui_btn_primary:not(.weui_btn_disabled):active{background:rgba(0,0,0,0)}
		.weui_btn_default:not(.weui_btn_disabled):active{background:rgba(0,0,0,0)}
		.weui_btn{font-size: 16px;}
		</style>
	</head>
	<body style="background:#457836;">
		<div class="wrap">
			<ul class="top-nav">
				<li>
					<div style="color: black"><b>距离开奖时间</b></div>
					<b><div style="color: #d55f30;" id="kjtime"></div></b>
				</li>
				<li>
					<!--<div>上期开奖结果</div>-->
					<!--<div style="color: #d55f30;">第300期:大6点</div>-->
					<div style="color: #d55f30;" id="kjinfo">
						<b style="color: #000;">第<span id="qishu"></span>期开奖结果:  <br/></b><b><span id="dian"></span>点<span id="type"></span></b>
						<!--<input type="hidden" id="hidden"/>-->
					</div>
				</li>
				<div class="clear"></div>
			</ul>
			<div class="weui_dialog_confirm" id="dialog3">
				<div class="weui_mask"></div>
				<div class="weui_dialog" style="background: rgba(0,0,0,0);">
					<div class="hongbao">
						<div class="tit"><span id="message"></span></div>
						<div class="change"><span id="fee"></span> 元</div>
						<div class="detail"><span id="message1"></span></div>
						<!--<a href="javascript:;" class="weui_btn weui_btn_primary enter" onclick="close_dialog2()">确定</a>-->
						<a href="javascript:;" class="weui_btn weui_btn_default again"  onclick="close_dialog2()">确定</a>
					</div>
				</div>
			</div>
			<div id="x-bg">
				<div style="width: 100px;height: 100px;margin:0 auto 50px;border-radius: 35px;overflow: hidden;"><img src="/Public/images/saizi/1.png" width="100"height="100" id="saiziimage"/></div>
				<ul class="col-3">
					<a href="<?php echo U('history');?>"><li><span style="padding: 10px;border-radius: 5px;border: 1px #000 solid;color: #000;">历史记录</span></li></a>
					<li style="margin-top: -15px;">
						<p style="padding: 8px;border-radius: 5px;border: 1px #000 solid;">
							<span style="color:#000">本次押注</span><br />
							<span style="color: #c75330;" id="money">无</span>
							<input type="hidden" id="hidden" name="hidden" />
						</p>

					</li>
					<a href="<?php echo U('help');?>"><li><span  style="padding: 10px;border-radius: 5px;border: 1px #000 solid;color: #000;"<br />游戏说明</span></li></a>
					<div class="clear"></div>
				</ul>
			</div>

			<!--两列布局-->
			<div class="col_wrap_2" >
				<ul class="col_2">
					<!--<button onclick="ya(1)" >-->
					<li onclick="ya(1)">
						<img src="/Public/images//btn_bg.png" width="145" height="109"style="opacity:0.8;"/>
						<h2>大</h2>
						<p>1赔<?php echo ($money["bili"]); ?></p>
					</li>
					<!--</button>-->
					<!--<button  onclick="ya(0)">-->
					<li onclick="ya(0)">
						<img src="/Public/images//btn_bg.png" width="145" height="109"style="opacity:0.8;"/>
						<h2>小</h2>
						<p >1赔<?php echo ($money["bili"]); ?></p>

					</li>
					<!--</button>-->
					<div class="clear"></div>
				</ul>				
			</div>
			<div class="footer_wrap">
				<ul class="footer">
					<a href="<?php echo U('#');?>">
						<li class="tab">
							玩一玩
						</li>
					</a>
					<a href="<?php echo U('qr');?>">
						<li class="tab">
							免费代理
						</li>
					</a>
					<a href="<?php echo U('tuiguangjl');?>">
						<li class="tab">
							代理业绩
						</li>
					</a>
					<a href="<?php echo U('center');?>">
						<li class="tab" style="border-right: 0;">
							个人中心
						</li>
					</a>
					<div class="clear"></div>
				</ul>
			</div>
		</div>

	</body>
</html>
<script src="/Public/js/jquery-1.8.3.min.js"></script>
<script src="/Public/layer-mobile/layer.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<!--<script src="/Public/Admin/layer/layer.js"></script>-->
<script>
    $(function(){
//        alert('a');
        var date = new Date();
        var time = date.getMinutes();
        $.ajax({
            type:'post',
            url:"<?php echo U('getOneKjInfo');?>",
            dataType:'json',
			data:{
                minute:time,
			},
            success:function (data) {
                var num = data.kj_dx;
                if(num == 0){
                    type = '小';
                }else if(num == 1){
                    type = '大';
                }
                $('#qishu').html(data.kj_id);
                $('#dian').html(data.kj_num);
                $('#type').html(type);
            }
        });
		//当整份的时候，赋值点数到这个变量上面，让图片停止。
		var kj_num = -1;
        setInterval(function (){
            var date = new Date();
            var time = date.getSeconds();
            time = 60 - time;
            if(time != 60){
                $('#kjtime').html(time);
            }else{
                var minute = date.getMinutes();
//                alert(minute);
                $.ajax({
                    type:'post',
                    url:"<?php echo U('getKjInfo');?>",
                    dataType:'json',
                    data:{
                        minute:minute,
                    },
                    success:function (data) {
                        //把上期的信息放到
//                       console.log(data);
                        var num = data.kj_dx;
                        if(num == 0){
                            type = '小';
                        }else if(num == 1){
                            type = '大';
                        }
                        $('#qishu').html(data.kj_id);
                        $('#dian').html(data.kj_num);
                        $('#type').html(type);
                        $('#money').html('无');
//						layer.open({
//                            type: 1,
//                            title: false,
//                            closeBtn: 10,
//                            shadeClose: true,
//                            skin: 'yourclass',
//                            content: "<img  src='/Public/images/saizi/" + data.kj_num + ".png' width='100px' height='100px'/>" +
//							"<br><p style='text-align: center;color: red;font-size: 20px'>" + data.kj_num + "点" + type + "</p>"
//                        });
						var iszhong = data.iszhong;
//						console.log(data);
						if(iszhong != null){
                            if(iszhong == 1){
                                $("#message").html('恭喜你猜中了');
                                $("#fee").html(data.money);
                                $("#message1").html("已存入你的微信零钱");
                                $("#dialog3").css("display","block");
                            }else{
                                $("#message").html('哎呀！差一点点');
                                $("#fee").html(0);
//                                $("#message1").html("已存入你的微信零钱");
                                $("#dialog3").css("display","block");
                            }
						}


                    }
                });
            }
        },1000);
		var i = 1;
        setInterval(function () {
			if(i == 7){
			    i = 1;
			}

            var date = new Date();
            var time = date.getSeconds();
            time = 60 - time;
//            if(time < 60 && time > 55){
////                alert(kj_num);
//                $('#saiziimage').attr('src','/Public/images/saizi/' + kj_num + '.png');
//            }else{
                $('#saiziimage').attr('src','/Public/images/saizi/' + i + '.png');
//            }
        	i++;
        },300);
    });
    var flag = null;
    //点击压大的时候执行的方法。
    function ya(type){
        //页面层
        var qishu = $('#qishu').html();
        flag = layer.open({
            type: 1,
            skin: 'layui-layer-rim', //加上边框
            area: ['300px', '200px'], //宽高
            content: '<div style="width: 300px; height: 200px;">' +
            "<button class='money' onclick='yazhu(<?php echo ($money["one"]); ?>,"+type+","+qishu+")'><?php echo ($money["one"]); ?></button>" +
            "<button class='money' onclick='yazhu(<?php echo ($money["two"]); ?>,"+type+","+qishu+")'><?php echo ($money["two"]); ?></button>" +
            "<button class='money' onclick='yazhu(<?php echo ($money["three"]); ?>,"+type+","+qishu+")'><?php echo ($money["three"]); ?></button>" +
            "<button class='money' onclick='yazhu(<?php echo ($money["four"]); ?>,"+type+","+qishu+")'><?php echo ($money["four"]); ?></button>" +
            "<button class='money' onclick='yazhu(<?php echo ($money["five"]); ?>,"+type+","+qishu+")'><?php echo ($money["five"]); ?></button>" +
            "<button class='money' onclick='yazhu(<?php echo ($money["six"]); ?>,"+type+","+qishu+")'><?php echo ($money["six"]); ?></button>" +
            '<input style="border-radius: 5px;margin-left: 10px;margin-top10px;height: 30px" type="test" id="money12"/>' +
            "<button style='border-radius: 15px;border: none; height: 35px;width: 82px;margin-left: 5px;background: deepskyblue;color: black;' onclick='yazhu(-1," + type +","+qishu+")'>押注</button></div>"
        });
//        alert('a');
    }

    function close_dialog2(){
        $("#dialog3").css("display","none")
    }
    //押注的方法
    function yazhu(money,type,qishu){
//        alert(qishu);
//        return;
        if(money == -1){
           var money = $('#money12').val();
        }
//        alert(money);
        pass(type,money,qishu);
        layer.close(flag);
//        layer.confirm('你确定要押注？', {
//            btn: ['确定','取消'] //按钮
//        }, function(){
//            pass(type,money,qishu);
//			$('#hidden').val(money);
//        }, function(){
//        });

    }

    $.ajax({
        type: "POST",
        url: "<?php echo U('getMoney');?>",
        dataType: "json",
        success: function(json) {
		$('#money').html(json);
		}
    });

    function pass(type,money,qishu){
//        alert(money);
        var shade = layer.open({type:2,content:'正在提交申请',shadeClose:false});
        $.ajax({
            type: "POST",
            url: "<?php echo U('yazhuZhifu');?>",
            dataType: "json",
            data: {"type":type,"money":money,"qishu":qishu},
            success: function(json){
                if(json.success==1){
                    layer.close(shade);
                    console.log(json);
                    var flag = onBridgeReady(json);
//                    var mon = $('#money').html();
//                    if(isNaN(mon)){
//                        $('#money').html(money);
//                    }else{
//                        $('#money').html(Number(mon) + Number(money));
//                    }
					setTimeout(function () {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo U('getMoney');?>",
                            dataType: "json",
                            success: function(json) {
                                $('#money').html(json);
                            }
                        });
                    },9000);
                }else if(json.success==0){
                    layer.close(shade);
                    layer.open({content:'您已经充值成功',skin:'msg',shadeClose:false,time:3});
                }
                else{
                    dialog2(json.info); //onBridgeReady();
                }
            },
            error : function() {
                dialog2("暂时无法充值噢");layer.close(shade);
            }
        });
    }
    function onBridgeReady(json){
//        console.log(json);
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',{"appId" : json.appid,
                "timeStamp":json.timeStamp,
                "nonceStr" : json.nonceStr,
                "package" : 'prepay_id='+json.prepay_id,
                "signType" :"MD5",
                "paySign" : json.paySign, //微信签名
            },
            function(res){
                $.ajax({
                    type: "POST",
                    url: "<?php echo U('abc');?>",
                    dataType: "json",
                    data: {"res":res},
                    success: function(json) {
                    }
                });
                if(res.err_msg == "get_brand_wcpay_request:ok" ) {
                    loading("支付成功…");
                }else{

                }
            }
        );
    }

</script>