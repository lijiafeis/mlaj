<?php if (!defined('THINK_PATH')) exit();?><link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/Public/bootstrap/css/font-awesome.min.css">
<link rel="stylesheet" href="/Public/admin/css/base.css">
<script> 
		function setChange() { 
			if (document.f.type.value == "click") { 
				document.all.tb1.style.display = "block"; 
			}else { 
				document.all.tb1.style.display = "none"; 
			} 
			if (document.f.type.value == "view") { 
				document.all.tb2.style.display = "block"; 
			} else { 
				document.all.tb2.style.display = "none"; 
			} 
		}
		function ddd() {
			document.getElementById("select1").value = "<?php echo ($info[0][type]); ?>";
			if(document.f.type.value == "click") {
				document.all.tb1.style.display = "block"; 
			}
			if (document.f.type.value == "view") { 
				document.all.tb2.style.display = "block"; 
			}

		}
	
	</script> 
<body  onload="ddd()">
<div class="container">
<form class="form-horizontal" action="/Admin/Base/menuedit.html?menu_id=5" method="post" name="f" onsubmit="return check()">
			  <div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label">菜单名称</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="" name="title" value="<?php echo ($info[0]['title']); ?>" placeholder="">
				</div>
			  </div>
			  <div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label">父级菜单</label>
				<div class="col-sm-7">
					<select name="pid" id="pid" style="height:25px;width:200px;padding-left:10px" >
						<?php if($pidtitle[0]['title'] == '' ): ?><option value="0" selected>设为一级菜单</option>						
						<?php else: ?>
							<option value="0">设为一级菜单</option>
							<option value="<?php echo ($pidtitle[0]['id']); ?>" selected><?php echo ($pidtitle[0]['title']); ?></option><?php endif; ?>
						
						
						<?php if(is_array($pinfo)): $i = 0; $__LIST__ = $pinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vv["id"]); ?>"><?php echo ($vv["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</div>
			  </div>
			  <div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label">菜单类型</label>
				<div class="col-sm-7">
				  <select name="type" onchange="setChange()"  style="height:25px;width:200px;padding-left:10px" id="select1">
							<option value="">请选择菜单类型</option>
							<option value="click">关键词回复</option>
							<option value="view">URL跳转链接</option>
							<option value="scancode_push">扫一扫</option>
							<option value="scancode_waitmsg">扫一扫</option>
							<option value="pic_sysphoto">拍照发图</option>
							<option value="pic_photo_or_album">从相册或相机发图</option>
							<option value="pic_weixin">相册发图</option>
							<option value="location_select">发送地理位置</option>
						</select>
				</div>
			  </div>
			  <div class="form-group" id="tb1" style="display:none;color:red">
				<label for="inputEmail3" class="col-sm-3 control-label">关联关键词</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="" name="keyword" value="<?php echo ($info[0]['keyword']); ?>" placeholder="">
				</div>
			  </div>
			  <div class="form-group" id="tb2" style="display:none;color:red">
				<label for="inputEmail3" class="col-sm-3 control-label">外链地址</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="" name="url" value="<?php echo ($info[0]['url']); ?>" placeholder="">
				</div>
			  </div>
			  <div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label">显示</label>
				<div class="col-sm-7">
					<input type="radio"  name="is_show" value="1" <?php if($info[0][is_show] == 1): ?>checked<?php endif; ?> /><span style="font-weight:normal">显示</span>
					<input type="radio" name="is_show" value="0" <?php if($info[0][is_show] == 0): ?>checked<?php endif; ?> /><span style="font-weight:normal">隐藏</span>
				</div>
			  </div>
			  <div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label">排序</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="" name="code" value="<?php echo ($info[0]['code']); ?>" placeholder="">
				</div>
			  </div>
			   <input type="hidden" class="form-control" id="" name="id" value="<?php echo ($info[0]['id']); ?>" placeholder="">
			  <div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				  <button type="submit" class="btn btn-default">确认保存</button>
				</div>
			  </div>
			</form>
</div>
</body>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script>	 
	function check(){
		 var pid = $("#pid").val();
		 if((pid == 0) && (<?php echo ($pinfonum); ?> == 3) ){
			
			if(<?php echo ($info[0]['pid']); ?> != 0){
				alert("一级菜单已经三个！");
				return false;
			}
			
		 }
	}
</script>