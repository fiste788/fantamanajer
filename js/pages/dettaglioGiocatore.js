(function($) {
    $.fn.grafico = function(datasets) {
        dataset = $.extend({}, $.fn.grafico.datasets, datasets);
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
        var placeholder = $("#placeholder");
        function plotAccordingToChoices() {
            var data = [];
            data.push(datasets['voto']);
            data.push(datasets['punti']);
            $("#legendcontainer table").remove();
            var j = null;
            var k = 0;
            var val1 = $("#hidden").attr('val1');
            var val2 = $("#hidden").attr('val2');

            if(val1 != null && val2 != null) {
                plot = $.plot($("#placeholder"), data,$.extend(true, {}, options, {
                    xaxis: {
                        min: Math.round(val1) ,
                        max: Math.round(val2)
                    },
                    yaxis: {}
                }));
            }
            else
                plot = $.plot($("#placeholder"), data,options);

            var overview = $.plot($("#overview"), data, {
                colors: ["#edc240", "#afd8f8","#555555", "#cb4b4b", "#4da74d", "#9440ed","#dddddd","#00a2ff"],
                lines: {
                    show: true,
                    lineWidth: 1
                },
                shadowSize: 0,
                xaxis: {
                    ticks: 4
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
            }

            var previousPoint = null;
            $("#placeholder").bind("plothover", function (event, pos, item) {
                if (item) {
                    if (!previousPoint || (previousPoint[0] != item.datapoint[0]) || (previousPoint[1] != item.datapoint[1])) {
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
                data.push(datasets['voto']);
                data.push(datasets['punti']);
                var j = null;
                var k = 0;

                // do the zooming
                plot = $.plot($("#placeholder"), data,
                    $.extend(true, {}, options, {
                        xaxis: {
                            min: Math.round(area.xaxis.from),
                            max: Math.round(area.xaxis.to)
                        },
                        yaxis: {}
                    }));
                overview.setSelection(area, true);
            });

            if(val1 != null && val2 != null)
                overview.setSelection({
                    x1 : val1,
                    x2 : val2
                });
        }
        plotAccordingToChoices();
    };
    $.fn.grafico.datasets = "";
})(jQuery);
var activeGrafico = false;
function enableGrafico() {
    if(!$.isViewport('phone') && !activeGrafico) {
        activeGrafico = true;
        Modernizr.load({
            test: Modernizr.canvas,
            nope: JSURL + '/flot/excanvas.min.js',
            complete: function() {
                if(datasets)
                    $(document).grafico(datasets);

            }
        });
    }
}
enableGrafico();
$(window).bind("exitViewportPhone", enableGrafico);
