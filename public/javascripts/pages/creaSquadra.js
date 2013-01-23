if(flagCancella) {
	$("#elimina").click(function () {
		$("#dialog").dialog({
			resizable: false,
			height:140,
			modal: true,
			position: 'center',
			buttons: {
				'Elimina squadra': function() {
					$(".div-submit").append('<input style="display:none;" id="eliminaConf" type="hidden" name="button" class="submit dark" value="' + button + '" />');
					$("#creaSq").submit();
					$(this).dialog('close');
				},
				Annulla: function() {
					$(this).dialog('close');
				}
			}	
		});
	});
}