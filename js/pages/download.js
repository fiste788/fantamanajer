$(".radio").change(function () { 
	$.getJSON(url + "download.php?type=" + this.value, 
		function(data,textStatus){ 
			if(textStatus = "success") { 
				$("#giornataSelect").empty(); 
				$("#giornataSelect").removeAttr("disabled"); 
				$("#uniform-giornataSelect").removeClass("disabled");  
				$("#giornataSelect").append("<option></option>");
				$("#giornataSelect").append('<option value="all">Tutte le giornate</option>'); 
				$.each(data, function(i,item){ 
					$("#giornataSelect").append('<option value="' + item + '">' + item + '</option>'); 
				}); 
			}
		});
	});
