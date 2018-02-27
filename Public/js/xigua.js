function xigua_dialog1(str1,str2,callback){
	var div = '<div id="dialog1" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;z-index: 99999999;"> <div class="mask" style="background:rgba(0,0,0,0.8);width: 100%;height: 100%;"></div> <div id="caishen-logo" style="position: absolute;width: 80%; top: 10%;left: 10%;border-radius: 1em; text-align: center;"> <img src="/Public/images/caishen.png" alt="" width="30%"> <div style="background: #fff;width:100%;min-height:50px;font-size: 1em;padding:0.5em 0 1.2em 0;border-top-left-radius: 2em;border-top-right-radius: 2em;"><div style="margin:10px;width:90%; word-wrap: break-word;color:#555;">'+str1+'</div></div> <div style="background:  #f3b30b;color: #555;text-align: center;border-bottom-left-radius: -2em;width:100%;" onclick="close_xiguadialog1('+callback+')" > <a href="javascript:;" style="font-size: 1em;line-height: 2.5em;">'+str2+'</a> </div> </div> </div>';
	$('body').append(div)
}
function close_xiguadialog1(func){

	$('#dialog1').animate({'opacity':'0'})
	setTimeout(function(){
		$('#dialog1').remove();
		if(typeof(func) == 'function' ){
			fuc()
		}
		
	},500)
	
}
function xigua_sheet(json){
	var div = '<div id="sheet"><div class="weui-mask" id="iosMask" style="opacity: 1;" onclick="xigua_sheet_close()"></div> <div class="weui-actionsheet weui-actionsheet_toggle" id="iosActionsheet" style="margin-bottom: -30px;opacity:0;background:#777"> <div class="weui-actionsheet__menu">';
	$.each(json,function(name,value) {
		div = div + '<a href="'+value+'"><div class="weui-actionsheet__cell" style="font-size: 16px;background:#333;color:#f3b30b">'+name+'</div></a>';
	});
	div = div + '</div> <div class="weui-actionsheet__action"> <div class="weui-actionsheet__cell" id="iosActionsheetCancel"  onclick="xigua_sheet_close()" style="font-size: 16px;background:#555;color:#f3b30b">取消</div> </div> </div> </div>';
	$('body').append(div);
	$('#iosActionsheet').animate({'margin-bottom':'0','opacity':'1'})
}
function xigua_sheet_close(){
	$('#sheet').animate({'opacity':'0'})
	setTimeout(function(){
		$('#sheet').remove();
	},500)
}

function xigua_ajax(surl,t_data,sfunc,efunc){
	xigua_load()
	$.ajax({
		type:'post',url: surl,dataType:'json',data:t_data,
		success:function(data){sfunc(data);xigua_load_close()},
		error:function(){efunc();xigua_load_close()}
	})
}
function xigua_load(){
	var _LoadingHtml = '<div id="loadingDiv" style="position:absolute;left:0;width:100%;height:' + _PageHeight + 'px;top:0;background:rgba(0,0,0,0.7);filter:alpha(opacity=80);z-index:10000;"><div class="loader"></div><div style="position: absolute;width:6em;top: calc(50% + 1.4em);left: calc(50% - 3em);color:#fff;font-size:14px;">正在努力加载</div></div>';
	$('body').append(_LoadingHtml);
}
function xigua_load_close(){
	$('#loadingDiv').remove();
}