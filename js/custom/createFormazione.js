$(document).ready(function(){
    var campo = $("#campo")
    var modulo = campo.data("modulo");
    var edit = campo.data("edit");
    if(!$.isEmptyObject(modulo)) {
        var ruolo = 'P';
        var j = 0;
        var k = 0;
        var current = "";
        var list = $("#titolari-field").find("input");
        list.each(function (i,ele) {
            var current = $(ele);
            var id = current.val();
            if(id != "") {
                var gioc = $('.giocatore#' + id);
                var ruoloGioc = gioc.data('ruolo');
                if(ruoloGioc != ruolo) {
                    j++;
                    k = 0;
                }
                gioc.css('left',((((554-(82 * modulo[ruoloGioc])) / (modulo[ruoloGioc] + 1 )) * (k+1)) + ((k+1) * 82)-82) + 'px');
                gioc.css('top',((161 * j) + 23) + 'px');
                sobstitution(gioc);
                $("#campo .droppable#" + ruoloGioc).append(gioc);
                ruolo = ruoloGioc;
                k++;
            }
        });
        list = $("#panchina-field").find("input[value!='']");
        list.each(function (i,ele) {
            var current = $(ele);
            var id = current.val();
            if(id != "") {
                var giocOld = $('.giocatore#' + id);
                var gioc = giocOld.clone();
                giocOld.remove();
                sobstitution(gioc);
                $("#panchina .droppable#panch-" + i).append(gioc);
            }
        });
        list = $("#capitani-field").find("input[value!='']");
        list.each(function (i,ele) {
            var current = $(ele);
            var id = current.val();
            if(id != "") {
                var giocOld = $('.giocatore#' + id);
                var gioc = giocOld.clone();
                var div = $("#capitani .droppable[id='cap-" + current.attr('id') + "']");
                div.append(gioc);
                if(typeof(edit) != "undefined" && edit)
                    div.append('<a class="remove">Rimuovi</a>');
            }
        });
    }
    if(typeof(edit) != "undefined" && edit) {
        $(".remove").live("click",function () {
            var parent = $(this).parent();
            var id = $(parent).attr('id');
            var appo = id.split('-');
            var livello = appo[1];
            $(this).parent().empty();
            $("#capitani-field #"+livello).removeAttr("value");
        });
        $(".draggable").draggable({
            helper:"clone",
            opacity:0.5,
            revert:true,
            appendTo:"#stadio"
        });
        var data = new Array();
        data['P'] = 1;
        data['D'] = 5;
        data['C'] = 5;
        data['A'] = 3;
        $('#campo .droppable').droppable({
            accept: function(draggable) {
                var ruolo = $(this).attr('id');
                if($(draggable).data('ruolo') == ruolo) {
                    var n = $(this).find("div").length;
                    var nTot = $(this).parent().find("div.giocatore").length;
                    var nDif = $(this).parent().find("div.D").length;
                    var nCen = $(this).parent().find("div.C").length;
                    var nAtt = $(this).parent().find("div.A").length;
                    if(nDif <= 2) {
                        data['D'] = 5;
                        data['C'] = 5;
                        data['A'] = 3;
                        if(nCen == 4) {
                            if(nAtt == 3) {
                                data['D'] = 3;
                                data['C'] = 4;
                                data['A'] = 3;
                            }
                        }
                        if(nCen == 5) {
                            data['D'] = 3;
                            data['C'] = 5;
                            data['A'] = 2;

                        }
                    }
                    if(nDif == 3) {
                        data['D'] = 5;
                        data['C'] = 5;
                        data['A'] = 3;
                        if(nCen == 5) {
                            data['D'] = 4;
                            data['C'] = 5;
                            data['A'] = 2;
                            if(nAtt == 2) {
                                data['D'] = 3;
                                data['C'] = 5;
                                data['A'] = 2;
                            }

                        }
                        if(nAtt == 3) {
                            data['D'] = 4;
                            data['C'] = 4;
                            data['A'] = 3;
                            if(nCen == 4) {
                                data['D'] = 3;
                                data['C'] = 4;
                                data['A'] = 3;
                            }
                        }
                    }
                    if(nDif == 4) {
                        data['D'] = 5;
                        data['C'] = 5;
                        data['A'] = 3;
                        if(nCen == 5) {
                            data['D'] = 4;
                            data['C'] = 5;
                            data['A'] = 1;
                        } else {
                            if(nCen == 4) {
                                data['D'] = 5;
                                data['C'] = 5;
                                data['A'] = 2;
                                if(nAtt == 2) {
                                    data['D'] = 4;
                                    data['C'] = 4;
                                    data['A'] = 2;
                                }
                            } else {
                                if(nCen <= 3) {
                                    data['D'] = 5;
                                    data['C'] = 5;
                                    data['A'] = 3;
                                    if(nAtt == 3) {
                                        data['D'] = 4;
                                        data['C'] = 3;
                                        data['A'] = 3;
                                    }
                                }
                            }
                        }
                    }
                    if(nDif == 5) {
                        data['D'] = 5;
                        data['C'] = 4;
                        data['A'] = 2;
                        if(nCen == 4) {
                            data['D'] = 5;
                            data['C'] = 4;
                            data['A'] = 1;
                        } else {
                            if(nAtt == 2) {
                                data['D'] = 5;
                                data['C'] = 3;
                                data['A'] = 2;
                            }
                        }
                    }
                    var numMax = data[ruolo];
                    if(n < numMax && nTot < 11 && $(draggable).data('ruolo') == ruolo)
                        return true;
                }
                return false;
            },
            activeClass: 'droppable-active',
            hoverClass: 'droppable-hover',
            drop: function(ev,ui) {
                var gioc = ui.draggable
                gioc.css('top',ui.helper.css('top'));
                gioc.css('left',ui.helper.css('left'));
                ui.helper.remove();
                //sobstitution(gioc);
                $(this).append(gioc);
                reloadFields();
                checkCapitani();
            }
        });
        $('#giocatori').droppable({
            accept: function(draggable) {
                return $(this).find("#" + draggable.attr('id')).length == 0;
            },
            activeClass: 'droppable-active',
            hoverClass: 'droppable-hover',
            drop: function(ev,ui) {
                ui.helper.remove();
                ui.draggable.css('top',0);
                ui.draggable.css('left',0);
                $("#giocatori .ruoli." + ui.draggable.data('ruolo')).append(ui.draggable);
                reloadFields();
                checkCapitani();
            }
        });

        $('#panchina .droppable').droppable({
            accept: function(draggable) {
                return $(this).find(".giocatore").length == 0;
            },
            activeClass: 'droppable-active',
            hoverClass: 'droppable-hover',
            drop: function(ev,ui) {
                var gioc = ui.draggable
                ui.helper.remove();
                //sobstitution(gioc);
                $(this).append(gioc);
                reloadFields();
                checkCapitani();
            }
        });
        $('#capitani .droppable').droppable({
            accept: function(draggable) {
                if($(this).find("div").length == 0) {
                    var ruolo = $(draggable).data('ruolo');
                    if((ruolo == "P" || ruolo == "D") && isInCampo(draggable)) {
                        var exist = $("#capitani-field input[value=" + $(draggable).attr('id') + "]");
                        if(exist.length == 0)
                            return true;
                    }
                }
                return false;
            },
            activeClass: 'droppable-active',
            hoverClass: 'droppable-hover',
            drop: function(ev,ui) {
                var gioc = ui.draggable.clone();
                ui.helper.remove();
                $(this).empty();
                $(this).append(gioc);
                var list = $("#capitani-field").find("input");
                list.each(function (i) {
                    $(list[i]).removeAttr('value');
                });
                var lista = $("#capitani").find(".giocatore");
                lista.each(function (i,ele) {
                    var id = $(ele).parent().attr('id');
                    var appo = id.split('-');
                    $("input[name='" + appo[1] + "']").val($(ele).attr('id'));
                });
                $(this).append('<a class="remove">Rimuovi</a>');
            }
        });

    }
    function checkCapitani() {
        var list = $("#capitani").find(".giocatore");
        if(list.length > 0) {
            var listTitolari = $("#campo").find(".giocatore");
            list.each(function (i,ele) {
                var idCap = $(ele).attr('id');
                if($("#campo #" + idCap).length == 0) {
                    $(ele).parent().empty();
                    $("#capitani-field input[value=" + idCap + "]").removeAttr('value');
                }
            });
        }
        else
            $("#capitani-field input").removeAttr('value');
    }
    function reloadFields() {
        var list = $("#titolari-field").find("input");
        list.each(function (i,ele) {
            $(ele).removeAttr('value');
        });
        var lista = $("#campo").find(".giocatore");
        lista.each(function (i,ele) {
            $("input[name='titolari[" + i + "]']").attr('value',$(ele).attr('id'));
        });
        list = $("#panchina-field").find("input");
        list.each(function (i,ele) {
            $(ele).removeAttr('value');
        });
        lista = $("#panchina").find(".giocatore");
        lista.each(function (i,ele) {
            $("input[name='panchinari[" + i + "]']").attr('value',$(ele).attr('id'));
        });
        $(".draggable").draggable({
            helper:"clone",
            opacity:0.5,
            revert:true,
            appendTo:"#stadio"
        });
        $("#panchina .giocatore, #campo .giocatore").each(function(i,ele) {
            sobstitution(ele);
        });
    }
    function isInCampo(dom) {
        return $(dom).parents().filter("#campo").length > 0
    }
    function sobstitution(gioc) {
        $(gioc).droppable({
            greedy: true,
            accept: function(draggable) {
                return ($(draggable).hasClass("giocatore") && $(draggable).data('ruolo') == $(this).data('ruolo'))
            },
            activeClass: 'droppable-active',
            hoverClass: 'droppable-hover2',
            drop: function(ev,ui) {
                var inCampoGioc1 = isInCampo(ui.draggable);
                var inCampoGioc2 = isInCampo(this);
                var gioc1 = ui.draggable;
                var gioc2 = $(this).clone();

                if(inCampoGioc1) {
                    gioc2.css('left',gioc1.css('left'));
                    gioc2.css('top',gioc1.css('top'));
                }
                if(inCampoGioc2) {
                    ui.draggable.css('left',$(this).css('left'));
                    ui.draggable.css('top',$(this).css('top'));
                }

                /*ui.draggable.droppable({

									})*/
                gioc1.replaceWith(gioc2);
                $(this).replaceWith(ui.draggable);
                ui.helper.remove();
                reloadFields();
                checkCapitani();
            }
        });
    }

});
/*$("#giocatori").stickyPanel({
    topPadding: 84,
    afterDetachCSSClass: "top",
    savePanelSpace: true
});*/
