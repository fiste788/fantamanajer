<div id="dettaglioGioc" class="main-content">
	<div id="cardPlayer" class="column last">
			<img class="column" alt="<?php echo $this->dettaglioGioc['dettaglio']['cognome'] . ' ' . $this->dettaglioGioc['dettaglio']['nome']; ?>" src="<?php echo $this->pathFoto; ?>"/>
			<div id="datiGioc" class="column last">
				<?php if(isset($_GET['edit']) && $_GET['edit'] == 'edit' && $_SESSION['roles'] == '2'): ?>
				<div id="formData">
					<input type="hidden" name="idGioc" value="<?php echo $this->dettaglioGioc['dettaglio']['idGioc'] ?>" />
					<div>
						<label for="cognome">Cognome:</label>
						<input id="cognome" type="text" name="cognome" value="<?php if(isset($this->dettaglioGioc['dettaglio']['cognome'])) echo $this->dettaglioGioc['dettaglio']['cognome'] ?>" />
					</div>
					<div>
						<label for="nome">Nome:</label>
						<input id="nome" type="text" name="nome" value="<?php if(isset($this->dettaglioGioc['dettaglio']['nome'])) echo $this->dettaglioGioc['dettaglio']['nome'] ?>" />
					</div>
				</div>
				<?php else: ?>
				<h3><?php echo $this->dettaglioGioc['dettaglio']['cognome'] . ' ' . $this->dettaglioGioc['dettaglio']['nome']; ?></h3>
				<?php endif; ?>
				<img title="<?php echo $this->dettaglioGioc['dettaglio']['nomeClub']?>" class="shield" alt="<?php echo $this->dettaglioGioc['dettaglio']['nomeClub']?>" src="<?php echo $this->pathClub ?>"/>
				<p><?php echo $this->ruoli[$this->dettaglioGioc['dettaglio']['ruolo']]; ?></p>
				<?php if($_SESSION['logged']): ?><p>Squadra: <?php echo $this->label; ?></p><?php endif; ?>
				<p>Presenze: <?php echo $this->dettaglioGioc['dettaglio']['presenze']." (".$this->dettaglioGioc['dettaglio']['presenzeVoto'].")"; ?></p>
				<p>Gol: <?php if($this->dettaglioGioc['dettaglio']['ruolo'] != 'P') echo $this->dettaglioGioc['dettaglio']['gol'];else  echo $this->dettaglioGioc['dettaglio']['golSubiti'];  ?></p>
				<p>Assist: <?php echo $this->dettaglioGioc['dettaglio']['assist']; ?></p>
				<p>Media voti: <?php if(!empty($this->dettaglioGioc['dettaglio']['avgVoti'])) echo $this->dettaglioGioc['dettaglio']['avgVoti']; ?></p>
				<p>Media punti: <?php if(!empty($this->dettaglioGioc['dettaglio']['avgPunti'])) echo $this->dettaglioGioc['dettaglio']['avgPunti']; ?></p>
			</div>
		<?php if(isset($_GET['edit']) && $_GET['edit'] == 'edit' && $_SESSION['roles'] == '2'): ?>
		<div class="uploadleft column last">
			<input class="upload" type="file" name="userfile" id="upload"/>
		</div>
<?php endif; ?>
	</div>
<?php if(isset($this->dettaglioGioc['dettaglio']['data'])): ?>
<table style="width:100px;clear:both;" class="column last" cellpadding="0" cellspacing="0">
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
	<tr>
		<td>Ammonizioni</td>
	</tr>
	<tr>
		<td>Espulsioni</td>
	</tr>
</table>
<div id="tab_giocatore" class="column last">
	<table class="column last" cellpadding="0" cellspacing="0" style="width:<?php echo count($this->dettaglioGioc['dettaglio']['data'])*40; ?>px;margin:0;">
		<tr>
		<?php foreach($this->dettaglioGioc['dettaglio']['data'] as $key => $val): ?>
			<th><?php echo $key; ?></th>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['dettaglio']['data'] as $key => $val): ?>
			<td><?php echo $val['punti']; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['dettaglio']['data'] as $key => $val): ?>
			<td><?php if($val['voto'] != '0') echo $val['voto']; else echo "&nbsp;"; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['dettaglio']['data'] as $key => $val): ?>
			<td><?php echo $val['gol']; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['dettaglio']['data'] as $key => $val): ?>
			<td><?php echo $val['assist']; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['dettaglio']['data'] as $key => $val): ?>
			<td><?php echo $val['ammonizioni']; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['dettaglio']['data'] as $key => $val): ?>
			<td><?php echo $val['espulsioni']; ?></td>
		<?php endforeach; ?>
		</tr>
	</table>
</div>
<div id="placeholder" class="column last" style="width:950px;height:300px;clear:both;">&nbsp;</div>
	<div id="overview" class="column " style="width:200px;height:100px;clear:both;cursor:pointer;">&nbsp;</div>
	<p class="column" style="width:720px;">Seleziona sulla miniatura una parte di grafico per ingrandirla. Per questa funzionalità si consiglia di usare browser come Safari, Firefox o Opera invece di altri meno performanti come Internet Explorer</p><p class="column" id="selection">&nbsp;</p>
	<div id="hidden" class="hidden">&nbsp;</div>
	<a id="clearSelection" class="column hidden">(Cancella selezione)</a>
	<script id="source" type="text/javascript">
	<!--
 $(function () {
			var data = [
				{
				label: "Voto <?php echo $this->dettaglioGioc['dettaglio']['cognome'] ." ". $this->dettaglioGioc['dettaglio']['nome'] ?>",
				data: [<?php $i = 0; foreach($this->dettaglioGioc['dettaglio']['data'] as $key => $val): $i++; ?><?php if($val['punti'] != '0') echo '['.$key.','.$val['punti'].']'; if($val['punti'] != '0' && count($this->dettaglioGioc['dettaglio']['data']) != $i) echo ','; endforeach; ?>]
				},
				{
				label: "Punteggio <?php echo $this->dettaglioGioc['dettaglio']['cognome'] ." ". $this->dettaglioGioc['dettaglio']['nome'] ?>",
				data: [<?php $i = 0; foreach($this->dettaglioGioc['dettaglio']['data'] as $key => $val): $i++; ?><?php if($val['voto'] != '0') echo '['.$key.','.$val['voto'].']'; if($val['voto'] != '0' && count($this->dettaglioGioc['dettaglio']['data']) != $i) echo ','; endforeach; ?>]
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

				$("#overview").bind("plotselected", function (event, area) {
					$("#legendcontainer table").remove();
					$("#hidden").attr('val1',area.xaxis.from);
					$("#hidden").attr('val2',area.xaxis.to);
					$("#clearSelection").removeClass('hidden');
					$("#selection").text("Hai selezionato dalla giornata " + Math.round(area.xaxis.from) + " alla " + Math.round(area.xaxis.to));
					// do the zooming
					plot = $.plot($("#placeholder"), data,
						$.extend(true, {}, options, {
							xaxis: { min: Math.round(area.xaxis.from), max: Math.round(area.xaxis.to) }
					}));
					overview.setSelection(area, true);
					$("#legendcontainer table").attr('cellspacing','0');
				});

				$("#legendcontainer table").attr('cellspacing','0');

	});
	-->
	</script>
<?php endif; ?>
</div>
