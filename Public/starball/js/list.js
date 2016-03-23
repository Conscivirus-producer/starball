$(document).ready(function(){
			var checkeds = new Array();
			if({:$categoriesChecked} != null){
				$("#category").addClass("am-active");
				$("#category dd").addClass("am-in");
			}
			if({:$gendersChecked} != null){
				$("#gender").addClass("am-active");
				$("#gender dd").addClass("am-in");
			}
			checkeds = checkeds.concat({:$categoriesChecked}).concat({:$gendersChecked});
			for(var i=0;i < checkeds.length;i++){
				$("#"+checkeds[i]).uCheck('check');
			}
		});
		$("input").click(function(){
			var url = "/Starball/List/showList"+"/by/"+"{$by}"+"/byValue/"+"{$byValue}";
			var categories = new Array();
			var genders = new Array();
			$("input.category").each(function(){
				if($(this).is(':checked')){
					categories.push($(this).attr("value"));
				}
			});
			if(categories.length != 0){
				url = url+"/categories/"+categories.toString();
			}
			$("input.gender").each(function(){
				if($(this).is(':checked')){
					genders.push($(this).attr("value"));
				}
			});
			if(genders.length != 0){
				url = url+"/genders/"+genders.toString();
			}
			window.location=(url);
		});