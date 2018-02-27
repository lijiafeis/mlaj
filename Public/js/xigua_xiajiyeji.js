var page_load = '<div class="weui-loadmore load_more" data-page="1" data-state="1" style="display: block;"><i class="weui-loading"></i><span class="weui-loadmore__tips">正在加载</span></div>';
var page_none = '<tr ><td colspan="3" style="">没有更多了</td></tr>';
var flag = 0;
$(function(){
	if($(document).scrollTop() == 0 && $(document).height()  ==  $(window).height()){
		/*首次打开页面加载*/
		get_page()
	}
	$(window).scroll(function() {
                //$(document).scrollTop() 获取垂直滚动的距离、$(document).scrollLeft() 这是获取水平滚动条的距离
                if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
                	//$('body').append('下滑了')
                	get_page()
                }
            });
})
function get_page(){

	var load_more = scroll_data.parentId.find('.load_more');
	if(load_more.length == 0){
		scroll_data.parentId.append(page_load)
		load_more = scroll_data.parentId.find('.load_more')
	}
	// 判断上一个请求是否执行完
             var state = load_more.data('state')
             if(state == 0){return}else{load_more.data('state',0)}
             load_more.show()
             var page = load_more.data('page')
             $.post(scroll_data.url+"p="+page,scroll_data.t_data,function(data){
             	str = '';
				if(flag == 0){
                    str = '<tr><td>时间</td><td>详情</td><td>业绩</td></tr>';
                    flag = flag + 1;
				}else{

				}
             	for(var i = 0;i < data.length;i++){
					str += '<tr>';
					str += '<td>' + data[i]['time'] + '</td>'
					if(data[i]['type'] == 0){
                        str += '<td>购买商品</td>';
					}else{
                        str += '<td>后台充值</td>';
					}
					str += '<td style="color:blue;">' + data[i]['number'] + '</td>';

				}
             	load_more.hide().data('page',page*1+1).before(str)
                    	if(data == ''){
                    		load_more.before(page_none)
                    	}else{
                    		load_more.data('state',1)
                    	}
             })
}