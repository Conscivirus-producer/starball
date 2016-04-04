var timeOut;
$(function() {
	//如果是从添加到收藏夹/购物车刷新的页面，则需要弹开相应的窗口
	var channel = $('#channel').val();
	if(channel == 'addSL'){
		$("#myShoppingListLnk").click();
	}else if(channel = 'addFL'){
		//TBC	
	}
	var shoppingListItems = JSON.parse(decodeURIComponent($('#shoppingListItems').val()));
  $('.am-slider').flexslider({
    // options
    smoothHeight: false
  });
});
$("a.yu-menu").mouseenter(function(){
	$("div.yu-sub-menu").not("#"+$(this).attr("id")).fadeOut();
	$(this).css("border-bottom", "4px solid black");
	$("div.yu-sub-menu"+"."+$(this).attr("id")).fadeIn();
	$("div.yu-sub-menu").css("height", $("div.am-viewport").css('height'));
});
$("a.yu-menu").mouseleave(function(){
	var element = $(this).attr("id");
	$(this).css("border-bottom", "");
	timeOut = setTimeout(function(){
		$("div.yu-sub-menu"+"."+element).fadeOut("");
	}, 150);
});
$("div.yu-sub-menu.am-u-md-12").mouseenter(function(){
	clearTimeout(timeOut);
});
$("div.yu-sub-menu.am-u-md-12").mouseleave(function(){
	$(this).fadeOut();
});
