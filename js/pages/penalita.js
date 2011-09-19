$("#legaSelect").change(function () {
	var id = $("#legaSelect option:selected").attr('value');
	$.getJSON("<?php echo FULLURL; ?>code/ajax/squadre.php?idLega=" + id,
	function(data){
		$("#squadraSelect").empty();
		$("#squadraSelect").removeAttr("disabled");
		$("#squadraSelect").append("<option></option>");
		$.each(data, function(i,item){
			$("#squadraSelect").append('<option value="' + i + '">' + item + '</option>');
		});
	});
});