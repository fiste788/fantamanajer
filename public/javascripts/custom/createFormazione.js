$(document).ready(function(){
    var $campo = $("#campo"),
		$panchina = $("#panchina"),
		$giocatori = $("#giocatori"),
		$capitani = $("#capitani"),
    	modulo = $campo.data("modulo"),
    	edit = $campo.data("edit");
        draggableOptions = {
            helper:"clone",
            opacity:0.5,
            revert:true,
            appendTo:"#campo"
        }
    if(!$.isEmptyObject(modulo)) {
        var ruolo = 'P',
        	j = 0,
        	k = 0,
        	current = "",
        	list = $("#titolari-field").find(":input");
        list.each(function (i,ele) {
            var id = $(ele).val();
            if(id !== "") {
                var gioc = $('.giocatore#' + id),
                	ruoloGioc = gioc.data('ruolo');
                if(ruoloGioc !== ruolo) {
                    j++;
                    k = 0;
                }
                gioc.css('left',((((554-(82 * modulo[ruoloGioc])) / (modulo[ruoloGioc] + 1 )) * (k+1)) + ((k+1) * 82)-82) + 'px');
                gioc.css('top',((161 * j) + 23) + 'px');
                sobstitution(gioc);
                $campo.find(".droppable#" + ruoloGioc).append(gioc);
                ruolo = ruoloGioc;
                k++;
            }
        });
        list = $("#panchina-field").find(":input");
        list.each(function (i,ele) {
            var id = $(ele).val();
            if(id !== "") {
                var giocOld = $('.giocatore#' + id),
                	gioc = giocOld.clone();
                giocOld.remove();
                sobstitution(gioc);
                $panchina.find(".droppable#panch-" + i).append(gioc);
            }
        });
        list = $("#capitani-field").find(":input");
        list.each(function (i,ele) {
            var current = $(ele),
            	id = current.val();
            if(id !== "") {
                var giocOld = $('.giocatore#' + id);
                var gioc = giocOld.clone();
                var appo = current.attr('id');
                appo = appo.substring(appo.indexOf('-') + 1);
                var div = $capitani.find(".droppable[id='" + appo + "']");
                div.append(gioc);
                gioc.removeClass("draggable");
                if(typeof(edit) !== undefined && edit)
                    div.append('<a class="remove">Rimuovi</a>');
            }
        });
        if(!$giocatori.data("hidden"))
            $giocatori.removeClass("hidden").addClass("in");
    }
    if(edit !== undefined && edit) {
        $(".remove").on("click",remove);
        $(".draggable").draggable(draggableOptions);
        var data = [];
        data['P'] = 1;
        data['D'] = 5;
        data['C'] = 5;
        data['A'] = 3;
        $campo.find('.droppable').droppable({
            accept: function(draggable) {
                var $this = $(this),
                    ruolo = $this.attr('id'),
                    draggableRuolo = $(draggable).data('ruolo');
                if(draggableRuolo === ruolo) {
                    var 
                        $parent = $this.parent(),
                        n = $this.find("div").length,
                    	nTot = $parent.find(".droppable div.giocatore").length,
                    	nDif = $parent.find(".droppable div.D").length,
                    	nCen = $parent.find(".droppable div.C").length,
                    	nAtt = $parent.find(".droppable div.A").length;
                    if(nDif <= 2) {
                        data['D'] = 5;
                        data['C'] = 5;
                        data['A'] = 3;
                        if(nCen === 4) {
                            if(nAtt === 3) {
                                data['D'] = 3;
                                data['C'] = 4;
                                data['A'] = 3;
                            }
                        }
                        if(nCen === 5) {
                            data['D'] = 3;
                            data['C'] = 5;
                            data['A'] = 2;

                        }
                    }
                    if(nDif === 3) {
                        data['D'] = 5;
                        data['C'] = 5;
                        data['A'] = 3;
                        if(nCen === 5) {
                            data['D'] = 4;
                            data['C'] = 5;
                            data['A'] = 2;
                            if(nAtt === 2) {
                                data['D'] = 3;
                                data['C'] = 5;
                                data['A'] = 2;
                            }

                        }
                        if(nAtt === 3) {
                            data['D'] = 4;
                            data['C'] = 4;
                            data['A'] = 3;
                            if(nCen === 4) {
                                data['D'] = 3;
                                data['C'] = 4;
                                data['A'] = 3;
                            }
                        }
                    }
                    if(nDif === 4) {
                        data['D'] = 5;
                        data['C'] = 5;
                        data['A'] = 3;
                        if(nCen === 5) {
                            data['D'] = 4;
                            data['C'] = 5;
                            data['A'] = 1;
                        } else {
                            if(nCen === 4) {
                                data['D'] = 5;
                                data['C'] = 5;
                                data['A'] = 2;
                                if(nAtt === 2) {
                                    data['D'] = 4;
                                    data['C'] = 4;
                                    data['A'] = 2;
                                }
                            } else {
                                if(nCen <= 3) {
                                    data['D'] = 5;
                                    data['C'] = 5;
                                    data['A'] = 3;
                                    if(nAtt === 3) {
                                        data['D'] = 4;
                                        data['C'] = 3;
                                        data['A'] = 3;
                                    }
                                }
                            }
                        }
                    }
                    if(nDif === 5) {
                        data['D'] = 5;
                        data['C'] = 4;
                        data['A'] = 2;
                        if(nCen === 4) {
                            data['D'] = 5;
                            data['C'] = 4;
                            data['A'] = 1;
                        } else {
                            if(nAtt === 2) {
                                data['D'] = 5;
                                data['C'] = 3;
                                data['A'] = 2;
                            }
                        }
                    }
                    var numMax = data[ruolo];
                    return (n < numMax && nTot < 11 && draggableRuolo === ruolo);
                }
                return false;
            },
            activeClass: 'droppable-active',
            hoverClass: 'droppable-hover',
            drop: function(ev,ui) {
                var gioc = ui.draggable;
                gioc.css('top',ui.helper.css('top'));
                gioc.css('left',ui.helper.css('left'));
                ui.helper.remove();
                gioc.draggable(draggableOptions);
                sobstitution(gioc);
                $(this).append(gioc);
                reloadFields();
                checkCapitani();
            }
        });
        $giocatori.droppable({
            accept: function(draggable) {
                return $(this).find("#" + draggable.attr('id')).length === 0;
            },
            activeClass: 'droppable-active',
            hoverClass: 'droppable-hover',
            drop: function(ev,ui) {
                ui.helper.remove();
                ui.draggable.css('top',0);
                ui.draggable.css('left',0);
                ui.draggable.draggable(draggableOptions);
                try {
                    ui.draggable.droppable("destroy");
                } catch(e) {}
                $giocatori.find(".ruoli." + ui.draggable.data('ruolo')).append(ui.draggable);
                reloadFields();
                checkCapitani();
            }
        });

        $panchina.find('.droppable').droppable({
            accept: function(draggable) {
                return $(this).find(".giocatore").length === 0;
            },
            activeClass: 'droppable-active',
            hoverClass: 'droppable-hover',
            drop: function(ev,ui) {
                var gioc = ui.draggable;
                ui.helper.remove();
                gioc.draggable(draggableOptions);
                sobstitution(gioc);
                $(this).append(gioc);
                reloadFields();
                checkCapitani();
            }
        });
        $capitani.find('.droppable').droppable({
            accept: function(draggable) {
                var $this = $(this);
                if($this.find("div").length === 0) {
                    var $draggable = $(draggable),
                        ruolo = $draggable.data('ruolo');
                    if((ruolo === "P" || ruolo === "D") && isInCampo(draggable)) {
                        var exist = false;
                        var id = $draggable.attr('id');
                        $("#capitani-field").find(":input").each(function() {
                            if($(this).val() === id)
                                exist = true;
                        });
                        return !exist;
                    }
                }
                return false;
            },
            activeClass: 'droppable-active',
            hoverClass: 'droppable-hover',
            drop: function(ev,ui) {
                var $this = $(this),
                    $gioc = ui.draggable.clone();
                ui.helper.remove();
                $this.empty();
                $this.append($gioc);
                $gioc.removeClass("draggable");
                try {
                    $gioc.draggable("destroy");
                } catch(e) {}
                var list = $("#capitani-field").find(":input");
                list.each(function (i) {
                    $(list[i]).val('');
                });
                var lista = $capitani.find(".giocatore");
                lista.each(function (i,ele) {
                    var id = $(ele).parent().attr('id');
                    $(":input[id='capField-" + id + "']").val($(ele).attr('id'));
                });
                $this.append($('<a class="remove">Rimuovi</a>').on('click',remove));
            }
        });

    } else {
        $(".inner-page :input").prop("disabled",true);
    }
    function checkCapitani() {
        var list = $capitani.find(".giocatore");
        if(list.length > 0) {
            list.each(function (i,ele) {
                var idCap = $(ele).attr('id');
                if($campo.find("#" + idCap).length === 0) {
                    $(ele).parent().empty();
                    $("#capitani-field").find(":input").each(function() {
                        if($(this).val() === idCap)
                            $(this).val('');
                    });
                }
            });
        }
        else
            $("#capitani-field").find("input").val('');
    }
    function reloadFields() {
        
        //var mod = {'P':modulo[0],'D':modulo[1],'C':modulo[2],'A':modulo[3]};
        var $fieldContainer = $("#titolari-field");
        $fieldContainer.find(":input").remove();
        var mod = [];
        mod['P'] = $campo.find(".giocatore.P").length;
        mod['D'] = $campo.find(".giocatore.D").length;
        mod['C'] = $campo.find(".giocatore.C").length;
        mod['A'] = $campo.find(".giocatore.A").length;
        var j = 0;
        for(ruolo in mod) {
            for(i = 0; i < mod[ruolo];i++) {
                var group = $("#cloni").find("."+ ruolo).find(".form-group");
                var clone = group.clone();
                clone.find(":input").attr("id","gioc-" + j).attr("name","titolari[" + j + "]");
                $fieldContainer.find("div." + ruolo).append(clone);
                j++;
            }
        };
        var lista = $campo.find(".giocatore");
        lista.each(function (i,ele) {
            $(":input[name='titolari[" + i + "]']").val($(ele).attr('id'));
        });
        list = $("#panchina-field").find(":input");
        list.each(function (i,ele) {
            $(ele).val('');
        });
        lista = $panchina.find(".giocatore");
        lista.each(function (i,ele) {
            $(":input[name='panchinari[" + i + "]']").val($(ele).attr('id'));
        });
        /*
        $(".draggable").draggable({
            helper:"clone",
            opacity:0.5,
            revert:true,
            appendTo:"#campo"
        });
        $("#panchina .giocatore, #campo .giocatore").each(function(i,ele) {
            sobstitution(ele);
        });*/
    }
    function isInCampo(dom) {
        return $(dom).parents().filter("#campo").length > 0;
    }
    function sobstitution(gioc) {
        $(gioc).droppable({
            greedy: true,
            accept: function(draggable) {
                return ($(draggable).hasClass("giocatore") && $(draggable).data('ruolo') === $(this).data('ruolo'));
            },
            activeClass: 'droppable-active',
            hoverClass: 'droppable-hover2',
            drop: function(ev,ui) {
                var $this = $(this),
                    inCampoGioc1 = isInCampo(ui.draggable),
                	inCampoGioc2 = isInCampo(this),
                	gioc1 = ui.draggable,
                	gioc2 = $this.clone();

                if(inCampoGioc1) {
                    gioc2.css('left',gioc1.css('left'));
                    gioc2.css('top',gioc1.css('top'));
                }
                if(inCampoGioc2) {
                    ui.draggable.css('left',$this.css('left'));
                    ui.draggable.css('top',$this.css('top'));
                }

                /*ui.draggable.droppable({

									})*/
                gioc1.replaceWith(gioc2);
                $this.replaceWith(ui.draggable);
                if(inCampoGioc1)
                    sobstitution(gioc2);
                if(inCampoGioc2)
                    sobstitution(ui.draggable);
                
                gioc2.draggable(draggableOptions);
                ui.draggable.draggable(draggableOptions);
                ui.helper.remove();
                reloadFields();
                checkCapitani();
            }
        });
    }
    function remove() {
        var $parent = $(this).parent(),
            id = $parent.attr('id');
        $(this).remove();
        $parent.children().first().fadeOut(function(){   
            $(this).remove();
        });
        $("#capitani-field").find("#capField-" + id).val("");
    }
});
/*$("#giocatori").stickyPanel({
    topPadding: 84,
    afterDetachCSSClass: "top",
    savePanelSpace: true
});*/
