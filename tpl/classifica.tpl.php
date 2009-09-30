<?php $i=1; ?>
<div id="classifica-content" class="main-content">
	<div id="classifica-container" class="column last">
		<table cellpadding="0" cellspacing="0" class="column last no-margin" style="width:316px;overflow:hidden;">
			<tbody>
				<tr>
					<th style="width:20px">P.</th>
					<th class="nowrap" style="width:180px">Nome</th>
					<th style="width:70px">Punti tot</th>
				</tr>
				<?php foreach($this->classificaDett as $key => $val): ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td class="squadra" id="squadra-<?php echo $key; ?>" class="nowrap"><?php echo $this->squadre[$key]['nome']; ?></td>
					<td><?php echo array_sum($val); ?></td>
				 </tr>
				<?php $i++;$flag = $key; endforeach; ?>
			</tbody>
		</table>
		<div id="tab_classifica" class="column last"  style="height:<?php echo (27 * (count($this->classificaDett) +1)) +18 ?>px">
		<?php $appo = array_keys($this->classificaDett); $i = $appo[0]; ?>
		<?php if(key($this->classificaDett[$flag]) != 0): ?>
		<table class="column last" cellpadding="0" cellspacing="0" style="width:<?php echo count($this->classificaDett[$i])*50; ?>px;margin:0;">
			<tbody>
				<tr>
					<?php foreach($this->classificaDett[$flag] as $key => $val): ?>
						<th style="width:35px"><?php echo $key ?></th>
					<?php endforeach; ?>
				</tr>
				<?php foreach($this->classificaDett as $key => $val): ?>
				<tr>
				<?php foreach($val as $secondKey=>$secondVal): ?>
					<td<?php if(isset($this->penalità[$key][$secondKey])) echo ' title="Penalità: ' . $this->penalità[$key][$secondKey] . ' punti" class="rosso"' ?>>
						<a href="<?php echo $this->linksObj->getLink('dettaglioGiornata',array('giorn'=>$secondKey,'squad'=>$this->squadre[$key][0])); ?>"><?php echo $val[$secondKey]; ?></a>
					</td>
					<?php endforeach; ?>
				</tr>
				<?php $i++; endforeach; ?>
			</tbody>
		</table>
		<?php endif; ?>
		</div>
	</div>
	<?php if(!empty($this->giornate)): ?>
	<div id="placeholder" class="column last" style="width:950px;height:300px;clear:both;">&nbsp;</div>
	<div id="overview" class="column " style="width:200px;height:100px;clear:both;cursor:pointer;">&nbsp;</div>
	<p class="column" style="width:720px;">Seleziona sulla miniatura una parte di grafico per ingrandirla. Per questa funzionalità si consiglia di usare browser come Safari, Firefox o Opera invece di altri meno performanti come Internet Explorer</p><p class="column" id="selection">&nbsp;</p>
	<div id="hidden" class="hidden">&nbsp;</div>
	<a id="clearSelection" class="column hidden">(Cancella selezione)</a>
	<script id="source" type="text/javascript">
	<!--
 $(function () {
   		var datasets = {
			<?php $i=0; foreach($this->classificaDett as $key => $val): $i++; ?>"<?php echo $key; ?>": {
				label: "<?php echo $this->squadre[$key]['nome']; ?>",
				data: [<?php foreach($val as $secondKey=>$secondVal): ?><?php echo '['.$secondKey.','.$val[$secondKey].']'; if(count($secondVal)-$secondKey != $secondKey-1) echo ','; endforeach; ?>]
			}<?php if(count($this->classificaDett) != $i) echo ",\n"; ?>

				<?php endforeach; ?>
			};

			var medie = {
				<?php $i=0; foreach($this->classificaDett as $key => $val): $i++; ?>
				<?php $media = array_sum($this->classificaDett[$key])/count($this->classificaDett[$key]) ?>
				"<?php echo $key ?>" : {label: "Media <?php echo $this->squadre[$key]['nome']; ?> (<?php echo substr($media,0,5); ?>)",data: [[1,<?php echo $media; ?>],[<?php echo count($this->classificaDett[$key]) ?>,<?php echo $media ?>]]}<?php if(count($this->classificaDett) != $i) echo ",\n"; ?>
				<?php endforeach; ?>
				};
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
				rigaSquadra.prepend('<div class="legend" style="background:' + options.colors[val.color] + '">&nbsp;</div>');
				rigaSquadra.prepend('<input style="margin:2px 0 0;float:left;padding:0" class="checkall checkbox" type="checkbox" name="' + key + '" checked="checked" />');
			});

			<?php if($_SESSION['logged'] == TRUE && $_SESSION['legaView'] == $_SESSION['idLega']): ?>
				choiceContainer.find("input[name!='<?php echo $this->squadre[$_SESSION['idSquadra']]['idUtente']; ?>']").attr ('checked','');
			<?php endif; ?>
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
						k++;}
				});
				if (k == 1)
					data.push(medie[j]);

				var val1 = $("#hidden").attr('val1');
				var val2 = $("#hidden").attr('val2');

				if(val1 != null && val2 != null) {
					plot = $.plot($("#placeholder"), data,
						$.extend(true, {}, options, {
							xaxis: { min: Math.round(val1) , max: Math.round(val2) },
							yaxis: {}
					}));
				}
				else
				{
					plot = $.plot($("#placeholder"), data,options);
				}

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
							
							showTooltip(item.pageX, item.pageY,item.series.color,
							item.series.label + ": giornata " + Math.round(x) + " = " + Math.round(y*10)/10 + " punti");
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
							k++;}
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
					$("#legendcontainer table").attr('cellspacing','0');
				});

				$("#legendcontainer table").attr('cellspacing','0');

				if(val1 != null && val2 != null)
					overview.setSelection({x1 : val1, x2 : val2});
			}

			$("#all").click(function()
			{
				var checked_status = this.checked;
				$("input.checkall").each(function()
				{
					this.checked = checked_status;
				});
				plotAccordingToChoices();
			});

			plotAccordingToChoices();
	});
	-->
	</script>
	<?php endif; ?>
</div>
