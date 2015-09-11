$(document).ready(function() {
    var $modulo = $("select[name=modulo]").change(function(ev) {
        var selected = $(this).val();
        var $fieldContainer = $("#titolari-field");
        var modulo = selected.split('-');
        var mod = {'P':modulo[0],'D':modulo[1],'C':modulo[2],'A':modulo[3]};
        for(ruolo in mod) {
            var select = $fieldContainer.find("." + ruolo).find("select");
            if(select.length != mod[ruolo]) {
                if(select.length > mod[ruolo]) {
                    for(var i = 0; i < (select.length - mod[ruolo]);i++) {
                        select.last().remove();
                    }
                } else {
                    select.parent().parent().append(select.first().clone().val(""));
                }
            }
        };
        
    });
    var url = FULLURL + "probabili_formazioni.html",
        $giocatori = $(".giocatore");
    $.ajax({
        url: url,
        type: 'GET',
        dataType:'html',
        success: function(data,textStatus){
            if(textStatus === "success") {
                var formazioni = $(".probabiliFormazioni",data);
                $giocatori.each(function() {
                    var $this = $(this),
                        cognome = $this.data('cognome');
                    if(formazioni.find(".team-players li:contains(" + cognome.toUpperCase() + "):not(.bottom)").length)
                        $this.addClass('titolare');
                    else
                        $this.addClass('no-titolare');
                });
            }
        }
    });
});