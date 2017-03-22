$(document).ready(function() {
    var $module = $("select[name=module]").change(function(ev) {
        var selected = $(this).val();
        var $fieldContainer = $("#regular-field");
        var module = selected.split('-');
        var mod = {'P':module[0],'D':module[1],'C':module[2],'A':module[3]};
        for(role in mod) {
            var select = $fieldContainer.find("." + role).find("select");
            if(select.length != mod[role]) {
                if(select.length > mod[role]) {
                    for(var i = 0; i < (select.length - mod[role]);i++) {
                        select.last().remove();
                    }
                } else {
                    select.parent().parent().append(select.first().clone().val(""));
                }
            }
        };
        
    });
    var url = FULLURL + "probabili_formazioni.html",
        $giocatori = $(".player");
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