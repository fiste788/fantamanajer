var url = AJAXURL + "probabiliFormazioni.php";
$.ajax({
    url: url,
    type: 'GET',
    dataType:'html',
    success:function(data,textStatus){
        if(textStatus == "success") {
            var formazioni = $(".formazione",data);
            var row = null;
            formazioni.each(function (i,ele) {
                if(i % 2 == 0)
                    row = $('<div class="row-fluid">');
                var formazione = $(ele).clone();
                formazione.addClass('span6');
                formazione.addClass('well');
                $(".container-campo,.container-altro",formazione).addClass("row-fluid");
                var campo = $(".container-campo ul",formazione);
                campo.addClass("span6 no-dotted well");
                $(".title,.sub-title",formazione).addClass('center');
                $(".container-campo ul",formazione).each(function(i,ele) {
                    var link = $(".title a",ele);
                    $(".title span",ele).remove();
                    var nome = link.text();
                    link.text('');
                    link.prepend('<h4 class="center">' + nome.toUpperCase() + '</h4>');
                    link.append('<img src="' + IMGSURL + 'clubs/' + clubs[nome]  + '.png">');
                    link.attr('href',FULLURL + 'dettaglioClub/' + clubs[nome]);
                })
                $(".container-altro ul",formazione).addClass("no-dotted");
                $(".container-altro>ul",formazione).addClass("span6 well");
                $("#formazione_ora",formazione).addClass('center');
                $(".col1 ",formazione).hide();
                row.append(formazione);
                $("#formazioni").append(row);
            });
        }
    }
});
