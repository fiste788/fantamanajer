var url = "probabili_formazioni.html";
var clubs = $("#formazioni").data("clubs");
$.ajax({
    url: url,
    type: 'GET',
    dataType:'html',
    success:function(data,textStatus){
        if(textStatus === "success") {
            var formazioni = $(".matchFieldContainer",data);
            var row = null;
            formazioni.each(function (i,ele) {
                if(i % 2 === 0)
                    row = $('<div class="row">');
                var formazione = $(ele).clone();
                //formazione.addClass('');
                formazione.addClass('well formazione');
                formazione.find(".team-players-container,.matchDetails").addClass("row");
                formazione.find('.team').each(function(i,ele) {
                    var link = $(ele).find(".teamName a");
                    var nome = link.text().toLowerCase();
                    link.text('');
                    link.prepend('<h4 class="center">' + nome.toUpperCase() + '</h4>');
                    link.append('<img src="' + IMGSURL + 'clubs/' + clubs[nome]  + '.png">');
                    link.attr('href',FULLURL + 'dettaglioClub/' + clubs[nome]);
                });
                var campo = formazione.find(".team-players-container");
                campo.find(">li").addClass('col-lg-6 col-md-6');
                campo.find("ul").addClass("list-unstyled well");
                campo.addClass("list-unstyled");
                $(".title,.sub-title",formazione).addClass('center');
                campo.each(function(i,ele) {
                    var link = $(ele).find(".teamName a");
                    $(".title span",ele).remove();
                    
                });
                $(".container-altro ul",formazione).addClass("list-unstyled");
                $(".container-altro>ul",formazione).wrap('<div class="col-lg-6 col-md-6">').addClass(" well");
                $("#formazione_ora",formazione).addClass('center');
                $(".col1 ",formazione).hide();
                var col = $('<div class="col-lg-6 col-md-6">');
                col.append(formazione);
                row.append(col);
                $("#formazioni").append(row);
            });
        }
    }
});
