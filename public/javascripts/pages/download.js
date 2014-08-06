$(".radio :input").change(function () {
	$.getJSON(AJAXURL + "download.php?type=" + this.value, function(data,textStatus){
		if(textStatus == "success") {
			var select = $("#giornataSelect");
			select.empty();
			select.removeAttr("disabled");
			select.append("<option></option>");
			select.append('<option value="all">Tutte le giornate</option>');
			$.each(data, function(i,item){
				select.append('<option value="' + item + '">' + item + '</option>');
			});
		}
	});
});
