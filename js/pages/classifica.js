(function ($) {
    $.fn.classifica = function(datasets,medie,squadra) {
        datasets = $.extend({}, $.fn.classifica.datasets, datasets);
        medie = $.extend({}, $.fn.classifica.medie, medie);
        squadra = $.extend({}, $.fn.classifica.squadra, squadra);
        var options = {
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
            input = $('<input class="checkall checkbox" type="checkbox" name="' + key + '" />');
            if(squadra != false)
                input.attr('checked','checked');

            rigaSquadra.prepend(input);
        });

        choiceContainer.find("input").click(plotAccordingToChoices);
        function plotAccordingToChoices() {
            var data = [];
            var grafico = $("#grafico");
            var placeholder = $("#placeholder");
            var overviewDom = $("#overview");
            var clearSelection = $("#clear-selection");
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
                grafico.hide();
            else
                grafico.show();
            if (k == 1)
                data.push(medie[j]);

            var from = grafico.data('from');
            var to = grafico.data('to');

            if(from != null && to != null) {
                plot = $.plot(placeholder, data,$.extend(true, {}, options, {
                    xaxis: {
                        min: Math.round(from) ,
                        max: Math.round(to),
                        tickDecimals:0
                    },
                    yaxis: {}
                }));
            }
            else
                plot = $.plot(placeholder, data,options);

            var overview = $.plot(overviewDom, data, {
                colors: ["#edc240", "#afd8f8","#555555", "#cb4b4b", "#4da74d", "#9440ed","#dddddd","#00a2ff"],
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

            clearSelection.bind("click",function () {
                overview.clearSelection();
                grafico.removeData('from');
                grafico.removeData('to');
                plotAccordingToChoices();
                clearSelection.hide();
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
            }

            var previousPoint = null;
            placeholder.bind("plothover", function (event, pos, item) {
                var tooltip = $("#tooltip");
                if (item) {
                    if (!previousPoint || (previousPoint[0] != item.datapoint[0]) || (previousPoint[1] != item.datapoint[1])) {
                        previousPoint = item.datapoint;
                        tooltip.remove();
                        var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);
                        showTooltip(item.pageX, item.pageY,item.series.color,item.series.label + ": giornata " + Math.round(x) + " = " + Math.round(y*10)/10 + " punti");
                    }
                }
                else {
                    tooltip.remove();
                    previousPoint = null;
                }
            });

            overviewDom.bind("plotselected", function (event, area) {
                grafico.data('from',area.xaxis.from);
                grafico.data('to',area.xaxis.to);
                clearSelection.show();
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
                plot = $.plot(placeholder, data,
                    $.extend(true, {}, options, {
                        xaxis: {
                            min: Math.round(area.xaxis.from),
                            max: Math.round(area.xaxis.to)
                        },
                        yaxis: {}
                    }));
                overview.setSelection(area, true);
            });

            if(from != null && to != null)
                overview.setSelection({
                    x1 : from,
                    x2 : to
                });
        }
        plotAccordingToChoices();
    };
    $.fn.classifica.datasets = "";
    $.fn.classifica.medie = "";
    $.fn.classifica.squadra = "";
})(jQuery);
var activeClassifica = false;
var datasets = {};
var medie = {};
var giornate = $("#tab_classifica thead th");
var squadra = $("#classifica-container").data("squadra");

$("#tab_classifica tbody tr").each(function(i,tr) {
    var nomeSquadra = $(tr).data("squadra");
    var key = $(tr).data("key");
    var mediaVal = $(tr).data("media");
    var squadra = {"label":nomeSquadra,"data":[]};
    var media = {"label":"Media " + nomeSquadra,"data":[]};

    var tds = $(tr).find("td");
    tds.each(function(i2,td) {
        var nGior = parseInt($(giornate[i2]).text());
        if(i2 == 0 || (i2 + 1) == tds.length) {
            var appo = new Array();
            appo.push(nGior,mediaVal);
            media.data.push(appo);
        }
        var giornata = new Array();
        giornata.push(nGior, parseFloat($("a",td).text()));
        squadra.data.push(giornata);
    });
    datasets[key] = squadra;
    medie[key] = media;
});
function enableClassifica() {
    if(($.isViewport('tablet') || $.isViewport('desktop')) && !activeClassifica) {
        activeClassifica = true;
        Modernizr.load({
            test: Modernizr.canvas,
            nope: JSURL + '/flot/excanvas.min.js',
            complete: function() {
                $(document).classifica(datasets,medie,squadra);
            }
        });
    }
}
enableClassifica();
$(window).bind("exitViewportPhone", enableClassifica);
