$(document).ready(function() {
    var url = "probabili_formazioni.html",
        $giocatori = $(".giocatore");
    $.ajax({
        url: url,
        type: 'GET',
        dataType:'html',
        success: function(data,textStatus){
            if(textStatus === "success") {
                var formazioni = $(".bodycontent",data);
                $giocatori.each(function() {
                    var $this = $(this),
                        cognome = $this.data('cognome');
                    if(formazioni.find(".formazione li:contains(" + cognome.toUpperCase() + "):not(.bottom)").length)
                        $this.addClass('titolare');
                    else
                        $this.addClass('no-titolare');
                });
            }
        }
    });
});