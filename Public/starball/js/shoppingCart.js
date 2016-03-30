		function addToShoppingList(){
			alert(1111);
		    $.post('__URL__/addToShoppingList',{'itemId':$('#itemId').val()},function(data){
		    	alert(data.itemId);
		    },'json');
		}
		
		function addToFavoriteList(){
		    $.post('__URL__/addToFavoriteList',{'itemId':$('#itemId').val()},function(data){
		    	alert(data.itemId);
		    },'json');
		}