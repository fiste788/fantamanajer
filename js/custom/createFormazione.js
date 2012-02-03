$(document).ready(function(){
	$("#giocatori .draggable").each(function () {
		ruolo = $(this).parent().attr("class");
		$(this).children('a').attr("name",ruolo + ruolo); 
	});
				if(typeof(modulo) != "undefined")
				{
					ruolo = 'PP';
					j = 0;
					k = 0;
					var title = "";
					var current = "";
					list = $("#titolari-field").find("input");
					list.each(function (i) {
						current = $(list[i]);
						title = current.attr('title');
						if(typeof(title) == 'undefined')
							title = "";
						$('a[rel="' + current.val() + '"]').parent().hide();
						if(title != "")
						{
							appo = title.split('-');
							ruoloGioc = appo[0];
							nomeGioc = appo[1];
							if(ruoloGioc != ruolo)
							{
								j++;
								k = 0;
							}
							if(appo[2])
							    gioc = $('<div style="position:absolute;left:' + ((((554-(70 * modulo[ruoloGioc])) / (modulo[ruoloGioc] + 1 )) * (k+1)) + ((k+1) * 70)-70) + 'px;top:' + ((140 * j) + 65) + 'px" class="embed giocatore draggable ui-draggable '+ ruoloGioc.substr(1) +'"><a rel="'+ current.attr('value') +'" name="'+ ruoloGioc +'" /><img width="40" src="' + imgsUrl + current.attr('value') + '.jpg" /><p>' + nomeGioc + '</p></div>');
							else
								gioc = $('<div style="position:absolute;left:' + ((((554-(70 * modulo[ruoloGioc])) / (modulo[ruoloGioc] + 1 )) * (k+1)) + ((k+1) * 70)-70) + 'px;top:' + ((140 * j) + 65) + 'px" class="embed giocatore draggable ui-draggable '+ ruoloGioc.substr(1) +'"><a rel="'+ current.attr('value') +'" name="'+ ruoloGioc +'" /><p>' + nomeGioc + '</p></div>');
							sobstitution(gioc);
                            $("#campo div.droppable[id=" +ruoloGioc+"]").append(gioc);
							ruolo = ruoloGioc;
							k++;
						}
					});
					list = $("#panchina-field").find("input[value!='']");
					list.each(function (i) {
						current = $(list[i]);
						title = current.attr('title');
						$('a[rel="' + current.val() + '"]').parent().hide();
						appo = title.split('-');
						ruoloGioc = appo[0];
						nomeGioc = appo[1];
						if(appo[2])
							gioc = $('<div class="embed giocatore draggable ui-draggable '+ ruoloGioc.substr(1) +'"><a rel="'+ current.attr('value') +'" name="'+ ruoloGioc +'" /><img height="50" src="' + imgsUrl + current.attr('value') + '.jpg" /><p>' + nomeGioc + '</p></div>');
						else
							gioc = $('<div class="embed giocatore draggable ui-draggable '+ ruoloGioc.substr(1) +'"><a rel="'+ current.attr('value') +'" name="'+ ruoloGioc +'" /><p>' + nomeGioc + '</p></div>');
                        sobstitution(gioc);
                        $("#panchina .droppable[id='panch-" + i + "']").append(gioc);
					});
					list = $("#capitani-field").find("input[value!='']");
					list.each(function (i) {
						current = $(list[i]);
						title = current.attr('title');
						appo = title.split('-');
						ruoloGioc = appo[0];
						nomeGioc = appo[1];
						if(appo[2])
							$("#capitani .droppable[id='cap-" + current.attr('id') + "']").append('<div class="embed giocatore '+ ruoloGioc.substr(1) +'"><a rel="'+ $(list[i]).attr('value') +'" name="'+ ruoloGioc +'" /><img height="50" src="' + imgsUrl + $(list[i]).attr('value') + '.jpg" /><p>' + nomeGioc + '</p></div>');
						else
							$("#capitani .droppable[id='cap-" + current.attr('id') + "']").append('<div class="embed giocatore '+ ruoloGioc.substr(1) +'"><a rel="'+ $(list[i]).attr('value') +'" name="'+ ruoloGioc +'" /><p>' + nomeGioc + '</p></div>');
						if(typeof(edit) != "undefined" && edit)
							$("#capitani .droppable[id='cap-" + current.attr('id') + "']").append('<a class="remove">Rimuovi</a>');
					});
				}
			if(typeof(edit) != "undefined" && edit)
			{
			$(".remove").live("click",function () {
		parent = $(this).parent();
		id = $(parent).attr('id');
		appo = id.split('-');
		livello = appo[1];
		$(this).parent().empty();
		$("#capitani-field #"+livello).removeAttr("value");
	});
			$(".draggable").draggable({
				helper:"clone",opacity:0.5,revert:true
			});
			var data = new Array();
					data['PP']=1;
					data['DD']=5;
					data['CC']=5;
					data['AA']=3;
			$('#campo .droppable').droppable({
				accept: function(draggable) {
					var nome = $(this).attr('id');
					if($(draggable).children('a').attr('name') == nome) {
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
							data['DD'] = 5;
							data['CC'] = 5;
							data['AA'] = 3;
							if(nCen == 4)
							{
								if(nAtt == 3)
								{
									data['DD'] = 3;
									data['CC'] = 4;
									data['AA'] = 3;
								}
								
							}
							if(nCen == 5)
							{
								data['DD'] = 3;
								data['CC'] = 5;
								data['AA'] = 2;
								
							}
						}
						if(nDif == 3)
						{ 
							data['DD'] = 5;
							data['CC'] = 5;
							data['AA'] = 3;
							if(nCen == 5)
							{
								data['DD'] = 4;
								data['CC'] = 5;
								data['AA'] = 2;
								if(nAtt == 2)
								{
									data['DD'] = 3;
									data['CC'] = 5;
									data['AA'] = 2;
								}
								
							}
							if(nAtt == 3)
							{
								data['DD'] = 4;
								data['CC'] = 4;
								data['AA'] = 3;
								if(nCen == 4)
								{
									data['DD'] = 3;
									data['CC'] = 4;
									data['AA'] = 3;
								}
							}
						}
						if(nDif == 4)
						{
							data['DD'] = 5;
							data['CC'] = 5;
							data['AA'] = 3;
							if(nCen == 5)
							{
								data['DD'] = 4;
								data['CC'] = 5;
								data['AA'] = 1;
							}
							else 
							{
								if(nCen == 4)
								{
									data['DD'] = 5;
									data['CC'] = 5;
									data['AA'] = 2;
									if(nAtt == 2)
									{
										data['DD'] = 4;
										data['CC'] = 4;
										data['AA'] = 2;
									}
								}
								else
								{
									if(nCen <= 3)
									{
										data['DD'] = 5;
										data['CC'] = 5;
										data['AA'] = 3;
										if(nAtt == 3)
										{
											data['DD'] = 4;
											data['CC'] = 3;
											data['AA'] = 3;
										}
									}
								}
							}
						}
						if(nDif == 5)
						{ 
							data['DD'] = 5;
							data['CC'] = 4;
							data['AA'] = 2;
							if(nCen == 4)
							{
								data['DD'] = 5;
								data['CC'] = 4;
								data['AA'] = 1;
							}
							else
							{
								if(nAtt == 2)
								{
									data['DD'] = 5;
									data['CC'] = 3;
									data['AA'] = 2;
								}
							}
						}
					var numMax = data[nome];
					if(n < numMax && nTot < 11 && $(draggable).children('a').attr('name') == nome)
						return true;
					}
				},
				activeClass: 'droppable-active',
				hoverClass: 'droppable-hover',
				drop: function(ev,ui) {
							var tagData = ui.draggable.children('a');
							if(ui.draggable.children('img').length > 0)
								gioc = $('<div style="'+ ui.helper.attr('style') +'" class="embed '+ui.draggable.attr('class')+'"><a rel="'+tagData.attr('rel') +'" name="'+ tagData.attr('name') +'" /><img height="50" src="' + imgsUrl + tagData.attr('rel') + '.jpg" /><p>' + ui.draggable.children('p').text() + '</p></div>');
							else
								gioc = $('<div style="'+ ui.helper.attr('style') +'" class="embed '+ui.draggable.attr('class')+'"><a rel="'+tagData.attr('rel') +'" name="'+ tagData.attr('name') +'" /><p>' + ui.draggable.children('p').text() + '</p></div>');
                            sobstitution(gioc);
							$(this).append(gioc);
								
								
							var exist = $("#panchina-field input[value=" + $(ui.draggable).children('a').attr('rel') + "]");
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
							var exist = $("#titolari-field input[value=" + $(ui.draggable).children('a').attr('rel') + "]");
							if(exist.length > 0)
								exist.removeAttr("value");
							
							$("#giocatori .draggable a[rel=" + ui.draggable.children('a').attr('rel') + "]").parent().show();
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
							var tagData = ui.draggable.children('a');
							if(ui.draggable.children('img').length > 0)
								gioc = $('<div style="margin:auto;float:none;" class="embed '+ui.draggable.attr('class')+'"><a rel="'+tagData.attr('rel') +'" name="'+ tagData.attr('name') +'"  /><img height="50" src="' + imgsUrl + tagData.attr('rel') + '.jpg" /><p>' + $(ui.draggable).children('p').text() + '</p></div>');
							else
								gioc = $('<div style="margin:auto;float:none;" class="embed '+ui.draggable.attr('class')+'"><a rel="'+tagData.attr('rel') +'" name="'+ tagData.attr('name') +'"  /><p>' + $(ui.draggable).children('p').text() + '</p></div>');
								
                            sobstitution(gioc);
							$(this).append(gioc);

							var exist = $("#titolari-field input[value=" + $(ui.draggable).children('a').attr('rel') + "]");
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
						var tagData = $(draggable).children('a').attr('name');
						if((tagData == "PP" || tagData == "DD") && $(draggable).parent().parent().attr('id') == 'campo')
						{
							var exist = $("#capitani-field input[value=" + $(draggable).children('a').attr('rel') + "]");
							if(exist.length == 0)
								return true;
						}
					}
				},
				activeClass: 'droppable-active',
				hoverClass: 'droppable-hover',
				drop: function(ev,ui) {
							var tagData = ui.draggable.children('a');
							if(ui.draggable.children('img').length > 0)
								gioc = $('<div class="embed '+ui.draggable.attr('class')+'"><a rel="'+tagData.attr('rel') +'" name="'+ tagData.attr('name') +'"  /><img height="50" src="' + imgsUrl + tagData.attr('rel') + '.jpg" /><p>' + $(ui.draggable).children('p').text() + '</p></div>');
							else
								gioc = $('<div class="embed '+ui.draggable.attr('class')+'"><a rel="'+tagData.attr('rel') +'" name="'+ tagData.attr('name') +'"  /><p>' + $(ui.draggable).children('p').text() + '</p></div>');
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
							list = $("#capitani-field").find("input");
							list.each(function (i) {
								$(list[i]).removeAttr('value');
							});
							lista = $("#capitani").find("div.embed");
							lista.each(function (i) {
								current = $(lista[i]);
								id = current.parent().attr('id');
								appo = id.split('-');
								$("input[name='cap[" + appo[1] + "]']").attr('value',$(lista[i]).children('a').attr('rel'));
							});
							$(this).append('<a class="remove">Rimuovi</a>');
					}
				});
			
			}
				function checkCapitani()
				{
					list = $("#capitani").find(".giocatore");
					if(list.length > 0)
					{
						listTitolari = $("#campo").find("div.embed");
						list.each(function (i) {
							idCap = $(list[i]).children('a').attr('rel');
							flag = false;
							listTitolari.each(function (i) {
								val = $(listTitolari[i]).children('a').attr('rel');
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
                    list = $("#titolari-field").find("input");
					list.each(function (i) {
						$(list[i]).removeAttr('value');
					});
					lista = $("#campo").find("div.embed");
					lista.each(function (i) {
						$("input[name='gioc[" + i + "]']").attr('value',$(lista[i]).children('a').attr('rel'));
					});
					list = $("#panchina-field").find("input");
					list.each(function (i) {
						$(list[i]).removeAttr('value');
					});
					lista = $("#panchina").find("div.embed");
					lista.each(function (i) {
						$("input[name='panch[" + i + "]']").attr('value',$(lista[i]).children('a').attr('rel'));
					});
				}
				function sobstitution(gioc) {
                    $(gioc).droppable({
                                greedy: true,
			                    accept: function(draggable) {
									var nome = $(this).children('a').attr('name');
									if($(draggable).hasClass("giocatore") && $(draggable).children('a').attr('name') == nome)
									    return true;
									else
									    return false;
								},
								activeClass: 'droppable-active',
								hoverClass: 'droppable-hover2',
								drop: function(ev,ui) {
									var id = $(this).children('a').attr('rel');
									var content = $(this).html();
									$(this).empty();
									$(this).html(ui.draggable.html());
									source = ui.draggable.parent().parent().attr('id')
									if(source == 'giocatori') {
										ui.draggable.hide();
                                        $('#giocatori a[rel=' + id + ']').parent().show();
									}
									else {
										if(source == 'panchina' || source == 'campo') {
											ui.draggable.empty();
											ui.draggable.html(content);
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
