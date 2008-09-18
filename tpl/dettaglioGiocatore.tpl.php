<?php $ruo = array('P'=>'Portiere','D'=>'Difensore','C'=>'Centrocampista','A'=>'Attaccante'); ?>
<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'freeplayer-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column"><?php echo $this->dettaglioGioc[0]['Cognome']." ".$this->dettaglioGioc[0]['Nome']; ?></h2>
</div>
<div id="dettaglioGioc" class="main-content"> 
<table>
	<tr>
		<td>Cognome:</td>
		<td><?php echo $this->dettaglioGioc[0]['Cognome']; ?></td>
	</tr>
	<tr>
		<td>Nome:</td>
		<td><?php echo $this->dettaglioGioc[0]['Nome']; ?></td>
	</tr>
	<tr>
		<td>Ruolo:</td>
		<td><?php echo $ruo[$this->dettaglioGioc[0]['Ruolo']]; ?></td>
	</tr>
	<tr>
		<td>Squadra:</td>
		<td><?php if(isset($this->dettaglioGioc[0]['IdSquadra'])) echo $this->squadra['nome']; else echo "Libero" ?></td>
	</tr>
	<tr>
		<td>Club:</td>
		<td><?php echo $this->dettaglioGioc[0]['Club']; ?></td>
	</tr>
	<tr>
		<td>Gol:</td>
		<td><?php echo $this->dettaglioGioc[0]['gol']; ?></td>
	</tr>
	<tr>
		<td>Presenze:</td>
		<td><?php echo $this->dettaglioGioc[0]['presenze']; ?></td>
	</tr>
	<tr>
		<td>Assist:</td>
		<td><?php echo $this->dettaglioGioc[0]['assist']; ?></td>
	</tr>
	<tr>
		<td>Voto medio:</td>
		<td><?php echo $this->dettaglioGioc[0]['votoMedio']; ?></td>
	</tr>
</table>
<?php if(isset($this->dettaglioGioc['data'])): ?>
<table>
	<tr>
			<th>Dettaglio</th>
		<?php foreach($this->dettaglioGioc['data'] as $key=>$val): ?>
			<th><?php echo $key; ?></th>
		<?php endforeach; ?>
	</tr>
	<tr>
			<td>Voti</td>
		<?php foreach($this->dettaglioGioc['data'] as $key=>$val): ?>
			<td><?php echo $val['Voto']; ?></td>
		<?php endforeach; ?>
	</tr>
	<tr>
			<td>Gol</td>
		<?php foreach($this->dettaglioGioc['data'] as $key=>$val): ?>
			<td><?php echo $val['Gol']; ?></td>
		<?php endforeach; ?>
	</tr>
	<tr>
			<td>Assist</td>
		<?php foreach($this->dettaglioGioc['data'] as $key=>$val): ?>
			<td><?php echo $val['Assist']; ?></td>
		<?php endforeach; ?>
	</tr>
</table>
<div id="placeholder" class="column last" style="width:600px;height:300px;clear:both;">&nbsp;</div>
	<div id="overview" class="column " style="width:200px;height:100px;clear:both;cursor:pointer;">&nbsp;</div>
	<p>Seleziona sulla miniatura una parte di grafico per ingrandirla. Per questa funzionalità si consiglia di usare browser come Safari, Firefox o Opera invece di altri meno performanti come Internet Explorer</p><p class="column" id="selection">&nbsp;</p>
	<div id="hidden" class="hidden">&nbsp;</div>
	<a id="clearSelection" class="hidden">(Cancella selezione)</a>
	<script id="source" type="text/javascript">
	<!--
 $(function () {
			var data = [
				{
				label: "<?php echo $this->dettaglioGioc[0]['Cognome'] ." ". $this->dettaglioGioc[0]['Nome'] ?>",
				data: [<?php $i = 0; foreach($this->dettaglioGioc['data'] as $key=>$val): $i++; ?><?php echo '['.$key.','.$val['Voto'].']'; if(count($this->dettaglioGioc['data']) != $i) echo ','; endforeach; ?>]
				}
			];
				
			var options = {
				lines: { show: true },
				points: { show: true },
				grid: { backgroundColor: null,hoverable:true },
				legend: { noColumns: 1, container: $("#legendcontainer"),backgroundColor: null },
				xaxis: { tickDecimals: 0 },
				yaxis: { min: 0 },
				shadowSize: 2,
				selection: { mode: null }
			};

			// hard-code color indices to prevent them from shifting as
			// countries are turned on/off
	

			var placeholder = $.plot($("#placeholder"), data, options);


				var overview = $.plot($("#overview"), data, {
					lines: { show: true, lineWidth: 1 },
					shadowSize: 0,
					xaxis: { tickDecimals: 0 },
					yaxis: { min: 0},
					selection: { mode: "x" },
					legend: { show:false }
				});
				
				function showTooltip(x, y,color, contents) {
					var arrayColor = color.substring(4);
					arrayColor = arrayColor.replace(')','');
					arrayColor = arrayColor.split(',');
					for (var i=0;i<arrayColor.length;i++)
					{
						arrayColor[i] = arrayColor[i]*1 + 120;
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
						opacity: 0.60
					}).appendTo("body").fadeIn(200);
				}
				
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

				$("#clearSelection").bind("click",function () {
					overview.clearSelection();
					$("#hidden").removeAttr('val1');
					$("#hidden").removeAttr('val2');
					var placeholder = $.plot($("#placeholder"), data, options);
					$("#clearSelection").addClass('hidden');
					$("#selection").empty();
				});

				$("#overview").bind("selected", function (event, area) {
					$("#legendcontainer table").remove();
					$("#hidden").attr('val1',area.x1);
					$("#hidden").attr('val2',area.x2);
					$("#clearSelection").removeClass('hidden');
					$("#selection").text("Hai selezionato dalla giornata " + Math.round(area.x1.toFixed(1)) + " alla " + Math.round(area.x2.toFixed(1)));
					// do the zooming
					plot = $.plot($("#placeholder"), data,
						$.extend(true, {}, options, {
							xaxis: { min: Math.round(area.x1), max: Math.round(area.x2) }
					}));
					$("#legendcontainer table").attr('cellspacing','0');
				});

				$("#legendcontainer table").attr('cellspacing','0');

	});
	-->
	</script>
<?php endif; ?>
</div>
<?php if($_SESSION['logged'] == TRUE): ?>
	<div id="squadradett" class="column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
<?php else: ?>
	<div class="right">&nbsp;</div>
<?php endif; ?>
