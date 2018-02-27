<?php if (!defined('THINK_PATH')) exit();?><link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/mlaj/Public/iconfont/0727/iconfont.css">
<link rel="stylesheet" href="/mlaj/Public/admin/css/base.css">
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/mlaj/Public/admin/js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){
	$('.menu_title').click(function(){
		$(this).attr('data','1');
		$('.menu_title').each(function(){
			if($(this).attr('data') == 1){
				$(this).css('color','#fff');$(this).css('background','#44b549');$(this).find('i').css('color','#fff');
			}else{
				$(this).css('color','#555');$(this).css('background','#fff');$(this).find('i').css('color','#555');
			}
		});
		var obj = $(this).next('dd');
		if(obj.css("display") == 'block'){
			obj.css("display","none");$(this).attr('data','0');
		}else{
			$('dd').each(function(){
				$(this).css("display","none");
			});
			obj.css("display","block");
			$(this).attr('data','0');
		}
	});
	$('.menu dl dd ul li').click(function(){
		$('.menu dl dd ul li').each(function(){
			$(this).css('background','');
			$(this).css('color','');
		});
		$(this).css('background','#44b549');
		$(this).css('color','#fff');
	});
	
});
</script>
<style>
.menu_title:hover{background:#44b549;color:#fff;font-size:15px;}
.menu_title:hover i{color:#fff;}
.iconfont{font-weight:normal;}
</style>
<div class="left" oncontextmenu=self.event.returnValue=false onselectstart="return false">
  <div class="menu">
    <dl>
      <dt class="menu_title" data='0'><i class="icon iconfont add">&#xe668;</i>　微信设置</dt>
      <dd style="display:none;">
        <ul>
            <a href="<?php echo U('Base/config');?>" target="main-frame"><li>微信参数</li></a>  
            <a href="<?php echo U('Base/password');?>" target="main-frame"><li>密码修改</li></a>  
			<a href="<?php echo U('Base/menu');?>" target="main-frame"><li>菜单设置</li></a>           
            <a href="<?php echo U('Base/subscribe');?>" target="main-frame"><li>关注回复</li></a>           
            <a href="<?php echo U('Base/text');?>" target="main-frame"><li>文本回复</li></a>           
            <a href="<?php echo U('Base/news');?>" target="main-frame"><li>图文回复</li></a>                    
            <a href="<?php echo U('Base/custom');?>" target="main-frame"><li>在线客服</li></a> 			
                   
        </ul>
      </dd>
    </dl>
	<!-- <dl>
      <dt class="menu_title" data='0'><i class="icon iconfont">&#xe65a;</i>　系统设置</dt>
      <dd  style="DISPLAY: none">
        <ul>
        
		 <a href="<?php echo U('System/qr');?>" target="main-frame"><li>海报二维码</li></a> 
		
      </dd>
    </dl> -->
      <dl>
          <dt class="menu_title" data='0'><i class="icon iconfont">&#xe65a;</i>　商品列表</dt>
          <dd  style="DISPLAY: none">
              <ul>
                  <a href="<?php echo U('Shop/shop');?>" target="main-frame"><li>商品列表</li></a>
              </ul>
          </dd>
      </dl>

       <!--<dl>
          <dt class="menu_title" data='0'><i class="icon iconfont">&#xe65a;</i>　参数设置</dt>
          <dd  style="DISPLAY: none">
              <ul>
                 <a href="<?php echo U('Canshu/size');?>" target="main-frame"><li>尺寸设置</li></a>
                  <a href="<?php echo U('Canshu/bili');?>" target="main-frame"><li>兑换比例</li></a>
                  <a href="<?php echo U('Canshu/tuijian');?>" target="main-frame"><li>推荐奖</li></a>
              </ul>
          </dd>
      </dl>-->

      <dl>
          <dt class="menu_title" data='0'><i class="icon iconfont">&#xe65a;</i>　会员中心</dt>
          <dd  style="DISPLAY: none">
              <ul>
                  <a href="<?php echo U('Member/memberList');?>" target="main-frame"><li>会员列表</li></a>
                  <a href="<?php echo U('Member/shMember');?>" target="main-frame"><li>会员审核</li></a>
              </ul>
          </dd>
      </dl>

      <!--<dl>-->
          <!--<dt class="menu_title" data='0'><i class="icon iconfont">&#xe65a;</i>　库存转移</dt>-->
          <!--<dd  style="DISPLAY: none">-->
              <!--<ul>-->
                  <!--<a href="<?php echo U('Member/memberList');?>" target="main-frame"><li>会员列表</li></a>-->
                  <!--<a href="<?php echo U('Member/shMember');?>" target="main-frame"><li>会员审核</li></a>-->
              <!--</ul>-->
          <!--</dd>-->
      <!--</dl>-->

      <dl>
          <dt class="menu_title" data='0'><i class="icon iconfont">&#xe65a;</i>　库存管理</dt>
          <dd  style="DISPLAY: none">
              <ul>
                  <a href="<?php echo U('Order/orderList');?>" target="main-frame"><li>库存列表</li></a>
                  <a href="<?php echo U('Order/dailiList');?>" target="main-frame"><li>代理订单</li></a>
                  <a href="<?php echo U('Order/dailiKuCunList');?>" target="main-frame"><li>代理库存</li></a>
				  <a href="<?php echo U('Order/findWuliu');?>" target="main-frame"><li>物流查询</li></a>
				  <a href="<?php echo U('Order/tihuo');?>" target="main-frame"><li>商品提货</li></a>
              </ul>
			  
          </dd>
      </dl>

      <dl>
          <dt class="menu_title" data='0'><i class="icon iconfont">&#xe65a;</i>　资金信息</dt>
          <dd  style="DISPLAY: none">
              <ul>
                  <a href="<?php echo U('ZiJin/chongzhiList');?>" target="main-frame"><li>会员充值</li></a>
                  <a href="<?php echo U('ZiJin/shengjiList');?>" target="main-frame"><li>会员升级</li></a>
                  <a href="<?php echo U('ZiJin/jifenList');?>" target="main-frame"><li>货款记录</li></a>
                  <a href="<?php echo U('ZiJin/yongjinList');?>" target="main-frame"><li>佣金记录</li></a>
                  <!--<a href="<?php echo U('ZiJin/yongjinbili');?>" target="main-frame"><li>佣金比例</li></a>-->
                  <a href="<?php echo U('ZiJin/tixianList');?>" target="main-frame"><li>提现记录</li></a>
                  <a href="<?php echo U('ZiJin/tixianSq');?>" target="main-frame"><li>提现申请</li></a>
                  <a href="<?php echo U('ZiJin/syFanDian');?>" target="main-frame"><li>上月返点</li></a>
              </ul>
          </dd>
      </dl>

      <dl>
          <dt class="menu_title" data='0'><i class="icon iconfont">&#xe65a;</i>　前台展示</dt>
          <dd  style="DISPLAY: none">
              <ul>
                  <a href="<?php echo U('Message/gsjj');?>" target="main-frame"><li>公司简介</li></a>
                  <a href="<?php echo U('Message/kfzx');?>" target="main-frame"><li>客服咨询</li></a>
                  <a href="<?php echo U('Message/yjbh');?>" target="main-frame"><li>一键拨号</li></a>
                  <a href="<?php echo U('Message/cpjs');?>" target="main-frame"><li>产品介绍</li></a>
                  <a href="<?php echo U('Message/jrwm');?>" target="main-frame"><li>加入我们</li></a>
                  <a href="<?php echo U('Message/sck');?>" target="main-frame"><li>素材库</li></a>
              </ul>
          </dd>
      </dl>
      <dl>
          <dt class="menu_title" data='0'><i class="icon iconfont">&#xe65a;</i>　链接生成</dt>
          <dd  style="DISPLAY: none">
              <ul>
                  <a href="<?php echo U('Url/lianjie');?>" target="main-frame"><li>链接生成</li></a>
              </ul>
          </dd>
      </dl>
	

	
	
  </div>
  <div style="font-size:12px;color:#999;text-align:center;"></div>
</div>

	<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			  <h4 class="modal-title" id="myLargeModalLabel">系统通知</h4>
			</div>
			<div class="modal-body">
			  功能后期添加，请等待
			</div>
		  </div>
	  </div>
	</div>