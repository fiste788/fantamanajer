$(".radio :input").change(function () {
	$.getJSON(AJAXURL + "download.php?type=" + this.value,
		function(data,textStatus){
			if(textStatus == "success") {
				$("#giornataSelect").empty();
				$("#giornataSelect").removeAttr("disabled");
				$("#giornataSelect").append("<option></option>");
				$("#giornataSelect").append('<option value="all">Tutte le giornate</option>');
				$.each(data, function(i,item){
					$("#giornataSelect").append('<option value="' + item + '">' + item + '</option>');
				});
			}
		});
	});
