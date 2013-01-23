$("#legaSelect").change(function () {
	var id = $("#legaSelect option:selected").attr('value');
	$.getJSON(url + "squadre.php?idLega=" + id,
		function(data,textStatus){
			if(textStatus == "success") {
				$("#squadraSelect").empty();
				$("#squadraSelect").removeAttr("disabled");
				$("#uniform-squadraSelect").removeClass("disabled");
				$("#squadraSelect").append("<option></option>");
				$.each(data, function(i,item){
					$("#squadraSelect").append('<option value="' + i + '">' + item + '</option>');
				});
			}
		});
	});
