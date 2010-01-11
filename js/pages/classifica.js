(function($) {
	$.fn.classifica = function(datasets,medie,squadra) {
		dataset = $.extend({}, $.fn.classifica.datasets, datasets);
		medie = $.extend({}, $.fn.classifica.medie, medie);
		squadra = $.extend({}, $.fn.classifica.squadra, squadra);
		var options = {
			colors: ["#edc240", "#afd8f8","#555555", "#cb4b4b", "#4da74d", "#9440ed","#dddddd","#00a2ff"],
			lines: { show: true },
			points: { show: true },
			grid: { backgroundColor: null,hoverable:true,tickColor: '#aaa',color:'#aaa' },
			legend: {show: false },
			xaxis: { tickDecimals: 0 },
			shadowSize: 2,
			selection: { mode: null }
		};

		// hard-code color indices to prevent them from shifting as
		// countries are turned on/off
		var i = 0;
		$.each(datasets, function(key, val) {
			val.color = i;
			++i;
		});
		i = 0;
		$.each(medie, function(key, val) {
			val.color = i;
			++i;
		});

			// insert checkboxes
		var choiceContainer = $("#classifica-container table");
		$.each(datasets, function(key, val) {
			rigaSquadra = choiceContainer.find("#squadra-"+key.replace(/ /g,''));
			rigaSquadra.prepend('<div class="legend" style="background:' + options.colors[val.color] + '"></div>');
			rigaSquadra.prepend('<input checked="checked" style="margin:2px 0 0;float:left;padding:0" class="checkall checkbox" type="checkbox" name="' + key + '" checked="checked" />');
		});
		if(squadra['val'] != "")
			choiceContainer.find("input[name!='" + squadra['val'] + "']").removeAttr('checked');
		choiceContainer.find("input").click(plotAccordingToChoices);
		var placeholder = $("#placeholder");
		function plotAccordingToChoices() {
			var data = [];
			$("#legendcontainer table").remove();
			var j = null;
			var k = 0;
			choiceContainer.find("input:checked").each(function () {
				var key = $(this).attr("name");
				if (key && datasets[key]) {
					data.push(datasets[key]);
					j = key;
					k++;
				}
			});
			if(k == 0)
				$("#grafico").css("display","none");
			else
				$("#grafico").css("display","block");
			if (k == 1)
				data.push(medie[j]);

			var val1 = $("#hidden").attr('val1');
			var val2 = $("#hidden").attr('val2');

			if(val1 != null && val2 != null) {
				plot = $.plot($("#placeholder"), data,$.extend(true, {}, options, {
					xaxis: { min: Math.round(val1) , max: Math.round(val2) },
					yaxis: {}
				}));
			}
			else
				plot = $.plot($("#placeholder"), data,options);

			var overview = $.plot($("#overview"), data, {
				colors: ["#edc240", "#afd8f8","#555555", "#cb4b4b", "#4da74d", "#9440ed","#dddddd","#00a2ff"],
				lines: { show: true, lineWidth: 1 },
				shadowSize: 0,
				xaxis: { ticks: 4 },
				selection: { mode: "x" },
				legend: { show:false },
				grid : {tickColor: '#aaa',color:'#aaa',borderWidth:1}
			});

			$("#clearSelection").bind("click",function () {
				overview.clearSelection();
				$("#hidden").removeAttr('val1');
				$("#hidden").removeAttr('val2');
				plotAccordingToChoices();
				$("#clearSelection").addClass('hidden');
				$("#selection").empty();
			});
				
			function showTooltip(x, y,color, contents) {
				var arrayColor = color.substring(4);
				arrayColor = arrayColor.replace(')','');
				arrayColor = arrayColor.split(',');
				for (var i=0;i<arrayColor.length;i++)
				{
					arrayColor[i] = arrayColor[i]*1 + 120;
					if(arrayColor[i] > 255)
						arrayColor[i] = 255;
				}
				colorLight = "rgb("+arrayColor[0]+","+arrayColor[1]+","+arrayColor[2]+")";
				$('<div id="tooltip">' + contents + '</div>').css( {
					position: 'absolute',
					display: 'none',
					top: y + 5,
					left: x + 5,
					border: '1px solid '+color,
					padding: '2px',
					'background-color': colorLight,
					color: '#000',
					opacity: 0.70
				}).appendTo("body").fadeIn(200);
			};
			
			var previousPoint = null;
			$("#placeholder").bind("plothover", function (event, pos, item) {
				if (item) {
					if (previousPoint != item.datapoint) {
						previousPoint = item.datapoint;
						$("#tooltip").remove();
						var x = item.datapoint[0].toFixed(2),
						y = item.datapoint[1].toFixed(2);
						showTooltip(item.pageX, item.pageY,item.series.color,item.series.label + ": giornata " + Math.round(x) + " = " + Math.round(y*10)/10 + " punti");
					}
				}
				else {
					$("#tooltip").remove();
					previousPoint = null;
				}
			});

			$("#overview").bind("plotselected", function (event, area) {
				$("#legendcontainer table").remove();
				$("#hidden").attr('val1',area.xaxis.from);
				$("#hidden").attr('val2',area.xaxis.to);
				$("#clearSelection").removeClass('hidden');
				$("#selection").text("Hai selezionato dalla giornata " + Math.round(area.xaxis.from.toFixed(1)) + " alla " + Math.round(area.xaxis.to.toFixed(1)));
				//selecting only the used data
				var data = [];
				var j = null;
				var k = 0;
				choiceContainer.find("input:checked").each(function () {
					var appo = {};
					var key = $(this).attr("name");
					appo.label = key;
					appo.data = [];
					appo.color = datasets[key]['color'];
					if (key && datasets[key]) {
						for(i=Math.round(area.xaxis.from);i<=Math.round(area.xaxis.to); i++) {
							appo.data.push(datasets[key]['data'][Math.abs(i - datasets[key]['data'].length)])
						}
						data.push(appo);
						j = key;
						k++;
					}
				});
				if (k == 1)
					data.push(medie[j]);
					
				// do the zooming
				plot = $.plot($("#placeholder"), data,
					$.extend(true, {}, options, {
						xaxis: { min: Math.round(area.xaxis.from), max: Math.round(area.xaxis.to) },
						yaxis: {}
				}));
				overview.setSelection(area, true);
			});

			if(val1 != null && val2 != null)
				overview.setSelection({x1 : val1, x2 : val2});
		}
		plotAccordingToChoices();
	};
	$.fn.classifica.datasets = "";
	$.fn.classifica.medie = "";
	$.fn.classifica.squadra = "";
})(jQuery);
$(document).ready(function() {
		$(document).classifica(datasets,medie,squadra);
	});
