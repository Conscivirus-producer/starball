$("#loginBtn").click(function(){
	$("#myAccountWindow").hide();
	$("#loginWindow").show();
	$('#loginWindow').removeAttr("style");
});

$("#registerLnkFromLogin").click(function(){
	$("#loginWindow").hide();
	$("#registerWindow").show();
	$('#registerWindow').removeAttr("style");
});
$("#registerLnk").click(function(){
	$("#myAccountWindow").hide();
	$("#registerWindow").show();
	$('#registerWindow').removeAttr("style");
});
$("#myAccountLnk").click(function(){
	restoreMyAccountEvents();
});
//控制点击body的其它部分,登录框应该被隐藏
$("body").click(function(e){
	 if (e.target.nodeName == 'A' 
	 || e.target.nodeName == 'BUTTON' 
	 || e.target.offsetParent.id == 'loginWindow' 
	 || e.target.offsetParent.id == 'registerWindow'
	 || e.target.id == 'rememberMe'){
	     return;
	 }else{
	    restoreMyAccountEvents();
	 }
});

function restoreMyAccountEvents(){
	$("#myAccountWindow").show();
	$('#myAccountWindow').removeAttr("style");
	$("#loginWindow").hide();
	$("#registerWindow").hide();	
}