//获取浏览器页面可见高度和宽度
var _PageHeight = document.documentElement.clientHeight,
_PageWidth = document.documentElement.clientWidth;
//计算loading框距离顶部和左部的距离（loading框的宽度为215px，高度为61px）
var _LoadingTop = _PageHeight > 61 ? (_PageHeight - 61) / 2 : 0,
_LoadingLeft = _PageWidth > 215 ? (_PageWidth - 215) / 2 : 0;
//加载gif地址
var Loadimagerul="/Content/LoadJs/Image/loading.gif";
//在页面未加载完毕之前显示的loading Html自定义内容
var _LoadingHtml = '<div id="loadingDiv" style="position:absolute;left:0;width:100%;height:' + _PageHeight + 'px;top:0;background:#1F1F1F url(/Public/images/section1.jpg) no-repeat;background-size:100%;opacity:1;filter:alpha(opacity=80);z-index:10000;"><div class="loader"></div><div style="position: absolute;width:6em;top: calc(50% + 1.4em);left: calc(50% - 3em);color:#fff;font-size:14px;font-family: "Helvetica Neue","Hiragino Sans GB","Microsoft YaHei","\9ED1\4F53",Arial,sans-serif;">正在努力加载</div></div>';
//呈现loading效果
document.write(_LoadingHtml);
//监听加载状态改变
document.onreadystatechange = completeLoading;
//加载状态为complete时移除loading效果
function completeLoading() {
	if (document.readyState == "complete") {
	var loadingMask = document.getElementById('loadingDiv');
	loadingMask.parentNode.removeChild(loadingMask);
	}
}
