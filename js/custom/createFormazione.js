$(document).ready(function(){
/*	$("#giocatori").stickyPanel({
		topPadding: 82,
		afterDetachCSSClass: "top",
		savePanelSpace: true
	});*/
	$("#giocatori .draggable").each(function () {
		ruolo = $(this).parent().attr("class");
		$(this).data('ruolo',ruolo);
	});
				if(typeof(modulo) != "undefined")
				{
					var ruolo = 'P';
					var j = 0;
					var k = 0;
					var title = "";
					var current = "";
					var list = $("#titolari-field").find("input");
					list.each(function (i) {
						var current = $(list[i]);
						$('.giocatore[data-player-id="' + current.val() + '"]').hide();
						var ruoloGioc = current.data('ruolo');
						var nomeGioc = current.data('nome');
						if(ruoloGioc != ruolo) {
							j++;
							k = 0;
						}
						if(current.data('has-image'))
						    var gioc = $('<div style="position:absolute;left:' + ((((554-(83 * modulo[ruoloGioc])) / (modulo[ruoloGioc] + 1 )) * (k+1)) + ((k+1) * 83)-83) + 'px;top:' + ((140 * j) + 65) + 'px" class="embed giocatore draggable ui-draggable '+ ruoloGioc +'" data-player-id="'+ current.val() +'" data-ruolo="'+ ruoloGioc +'" ><img width="80" src="' + imgsUrl + current.attr('value') + '.jpg" /><p>' + nomeGioc + '</p></div>');
						else
							var gioc = $('<div style="position:absolute;left:' + ((((554-(83 * modulo[ruoloGioc])) / (modulo[ruoloGioc] + 1 )) * (k+1)) + ((k+1) * 83)-83) + 'px;top:' + ((140 * j) + 65) + 'px" class="embed giocatore draggable ui-draggable '+ ruoloGioc +'" data-player-id="'+ current.val() +'" data-ruolo="'+ ruoloGioc +'" ><p>' + nomeGioc + '</p></div>');
						sobstitution(gioc);
                          $("#campo div.droppable[id=" +ruoloGioc+"]").append(gioc);
						ruolo = ruoloGioc;
						k++;
					});
					list = $("#panchina-field").find("input[value!='']");
					list.each(function (i) {
						var current = $(list[i]);
						$('.giocatore[data-player-id="' + current.val() + '"]').hide();
						var ruoloGioc = current.data('ruolo');
						var nomeGioc = current.data('nome');
						if(current.data('has-image'))
							var gioc = $('<div class="embed giocatore draggable ui-draggable '+ ruoloGioc +'" data-player-id="'+ current.val() +'" data-ruolo="'+ ruoloGioc +'" ><img width="80" src="' + imgsUrl + current.val() + '.jpg" /><p>' + nomeGioc + '</p></div>');
						else
							var gioc = $('<div class="embed giocatore draggable ui-draggable '+ ruoloGioc +'" data-player-id="'+ current.val() +'" data-ruolo="'+ ruoloGioc +'" ><p>' + nomeGioc + '</p></div>');
                        sobstitution(gioc);
                        $("#panchina .droppable[id='panch-" + i + "']").append(gioc);
					});
					list = $("#capitani-field").find("input[value!='']");
					list.each(function (i) {
						var current = $(list[i]);
						var ruoloGioc = current.data('ruolo');
						var nomeGioc = current.data('nome');
						if(current.data('has-image'))
							$("#capitani .droppable[id='cap-" + current.attr('id') + "']").append('<div class="embed giocatore '+ ruoloGioc +'" data-player-id="'+ $(list[i]).val() +'" data-ruolo="'+ ruoloGioc +'" /><img width="80" src="' + imgsUrl + $(list[i]).val() + '.jpg" /><p>' + nomeGioc + '</p></div>');
						else
							$("#capitani .droppable[id='cap-" + current.attr('id') + "']").append('<div class="embed giocatore '+ ruoloGioc +'" data-player-id="'+ $(list[i]).val() +'" data-ruolo="'+ ruoloGioc +'" /><p>' + nomeGioc + '</p></div>');
						if(typeof(edit) != "undefined" && edit)
							$("#capitani .droppable[id='cap-" + current.attr('id') + "']").append('<a class="remove">Rimuovi</a>');
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
					helper:"clone",opacity:0.5,revert:true
				});
				var data = new Array();
				data['PP'] = 1;
				data['DD'] = 5;
				data['CC'] = 5;
				data['AA'] = 3;
			$('#campo .droppable').droppable({
				accept: function(draggable) {
					var ruolo = $(this).attr('id');
					if($(draggable).data('ruolo') == ruolo) {
					var nPor = 0;
					var nDif = 0;
					var nCen = 0;
					var nAtt = 0;
					var nTot = 0;
					var n = 0;

					$(this).find("div").each(function () {
						n++;
					});
					$(this).parent().find("div.embed").each(function () {
						nTot++;
					});
					$(this).parent().find("div.P").each(function () {
						nPor++;
					});
					$(this).parent().find("div.D").each(function () {
						nDif++;
					});
					$(this).parent().find("div.C").each(function () {
						nCen++;
					});
					$(this).parent().find("div.A").each(function () {
						nAtt++;
					});
						if(nDif <= 2)
						{
							data['D'] = 5;
							data['C'] = 5;
							data['A'] = 3;
							if(nCen == 4)
							{
								if(nAtt == 3)
								{
									data['D'] = 3;
									data['C'] = 4;
									data['A'] = 3;
								}

							}
							if(nCen == 5)
							{
								data['D'] = 3;
								data['C'] = 5;
								data['A'] = 2;

							}
						}
						if(nDif == 3)
						{
							data['D'] = 5;
							data['C'] = 5;
							data['A'] = 3;
							if(nCen == 5)
							{
								data['D'] = 4;
								data['C'] = 5;
								data['A'] = 2;
								if(nAtt == 2)
								{
									data['D'] = 3;
									data['C'] = 5;
									data['A'] = 2;
								}

							}
							if(nAtt == 3)
							{
								data['D'] = 4;
								data['C'] = 4;
								data['A'] = 3;
								if(nCen == 4)
								{
									data['D'] = 3;
									data['C'] = 4;
									data['A'] = 3;
								}
							}
						}
						if(nDif == 4)
						{
							data['D'] = 5;
							data['C'] = 5;
							data['A'] = 3;
							if(nCen == 5)
							{
								data['D'] = 4;
								data['C'] = 5;
								data['A'] = 1;
							}
							else
							{
								if(nCen == 4)
								{
									data['D'] = 5;
									data['C'] = 5;
									data['A'] = 2;
									if(nAtt == 2)
									{
										data['D'] = 4;
										data['C'] = 4;
										data['A'] = 2;
									}
								}
								else
								{
									if(nCen <= 3)
									{
										data['D'] = 5;
										data['C'] = 5;
										data['A'] = 3;
										if(nAtt == 3)
										{
											data['D'] = 4;
											data['C'] = 3;
											data['A'] = 3;
										}
									}
								}
							}
						}
						if(nDif == 5)
						{
							data['D'] = 5;
							data['C'] = 4;
							data['A'] = 2;
							if(nCen == 4)
							{
								data['D'] = 5;
								data['C'] = 4;
								data['A'] = 1;
							}
							else
							{
								if(nAtt == 2)
								{
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
				},
				activeClass: 'droppable-active',
				hoverClass: 'droppable-hover',
				drop: function(ev,ui) {
							var tagData = ui.draggable;
							if(ui.draggable.children('img').length > 0)
								var gioc = $('<div style="'+ ui.helper.attr('style') +'" class="embed '+ui.draggable.attr('class')+'" data-player-id="'+tagData.data('player-id') +'" ruolo="'+ tagData.data('ruolo') +'"><img width="80" src="' + imgsUrl + tagData.data('player-id') + '.jpg" /><p>' + ui.draggable.children('p').text() + '</p></div>');
							else
								var gioc = $('<div style="'+ ui.helper.attr('style') +'" class="embed '+ui.draggable.attr('class')+'" data-player-id="'+tagData.data('player-id') +'" ruolo="'+ tagData.data('ruolo') +'"><p>' + ui.draggable.children('p').text() + '</p></div>');
                            sobstitution(gioc);
							$(this).append(gioc);


							var exist = $("#panchina-field input[value=" + $(ui.draggable).data('player-id') + "]");
							if(exist.length > 0)
								exist.removeAttr("value");
							$(this).children('div').css('opacity','1');
							if((ui.draggable).parent().parent().attr('id') == 'giocatori')
								$(ui.draggable).hide();
							else
								ui.draggable.remove();
							ui.helper.remove();
							$("#campo .draggable").draggable({
								helper:"clone",opacity:0.5,revert:true
							});
							reloadFields();
							checkCapitani();
					}
				});
				$('#giocatori').droppable({
				activeClass: 'droppable-active',
				hoverClass: 'droppable-hover',
				drop: function(ev,ui) {
							if((ui.draggable).parent().parent().attr('id') == 'giocatori')
								$(ui.draggable).hide();
							else
								ui.draggable.remove();
							ui.helper.remove();
							var exist = $("#titolari-field input[value=" + $(ui.draggable).data('player-id') + "]");
							if(exist.length > 0)
								exist.removeAttr("value");

							$("#giocatori .giocatore[data-player-id=" + ui.draggable.data('player-id') + "]").show();
							$("#giocatori .draggable").draggable({
								helper:"clone",opacity:0.5,revert:true
							});
						reloadFields();
						checkCapitani();
					}
				});

				$('#panchina .droppable').droppable({
				accept: function(draggable) {
					var n = 0;
					$(this).find("div").each(function () {
						n++;
					});
					if(n== 0)
						return true;
				},
				activeClass: 'droppable-active',
				hoverClass: 'droppable-hover',
				drop: function(ev,ui) {
							ui.draggable.removeClass('embed');
							var tagData = ui.draggable;
							if(ui.draggable.children('img').length > 0)
								var gioc = $('<div style="margin:auto;float:none;" class="embed '+ui.draggable.attr('class')+'" data-player-id="'+tagData.data('player-id') +'" data-ruolo="'+ tagData.data('ruolo') +'"><img width="80" src="' + imgsUrl + tagData.data('player-id') + '.jpg" /><p>' + $(ui.draggable).children('p').text() + '</p></div>');
							else
								var gioc = $('<div style="margin:auto;float:none;" class="embed '+ui.draggable.attr('class')+'" data-player-id="'+tagData.data('player-id') +'" data-ruolo="'+ tagData.data('ruolo') +'"><p>' + $(ui.draggable).children('p').text() + '</p></div>');

                            sobstitution(gioc);
							$(this).append(gioc);

							var exist = $("#titolari-field input[value=" + $(ui.draggable).data('player-id') + "]");
							if(exist.length > 0)
								exist.removeAttr("value");

							$(this).children('div').css('opacity','1');
							if((ui.draggable).parent().parent().attr('id') == 'giocatori')
								$(ui.draggable).hide();
							else
								ui.draggable.remove();
							ui.helper.remove();
							$("#panchina .draggable").draggable({
								helper:"clone",opacity:0.5,revert:true
							});
							reloadFields();
							checkCapitani();
					}
				});
				$('#capitani .droppable').droppable({
				accept: function(draggable) {
					var n = 0;
					$(this).find("div").each(function () {
						n++;
					});
					if(n == 0)
					{
						var tagData = $(draggable).attr('name');
						if((tagData == "P" || tagData == "D") && $(draggable).parent().parent().attr('id') == 'campo')
						{
							var exist = $("#capitani-field input[value=" + $(draggable).data('player-id') + "]");
							if(exist.length == 0)
								return true;
						}
					}
				},
				activeClass: 'droppable-active',
				hoverClass: 'droppable-hover',
				drop: function(ev,ui) {
							var tagData = ui.draggable;
							if(ui.draggable.children('img').length > 0)
								var gioc = $('<div class="embed '+ui.draggable.attr('class')+'" data-player-id="'+tagData.data('player-id') +'" data-ruolo="'+ tagData.data('ruolo') +'"><img width="80" src="' + imgsUrl + tagData.data('player-id') + '.jpg" /><p>' + $(ui.draggable).children('p').text() + '</p></div>');
							else
								var gioc = $('<div class="embed '+ui.draggable.attr('class')+'" data-player-id="'+tagData.data('player-id') +'" data-ruolo="'+ tagData.data('ruolo') +'"><p>' + $(ui.draggable).children('p').text() + '</p></div>');
							gioc.droppable({
			                    accept: function(draggable) {
									if($(draggable).hasClass("giocatore"))
									    return true;
									else
									    return false;
								},
								activeClass: 'droppable-active',
								hoverClass: 'droppable-hover'
							});
							$(this).append(gioc);
							$(this).children('div').css('opacity','1');
							var list = $("#capitani-field").find("input");
							list.each(function (i) {
								$(list[i]).removeAttr('value');
							});
							var lista = $("#capitani").find("div.embed");
							lista.each(function (i) {
								var current = $(lista[i]);
								var id = current.parent().attr('id');
								var appo = id.split('-');
								$("input[name='" + appo[1] + "']").val($(lista[i]).data('player-id'));
							});
							$(this).append('<a class="remove">Rimuovi</a>');
					}
				});

			}
				function checkCapitani()
				{
					var list = $("#capitani").find(".giocatore");
					if(list.length > 0)
					{
						var listTitolari = $("#campo").find("div.embed");
						list.each(function (i) {
							var idCap = $(list[i]).data('player-id');
							var flag = false;
							listTitolari.each(function (i) {
								var val = $(listTitolari[i]).data('player-id');
								if(idCap == val)
									flag = true;
							});
							if(!flag) {
								$(list[i]).parent().empty();
								$("#capitani-field input[value=" + idCap + "]").removeAttr('value');
							}
						});
					}
					else
						$("#capitani-field input").removeAttr('value');
				}
				function reloadFields() {
                    var list = $("#titolari-field").find("input");
					list.each(function (i) {
						$(list[i]).removeAttr('value');
					});
					var lista = $("#campo").find("div.embed");
					lista.each(function (i) {
						$("input[name='gioc[" + i + "]']").attr('value',$(lista[i]).data('player-id'));
					});
					list = $("#panchina-field").find("input");
					list.each(function (i) {
						$(list[i]).removeAttr('value');
					});
					lista = $("#panchina").find("div.embed");
					lista.each(function (i) {
						$("input[name='panch[" + i + "]']").attr('value',$(lista[i]).data('player-id'));
					});
				}
				function sobstitution(gioc) {
                    $(gioc).droppable({
                                greedy: true,
			                    accept: function(draggable) {
									var ruolo = $(this).data('ruolo');
									if($(draggable).hasClass("giocatore") && $(draggable).data('ruolo') == ruolo)
									    return true;
									else
									    return false;
								},
								activeClass: 'droppable-active',
								hoverClass: 'droppable-hover2',
								drop: function(ev,ui) {
									var id = $(this).data('player-id');
									var content = $(this).html();
									$(this).empty();
									$(this).html(ui.draggable.html());
									$(this).attr('data-player-id',ui.draggable.data('player-id'));
									var source = ui.draggable.parent().parent().attr('id');
									if(source == 'giocatori') {
										ui.draggable.hide();
                                        $('#giocatori .giocatore[data-player-id=' + id + ']').show();
									}
									else {
										if(source == 'panchina' || source == 'campo') {
											ui.draggable.empty();
											ui.draggable.html(content);
											ui.draggable.attr('data-player-id',id);
										} else {
											ui.draggable.remove();
		  								}
									}
									ui.helper.remove();
									$("#campo .draggable").draggable({
										helper:"clone",opacity:0.5,revert:true
									});
									reloadFields();
									checkCapitani();
								}
							});
				}
			});
