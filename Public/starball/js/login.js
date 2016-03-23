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

   $("body").click(function(e){
     if (e.target.nodeName == 'A' || e.target.nodeName == 'BUTTON' || e.target.offsetParent.id == 'loginWindow' || e.target.offsetParent.id == 'registerWindow')
             return;
         else{
             restoreMyAccountEvents();
         }
         
   });

function restoreMyAccountEvents(){
	$("#myAccountWindow").show();
	$('#myAccountWindow').removeAttr("style");
	$("#loginWindow").hide();
	$("#registerWindow").hide();	
}