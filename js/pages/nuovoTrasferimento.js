var selectLega = $("select[name='lega']");
var selectSquadra = $("select[name='squadra']");
selectLega.change(function () {
	var id = $("option:selected",this).attr('value');
	$.getJSON(url + "squadre.php?idLega=" + id, 
		function(data,textStatus){ 
			if(textStatus = "success") { 
				selectSquadra.empty();
				selectSquadra.removeAttr("disabled");
				selectSquadra.append("<option></option>");
				$.each(data, function(i,item){ 
					selectSquadra.append('<option value="' + item.id + '">' + item.nomeSquadra + '</option>');
				}); 
			}
		});
	});
