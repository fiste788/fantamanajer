$("#elimina").click(function () {
	$("#dialog").dialog({
		resizable: false,
		height:140,
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
		buttons: {
			'Elimina squadra': function() {
				$(".div-submit").append('<input style="display:none;" id="eliminaConf" type="hidden" name="button" class="submit dark" value="<?php if(isset($button)) echo $button; ?>" />');
				$("#creaSq").submit();
				$(this).dialog('close');
			},
			Annulla: function() {
				$(this).dialog('close');
			}
		}	
	});
});