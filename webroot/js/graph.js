(function ($) {
    $.fn.classifica = function(datasets,medie,squadra) {
        datasets = $.extend({}, $.fn.classifica.datasets, datasets);
        medie = $.extend({}, $.fn.classifica.medie, medie);
        squadra = $.extend({}, $.fn.classifica.squadra, squadra);
        var i = 0,
			colors = ["#edc240", "#afd8f8","#555555", "#cb4b4b", "#4da74d", "#9440ed","#dddddd","#00a2ff"],
			$choiceContainer = $("#classifica-container").find("table");
			options = {
	            colors: colors,
	            lines: {
	                show: true
	            },
	            points: {
	                show: true
	            },
	            grid: {
	                backgroundColor: null,
	                hoverable:true,
	                tickColor: '#aaa',
	                color:'#aaa'
	            },
	            legend: {
	                show: false
	            },
	            xaxis: {
	                tickDecimals: 0
	            },
	            shadowSize: 2,
	            selection: {
	                mode: null
	            }
	        };


        // hard-code color indices to prevent them from shifting as
        // countries are turned on/off
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
        $.each(datasets, function(key, val) {
            var $rigaSquadra = $choiceContainer.find("#squadra-"+key.replace(/ /g,'')),
				input = $('<input class="hidden-xs checkbox" type="checkbox" name="' + key + '" />');
            $rigaSquadra.prepend('<div class="hidden-xs legend" style="background:' + options.colors[val.color] + '"></div>');
            if(squadra !== false)
                input.prop('checked',true);
            $rigaSquadra.prepend(input);
        });

        $choiceContainer.find("input").change(plotAccordingToChoices);
        function plotAccordingToChoices() {
            var data = [],
            	$grafico = $("#grafico"),
            	$placeholder = $("#placeholder"),
            	$overviewDom = $("#overview"),
            	$clearSelection = $("#clear-selection"),
				plot = null;
            	j = null,
            	k = 0;
            $choiceContainer.find("input:checked").each(function () {
                var key = $(this).attr("name");
                if (key && datasets[key]) {
                    data.push(datasets[key]);
                    j = key;
                    k++;
                }
            });
            if(k === 0)
                $grafico.hide();
            else
                $grafico.show();
            if (k === 1)
                data.push(medie[j]);

            var from = $grafico.data('from'),
            	to = $grafico.data('to');

            if(from !== undefined && to !== undefined) {
                plot = $.plot($placeholder, data,$.extend(true, {}, options, {
                    xaxis: {
                        min: Math.round(from) ,
                        max: Math.round(to),
                        tickDecimals:0
                    },
                    yaxis: {}
                }));
            } else
                plot = $.plot($placeholder, data,options);

            var $overview = $.plot($overviewDom, data, {
                colors: colors,
                lines: {
                    show: true,
                    lineWidth: 1
                },
                shadowSize: 0,
                xaxis: {
                    ticks: 6,
                    tickDecimals:0
                },
                selection: {
                    mode: "x"
                },
                legend: {
                    show:false
                },
                grid : {
                    tickColor: '#aaa',
                    color:'#aaa',
                    borderWidth:1
                }
            });

            $clearSelection.on("click",function () {
                $overview.clearSelection();
                $grafico.removeData('from');
                $grafico.removeData('to');
                plotAccordingToChoices();
                $clearSelection.hide();
                $("#selection").empty();
            });

            function showTooltip(x, y,color, contents) {
                var arrayColor = color.substring(4),
					myLength = 0;
                arrayColor = arrayColor.replace(')','');
                arrayColor = arrayColor.split(',');
				myLength = arrayColor.length;
                for (var i = 0;i < myLength;i++) {
                    arrayColor[i] = arrayColor[i] * 1 + 120;
                    if(arrayColor[i] > 255)
                        arrayColor[i] = 255;
                }
                colorLight = "rgba("+arrayColor[0]+","+arrayColor[1]+","+arrayColor[2]+",0.7)";
                $('<div id="tooltip">' + contents + '</div>').css( {
                    position: 'absolute',
                    display: 'none',
                    top: y + 5,
                    left: x + 5,
                    border: '1px solid '+color,
                    padding: '5px',
                    'background-color': colorLight,
                    color: '#333'
                }).appendTo("body").fadeIn(200);
            }

            var previousPoint = null;
            $placeholder.on("plothover", function (event, pos, item) {
                var $tooltip = $("#tooltip");
                if (item) {
                    if (!previousPoint || (previousPoint[0] !== item.datapoint[0]) || (previousPoint[1] !== item.datapoint[1])) {
                        previousPoint = item.datapoint;
                        $tooltip.remove();
                        var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);
                        showTooltip(item.pageX, item.pageY,item.series.color,item.series.label + ": giornata " + Math.round(x) + " = " + Math.round(y*10)/10 + " punti");
                    }
                } else {
                    $tooltip.remove();
                    previousPoint = null;
                }
            });

            $overviewDom.on("plotselected", function (event, area) {
                $grafico.data('from',area.xaxis.from);
                $grafico.data('to',area.xaxis.to);
                $clearSelection.show();
                $("#selection").text("Hai selezionato dalla giornata " + Math.round(area.xaxis.from.toFixed(1)) + " alla " + Math.round(area.xaxis.to.toFixed(1)));
                //selecting only the used data
                var data = [],
					plot = null,
                	j = null,
                	k = 0;
                $choiceContainer.find("input:checked").each(function () {
                    var appo = {},
                    	key = $(this).attr("name");
                    appo.label = key;
                    appo.data = [];
                    appo.color = datasets[key]['color'];
                    if (key && datasets[key]) {
                        for(i = Math.round(area.xaxis.from);i <= Math.round(area.xaxis.to); i++)
                            appo.data.push(datasets[key]['data'][Math.abs(i - datasets[key]['data'].length)]);
                        data.push(appo);
                        j = key;
                        k++;
                    }
                });
                if (k === 1)
                    data.push(medie[j]);

                // do the zooming
                plot = $.plot(placeholder, data,
                    $.extend(true, {}, options, {
                        xaxis: {
                            min: Math.round(area.xaxis.from),
                            max: Math.round(area.xaxis.to)
                        },
                        yaxis: {}
                    }));
                $overview.setSelection(area, true);
            });

            if(from !== undefined && to !== undefined)
                $overview.setSelection({
                    x1 : from,
                    x2 : to
                });
        }
        plotAccordingToChoices();
    };
    $.fn.classifica.datasets = null;
    $.fn.classifica.medie = null;
    $.fn.classifica.squadra = null;
})(jQuery);
enquire.register("screen and (min-width:" + sizes.sm + "px)", {
    deferSetup:true,
    setup: function() {
        var giornate = $("#tab_classifica").find("th"),
            datasets = {},
            medie = {},
            squadra = $("#classifica-container").data("squadra");
        $("#tab_classifica").find("tbody tr").each(function(i,tr) {
            var $tr = $(tr);
                nomeSquadra = $tr.data("squadra");
                key = $tr.data("key");
                mediaVal = $tr.data("media");
                squadra = {label:nomeSquadra,data:[]};
                media = {label:"Media " + nomeSquadra,data:[]},
                tds = $tr.find("td");
            tds.each(function(i2,td) {
                var nGior = parseInt($(giornate[i2]).text()),
                    giornata = [];
                if(i2 === 0 || (i2 + 1) === tds.length) {
                    var appo = [];
                    appo.push(nGior,mediaVal);
                    media.data.push(appo);
                }
                giornata.push(nGior, parseFloat($(td).find("a").text()));
                squadra.data.push(giornata);
            });
            datasets[key] = squadra;
            medie[key] = media;
        });
        if(!$.isEmptyObject(datasets))
            $(document).classifica(datasets,medie,squadra);
    }
});