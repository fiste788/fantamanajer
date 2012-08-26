/*$("select[name='modulo']").change(function() {
	var modulo = $(this).val();
	if(modulo != '') {
		var modArray = modulo.split('-');
		$(modArray).each(function (i,ele) {
			var cont = $($(".ruolo")[i]);
			var giocatore = $(".giocatore",cont).first();
			var giocatori = $(".giocatori",cont);
			giocatori.empty();
			for(i = 0;i < ele;i++) {
				giocatori.append(giocatore.clone());
			}
		});
	}
});*/
$(document).ready(function() {
	loadCapitani();
});
$(".ruolo#P select,.ruolo#D select").change(function() {
	loadCapitani();
})
function loadCapitani() {
	var selects = $("#capitani select");
	selects.empty();
	$(".ruolo#P select,.ruolo#D select").each(function(i,ele) {
		var opt = $("option:selected",ele).clone();
		opt.removeAttr('selected');
		selects.append(opt);
	});
	$(selects).each(function(i2,ele2) {
		var appo = $("option[value='" + $(ele2).data('oldvalue') + "']",ele2);
		if(appo.length)
			appo.attr('selected','selected');
		else {
			//alert('Un capitano è stato tolto');
			$(ele2).prepend('<option selected="selected"><option>');
		}
	});
}