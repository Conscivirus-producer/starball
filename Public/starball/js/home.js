var timeOut;
$(function() {
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

$("#myShoppingCart").click(function(){
	window.location.href="/Starball/Cart/index";
	
});
