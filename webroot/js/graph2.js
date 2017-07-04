+function ($) {
	'use strict';

	var Classification = function (datasets, averages, team) {
		this.team = team;
		this.datasets = datasets;
		this.averages = averages;

		this.$choiceContainer = null;
		this.$plotContainer = null;
		this.$placeholder = null;
		this.$placeholderOverview = null;
		this.$clearSelection = null;
		this.plot = null;
		this.overview = null;
		this.previusPoint = null;
		this.options = {
			colors: ["#edc240", "#afd8f8", "#555555", "#cb4b4b", "#4da74d", "#9440ed", "#dddddd", "#00a2ff"],
			lines: {
				show: true
			},
			points: {
				show: true
			},
			grid: {
				backgroundColor: null,
				hoverable: true,
				tickColor: '#aaa',
				color: '#aaa'
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
		this.overviewOptions = {
			colors: this.options.colors,
			lines: {
				show: true,
				lineWidth: 1
			},
			shadowSize: 0,
			xaxis: {
				ticks: 6,
				tickDecimals: 0
			},
			selection: {
				mode: "x"
			},
			legend: {
				show: false
			},
			grid: {
				tickColor: '#aaa',
				color: '#aaa',
				borderWidth: 1
			}
		};
		this.init();
	};

	Classification.prototype.init = function () {
		this.$choiceContainer = $("#classification-container").find("table");
		this.$plotContainer = $("#plot-container");
		this.$placeholder = $("#placeholder");
		this.$placeholderOverview = $("#placeholder-overview");
		this.$clearSelection = $("#clear-selection");

		this.fixColors();
		this.buildOptions();
		this.$choiceContainer.find("input").on('change', null, $.proxy(this.build, this));
		this.$clearSelection.on("click", null, $.proxy(this.clearSelection, this));
		this.$placeholder.on("plothover", null, $.proxy(this.plotHover, this));
		this.$placeholderOverview.on("plotselected", null, $.proxy(this.plotSelection, this));
		this.build();
	};
	
	Classification.prototype.fixColors = function() {
		var i = 0;
		$.each(this.datasets, function (key, val) {
			val.color = i;
			++i;
		});
		i = 0;
		$.each(this.averages, function (key, val) {
			val.color = i;
			++i;
		});
	};
	
	Classification.prototype.buildOptions = function() {
		var colors = this.options.colors,
				team = this.options.team,
				$container = this.$choiceContainer;
		$.each(this.datasets, function (key, val) {
			var $teamRow = $container.find("#team-" + key.replace(/ /g, '')),
					input = $('<input class="hidden-xs checkbox" type="checkbox" name="' + key + '" />');
			$teamRow.prepend('<div class="hidden-xs legend" style="background:' + colors[val.color] + '"></div>');
			if (team !== false)
				input.prop('checked', true);
			$teamRow.prepend(input);
		});
	};

	Classification.prototype.plotHover = function (event, pos, item) {
		var $tooltip = $("#tooltip");
		if (item) {
			if (!this.previousPoint || (this.previousPoint[0] !== item.datapoint[0]) || (this.previousPoint[1] !== item.datapoint[1])) {
				this.previousPoint = item.datapoint;
				$tooltip.remove();
				var x = item.datapoint[0].toFixed(2),
						y = item.datapoint[1].toFixed(2);
				this.showTooltip(item.pageX, item.pageY, item.series.color, item.series.label + ": giornata " + Math.round(x) + " = " + Math.round(y * 10) / 10 + " punti");
			}
		} else {
			$tooltip.remove();
			this.previousPoint = null;
		}
	};

	Classification.prototype.plotSelection = function (event, area) {
		var from = Math.round(area.xaxis.from.toFixed(1)),
				to = Math.round(area.xaxis.to.toFixed(1));
		this.$plotContainer.data('from', from);
		this.$plotContainer.data('to', to);
		this.$clearSelection.show();
		$("#selection").text("Hai selezionato dalla giornata " + from + " alla " + to);
		this.overview.setSelection(area, true);
		this.build();
	};

	Classification.prototype.clearSelection = function () {
		this.overview.clearSelection();
		this.$plotContainer.removeData('from');
		this.$plotContainer.removeData('to');
		this.build();
		this.$clearSelection.hide();
		$("#selection").empty();
	};

	Classification.prototype.lightColor = function (color, opacity) {
		var arrayColor = color.substring(4).replace(')', '').split(','),
				myLength = arrayColor.length;
		for (var i = 0; i < myLength; i++) {
			arrayColor[i] = parseInt(arrayColor[i]) + 120;
			if (arrayColor[i] > 255)
				arrayColor[i] = 255;
		}
		return "rgba(" + arrayColor[0] + "," + arrayColor[1] + "," + arrayColor[2] + "," + opacity + ")";
	};

	Classification.prototype.showTooltip = function (x, y, color, contents) {
		var colorLight = this.lightColor(color, 0.7);
		$('<div id="tooltip">' + contents + '</div>').css({
			position: 'absolute',
			display: 'none',
			top: y + 5,
			left: x + 5,
			border: '1px solid ' + color,
			padding: '5px',
			'background-color': colorLight,
			color: '#333'
		}).appendTo("body").fadeIn(200);
	};
	
	Classification.prototype.getActiveSeries = function () {
		var data = [],
				datasets = this.datasets,
				averages = this.averages,
				checked = this.$choiceContainer.find("input:checked");
		
		checked.each(function () {
			var key = $(this).attr("name");
			if (key && datasets[key]) {
				data.push(datasets[key]);
			}
			if(checked.length === 1)
				data.push(averages[key]);
		});
		return data;
	};

	Classification.prototype.build = function () {
		var series = this.getActiveSeries(),
				options = this.options,
				from = this.$plotContainer.data('from'),
				to = this.$plotContainer.data('to');
		
		if (series.length === 0)
			this.$plotContainer.hide();
		else {
			if (from !== undefined && to !== undefined) {
				options = $.extend(true, {}, options, {
					xaxis: {
						min: Math.round(from),
						max: Math.round(to)
					},
					yaxis: {}
				});
			}
			this.$plotContainer.show();
			this.plot = $.plot(this.$placeholder, series, options);
			this.overview = $.plot(this.$placeholderOverview, series, this.overviewOptions);
		}
	};

	function Plugin(datasets, averages, team) {
		new Classification(datasets, averages, team);
	}

	var old = $.fn.classification;

	$.fn.classification = Plugin;
	$.fn.classification.Constructor = Classification;


	// CLASSIFICATION NO CONFLICT
	// ===================

	$.fn.classification.noConflict = function () {
		$.fn.classification = old;
		return this;
	};

}(jQuery);

enquire.register("screen and (min-width:" + sizes.sm + "px)", {
	deferSetup: true,
	setup: function () {
		var matchdays = $("#tab_classification").find("th"),
				datasets = {},
				averages = {},
				team = $("#classification-container").data("team");
		$("#tab_classification").find("tbody tr").each(function (i, tr) {
			var $tr = $(tr),
					name = $tr.data("team"),
					key = $tr.data("key"),
					mediaVal = $tr.data("avg"),
					teamData = {label: name, data: []},
					avg = {label: "Media " + name, data: []},
					tds = $tr.find("td");
			tds.each(function (i2, td) {
				var numMatchday = parseInt($(matchdays[i2]).text()),
						matchday = [];
				if (i2 === 0 || (i2 + 1) === tds.length) {
					var appo = [];
					appo.push(numMatchday, mediaVal);
					avg.data.push(appo);
				}
				matchday.push(numMatchday, parseFloat($(td).find("a").text()));
				teamData.data.push(matchday);
			});
			datasets[key] = teamData;
			averages[key] = avg;
		});
		if (!$.isEmptyObject(datasets))
			$(document).classification(datasets, averages, team);
	}
});