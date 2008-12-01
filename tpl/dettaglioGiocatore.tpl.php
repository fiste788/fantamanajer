<?php $ruo = array('P'=>'Portiere','D'=>'Difensore','C'=>'Centrocampista','A'=>'Attaccante'); ?>
<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'freeplayer-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column"><?php echo $this->dettaglioGioc[0]['cognome']." ".$this->dettaglioGioc[0]['nome']; ?></h2>
</div>
<div id="dettaglioGioc" class="main-content"> 
<?php if(file_exists(IMGDIR.'foto/'.$this->dettaglioGioc[0]['idGioc'].'.jpg')): ?>
	<img class="column" src="<?php echo IMGSURL.'foto/'.$this->dettaglioGioc[0]['idGioc'].'.jpg'; ?>">
<?php endif; ?>
	<p>Cognome: <?php echo $this->dettaglioGioc[0]['cognome']; ?></p>
	<p>Nome: <?php echo $this->dettaglioGioc[0]['nome']; ?></p>
	<p>Ruolo: <?php echo $ruo[$this->dettaglioGioc[0]['ruolo']]; ?></p>
	<p>Squadra: <?php if(isset($this->dettaglioGioc[0]['idUtente'])) echo $this->squadra['nome']; else echo "Libero" ?></p>
	<p>Club: <?php echo $this->dettaglioGioc[0]['nomeClub']; ?></p>
	<p>Presenze: <?php echo $this->dettaglioGioc[0]['presenze']; ?></p>
	<p>Gol: <?php if(!empty($this->dettaglioGioc[0]['gol'])) echo $this->dettaglioGioc[0]['gol'];  ?></p>
	<p>Assist: <?php if(!empty($this->dettaglioGioc[0]['assist'])) echo $this->dettaglioGioc[0]['assist']; ?></p>
	<p>Media Voto: <?php if(!empty($this->dettaglioGioc[0]['mediaVoti'])) echo $this->dettaglioGioc[0]['mediaVoti']; ?></p>
	<p>Media Punti: <?php if(!empty($this->dettaglioGioc[0]['mediaPunti'])) echo $this->dettaglioGioc[0]['mediaPunti']; ?></p>
<?php if(isset($this->dettaglioGioc['data'])): ?>
<table style="width:100px;" class="column last" cellpadding="0" cellspacing="0">
	<tr>
		<th>Giornata</th>
	</tr>
	<tr>
		<td>Punti</td>
	</tr>
	<tr>
		<td>Voti</td>
	</tr>
	<tr>
		<td>Gol</td>
	</tr>
	<tr>
		<td>Assist</td>
	</tr>
</table>
<div id="tab_giocatore" class="column last">
	<table class="column last" cellpadding="0" cellspacing="0" style="width:<?php echo count($this->dettaglioGioc['data'])*40; ?>px;margin:0;">
		<tr>
		<?php foreach($this->dettaglioGioc['data'] as $key => $val): ?>
			<th><?php echo $key; ?></th>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['data'] as $key => $val): ?>
			<td><?php echo $val['voto']; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['data'] as $key => $val): ?>
			<td><?php if($val['votoUff'] != '0') echo $val['votoUff']; else echo "&nbsp;"; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['data'] as $key => $val): ?>
			<td><?php echo $val['gol']; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['data'] as $key => $val): ?>
			<td><?php echo $val['assist']; ?></td>
		<?php endforeach; ?>
		</tr>
	</table>
</div>
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
				label: "Voto <?php echo $this->dettaglioGioc[0]['cognome'] ." ". $this->dettaglioGioc[0]['nome'] ?>",
				data: [<?php $i = 0; foreach($this->dettaglioGioc['data'] as $key => $val): $i++; ?><?php if($val['votoUff'] != '0') echo '['.$key.','.$val['votoUff'].']'; if($val['votoUff'] != '0' && count($this->dettaglioGioc['data']) != $i) echo ','; endforeach; ?>]
				},
				{
				label: "Punteggio <?php echo $this->dettaglioGioc[0]['cognome'] ." ". $this->dettaglioGioc[0]['nome'] ?>",
				data: [<?php $i = 0; foreach($this->dettaglioGioc['data'] as $key => $val): $i++; ?><?php if($val['voto'] != '0') echo '['.$key.','.$val['voto'].']'; if($val['voto'] != '0' && count($this->dettaglioGioc['data']) != $i) echo ','; endforeach; ?>]
				}
			];
				
			var options = {
				lines: { show: true },
				points: { show: true },
				grid: { backgroundColor: null,hoverable:true,tickColor: '#aaa',color:'#aaa' },
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
					legend: { show:false },
					grid: {tickColor: '#aaa',color:'#aaa',borderWidth:1}
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
						opacity: 0.60
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
					$("#overview").setSelection(area, true);
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
