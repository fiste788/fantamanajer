var url = "probabili_formazioni.html";
var clubs = $("#formazioni").data("clubs");
$.ajax({
    url: url,
    type: 'GET',
    dataType:'html',
    success:function(data,textStatus){
        if(textStatus === "success") {
            var formazioni = $(".formazione",data);
            var row = null;
            formazioni.each(function (i,ele) {
                if(i % 2 === 0)
                    row = $('<div class="row">');
                var formazione = $(ele).clone();
                //formazione.addClass('');
                formazione.addClass('well');
                $(".container-campo,.container-altro",formazione).addClass("row");
                var campo = $(".container-campo ul",formazione);
                campo.wrap('<div class="col-lg-6 col-md-6">')
                campo.addClass("list-unstyled well");
                $(".title,.sub-title",formazione).addClass('center');
                $(".container-campo ul",formazione).each(function(i,ele) {
                    var link = $(".title a",ele);
                    $(".title span",ele).remove();
                    var nome = link.text().toLowerCase();
                    link.text('');
                    link.prepend('<h4 class="center">' + nome.toUpperCase() + '</h4>');
                    link.append('<img src="' + IMGSURL + 'clubs/' + clubs[nome]  + '.png">');
                    link.attr('href',FULLURL + 'dettaglioClub/' + clubs[nome]);
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
