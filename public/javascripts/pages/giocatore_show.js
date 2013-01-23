(function($) {
    $.fn.grafico = function(datasets) {
        datasets = $.extend({}, $.fn.grafico.datasets, datasets);
		var i = 0,
        	options = {
	            colors: ["#edc240", "#afd8f8","#555555", "#cb4b4b", "#4da74d", "#9440ed","#dddddd","#00a2ff"],
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
        function plotAccordingToChoices() {
            var data = [],
				plot = null,
            	$grafico = $("#grafico"),
            	$clearSelection = $("#clear-selection"),
            	$placeholder = $("#placeholder"),
            	$overviewDom = $("#overview"),
            	$selection = $("#selection"),
            	from = grafico.data('from'),
            	to = grafico.data('to');
			data.push(datasets.voto);
            data.push(datasets.punti);
            if(from != null && to != null) {
                plot = $.plot($placeholder, data,$.extend(true, {}, options, {
                    xaxis: {
                        min: Math.round(from),
                        max: Math.round(to),
                        tickDecimals: 0
                    },
                    yaxis: {}
                }));
            } else
                plot = $.plot($placeholder, data,options);

            var overview = $.plot($overviewDom, data, {
                colors: ["#edc240", "#afd8f8","#555555", "#cb4b4b", "#4da74d", "#9440ed","#dddddd","#00a2ff"],
                lines: {
                    show: true,
                    lineWidth: 1
                },
                shadowSize: 0,
                xaxis: {
                    ticks: 4,
                    tickDecimals: 0
                },
                yaxis: {
                    ticks: 4
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

            clearSelection.bind("click",function () {
                $overview.clearSelection();
                $grafico.removeData('from');
                $grafico.removeData('to');
                plotAccordingToChoices();
                $clearSelection.hide();
                $selection.empty();
            });

            function showTooltip(x, y,color, contents) {
                var arrayColor = color.substring(4);
                arrayColor = arrayColor.replace(')','');
                arrayColor = arrayColor.split(',');
                for (var i=0;i<arrayColor.length;i++) {
                    arrayColor[i] = arrayColor[i] * 1 + 120;
                    if(arrayColor[i] > 255)
                        arrayColor[i] = 255;
                }
                colorLight = "rgb("+arrayColor[0]+","+arrayColor[1]+","+arrayColor[2]+")";
                $('<div id="tooltip">' + contents + '</div>').css({
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
            }

            var previousPoint = null;
            placeholder.bind("plothover", function (event, pos, item) {
                var $tooltip = $("#tooltip");
                if (item) {
                    if (!previousPoint || (previousPoint[0] != item.datapoint[0]) || (previousPoint[1] != item.datapoint[1])) {
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

            $overviewDom.bind("plotselected", function (event, area) {
                $grafico.data('from',area.xaxis.from);
                $grafico.data('to',area.xaxis.to);
                $clearSelection.show();
                $selection.text("Hai selezionato dalla giornata " + Math.round(area.xaxis.from.toFixed(1)) + " alla " + Math.round(area.xaxis.to.toFixed(1)));
                //selecting only the used data
                var data = [];
                data.push(datasets.voto);
                data.push(datasets.punti);

                // do the zooming
                plot = $.plot($placeholder, data,
                    $.extend(true, {}, options, {
                        xaxis: {
                            min: Math.round(area.xaxis.from),
                            max: Math.round(area.xaxis.to),
                            tickDecimals:0
                        },
                        yaxis: {}
                    }));
                $overview.setSelection(area, true);
            });

            if(from != null && to != null) {
                overview.setSelection({
                    x1 : from,
                    x2 : to
                });
            }
        }
        plotAccordingToChoices();
    };
    $.fn.grafico.datasets = "";
})(jQuery);
var activeGrafico = false,
	giornate = $("#giornate"),
	giocatore = giornate.data("giocatore"),
	datasets = {};
if(giocatore) {
    datasets.punti = {
        "label":"Punti " + giocatore,
        "data":[]
    };
    datasets.voto = {
        "label":"Voto " + giocatore,
        "data":[]
    };
    giocatore.find("tbody tr").each(function(i,tr) {
        datasets.voto.data.push($(tr).data("voto"));
        datasets.punti.data.push($(tr).data("punti"));
    });
}
function enableGrafico() {
    if(!$.isViewport('phone') && !activeGrafico) {
        activeGrafico = true;
        Modernizr.load({
            test: Modernizr.canvas,
            nope: JSURL + '/flot/excanvas.min.js',
            complete: function() {
                if(datasets && !$.isEmptyObject(datasets))
                    $(document).grafico(datasets);

            }
        });
    }
}
enableGrafico();
$(window).bind("exitViewportPhone", enableGrafico);
