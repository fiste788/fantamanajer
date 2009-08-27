<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'home-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Home</h2>
</div>
<div id="home" class="main-content">
	<?php if($this->giornata == 1): ?>
	<h2 class="no-margin center">Ricordati di impostare il nome della squadra!</h2>
	<p class="center">Lo trovi nella pagina rosa e lo puoi cambiare entro la seconda giornata di campionato</p>
	<?php endif; ?>
	<div id="tabs">
		<ul id="icone" class="ui-tabs-nav column last">
			<li><a href="#grafica">
				<img id="grafica-icon" src="<?php echo IMGSURL.'grafica-icon.png' ?>" title="Nuova grafica" alt="1" />
			</a></li>
			<li><a href="#effetti">
				<img id="effetti-icon" src="<?php echo IMGSURL.'effetti-icon.png' ?>" title="Effetti" alt="2" />
			</a></li>
			<li><a href="#coming-soon">
				<img id="coming-soon-icon" src="<?php echo IMGSURL.'coming-soon-icon.png' ?>" title="Coming soon" alt="3" />
			</a></li>
			<li><a href="#valid">
				<img id="valid-icon" src="<?php echo IMGSURL.'valid-icon.png' ?>" title="Valido W3C" alt="4" />
			</a></li>
			<li><a href="#rss">
				<img id="beta-icon" src="<?php echo IMGSURL.'rss-icon.png' ?>" title="Feed RSS" alt="5" />
			</a></li>
		</ul>
		<div id="box-home" class="column last">
			<div class="box2-top-sx column last">
			<div class="box2-top-dx column last">
			<div class="box2-bottom-sx column last">
			<div class="box2-bottom-dx column last">
			<div class="box-content column last">
				<div id="grafica" class="ui-tabs-panel">
					<img class="column" src="<?php echo IMGSURL.'grafica.png'; ?>" alt="Nuova Grafica" />
					<h2>Nuova Grafica</h2>
					<p>FantaManajer 2.0 introduce una nuovissima grafica in stile web 2.0. Nuove icone, nuovi sfondi per rendere più piacevole la navigazione</p>
				</div>
				<div id="effetti" class="ui-tabs-panel">
					<img class="column" src="<?php echo IMGSURL.'effetti.png'; ?>" alt="Effetti" />
					<h2>Effetti</h2>
					<p>Grazie alle librerie di jQuery (noto framework javascript) sono stati aggiunti diversi effetti per rendere il nuovo FantaManajer più accattivante. Popup, trasparenze e animazioni e molto altro...</p>
				</div>
				<div id="coming-soon" class="ui-tabs-panel">
					<img class="column" src="<?php echo IMGSURL.'coming-soon.png'; ?>" alt="Coming soon" />
					<h2>Coming soon</h2>
					<p>Per la prossima stagione verrà implementato un metodo del tutto nuovo per la settare la formazione. Esso consistera in dei riquadri con il nome del giocatore che sarà possibile trascinare sul campo e mettere il giocatore nella posizione desiderata. E ci sarà anche un'interfaccia di amministrazione per gli admin</p>
				</div>
				<div id="valid" class="ui-tabs-panel">
					<img class="column" src="<?php echo IMGSURL.'valid.png'; ?>" alt="Valido W3C"/>
					<h2>Valido W3C</h2>
					<p>Da questa versione l'intero sito è stato validato secondo gli standard web del W3C. Questo consiste nel non avere errori nelle pagine e una maggiore integrazione cross-browser (nessuna differenza tra i browser)</p>
				</div>
				<div id="rss" class="ui-tabs-panel">
					<img class="column" src="<?php echo IMGSURL.'rss.png'; ?>" alt="RSS"/>
					<h2>Feed RSS</h2>
					<p>Nel FantaManajer 2.0 sono stati introdotti gli eventi con la possibilità di estrarre i feed RSS per aggiungerli alle tue pagine personali come iGoogle o Netvibes. Aggiungi il seguente link per abbonarti al feed RSS del FantaManajer http://www.fantamanajer.it/rss.php</p>
				</div>
			</div>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready( function() {
			$('#tabs').tabs({
				event:'mouseover',
				cache:true,
				show: function(ui)
				{
					if(jQuery.browser.msie)
						$(".panel").removeAttr('style');
				}, fx: { opacity: 'toggle',duration:300 } }).tabs('rotate', 5000,false);
		});
	</script>
	<?php if($this->eventi != FALSE) :?>
	<div id="eventi" class="column" style="clear:both;">
	<h3 class="column">Ultimi eventi</h3>
		<div class="conf-stampa column last" style="clear:both;">
			<div class="box2-top-sx column last">
			<div class="box2-top-dx column last">
			<div class="box2-bottom-sx column last">
			<div class="box2-bottom-dx column last">
			<div class="conf-stampa-content column last">
				<?php foreach($this->eventi as $key =>$val): ?>
					<h4>
					<?php if($val['tipo'] != 2): ?>
						<a name="evento-<?php echo $val['idEvento']; ?>" href="<?php echo $val['link']; ?>" title="<?php echo $val['content']; ?>">
					<?php endif;?>
					<?php echo $val['titolo']; ?>
					<?php if($val['tipo'] != 2): ?>
						</a>
					<?php endif;?>
					</h4>
				<?php endforeach; ?>
				<a class="right" href="<?php echo $this->linksObj->getLink('feed'); ?>">Vedi tutti gli eventi &raquo;</a>
			</div>
			</div>
			</div>
			</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php if($this->articoli != FALSE) :?>
	<div id="confStampa" class="column last">
	<h3 class="column">Ultime news</h3>
	<?php foreach($this->articoli as $key => $val): ?>
		<div class="conf-stampa column last" style="clear:both;">
			<div class="box2-top-sx column last">
			<div class="box2-top-dx column last">
			<div class="box2-bottom-sx column last">
			<div class="box2-bottom-dx column last">
			<div class="conf-stampa-content column last">
				<?php if(isset($_SESSION['idSquadra']) && $_SESSION['idSquadra'] == $val['idSquadra']): ?>
					<a class="column last" href="<?php echo $this->linksObj->getLink('modificaConferenza',array('a'=>'edit','id'=>$val['idArticolo'])); ?>">
						<img src="<?php echo IMGSURL.'edit.png'; ?>" alt="m" title="Modifica" />
					</a>
					<a class="column" href="<?php echo $this->linksObj->getLink('modificaConferenza',array('a'=>'cancel','id'=>$val['idArticolo'])); ?>">
						<img src="<?php echo IMGSURL.'cancel.png'; ?>" alt="e" title="Cancella" />
					</a>
				<?php endif; ?>
				<em>
					<span class="column last"><?php echo $this->squadre[$val['idSquadra']]['username']; ?></span>
					<span class="right"><?php echo $val['insertDate']; ?></span>
				</em>
				<h3 class="title"><?php echo $val['title']; ?></h3>
				<div class="abstract"><?php echo $val['abstract']; ?></div>
				<div class="text"><?php echo nl2br($val['text']); ?></div>
			</div>
			</div>
			</div>
			</div>
			</div>
		</div>
	<?php endforeach; ?>
	</div>
	<?php endif; ?>
</div>
<div id="squadradett" class="right last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
	<?php if(TIMEOUT): ?>
	<div id="countdown">Tempo rimanente per la formazione:<br /><div>&nbsp;</div></div>
	<script type="text/javascript">
		var d = new Date();
		d.setFullYear(<?php echo $this->dataFine['year'] . ',' . ($this->dataFine['month'] -1) . ',' . $this->dataFine['day']; ?>);
		d.setHours(<?php echo $this->dataFine['hour'] . ',' . $this->dataFine['minute'] . ',' . $this->dataFine['second']; ?>);
		$('#countdown div').countdown({
			msgFormat: '<span class="number">%d</span> [giorno|giorni], <span class="number">%h</span> [ora|ore] <span class="number">%m</span> [minuto|minuti] e <span class="number">%s</span> [secondo|secondi]',
			date: d,
			msgNow:'Tempo scaduto'
		});
	</script>
	<?php endif; ?>
	<?php if(isset($_SESSION['message'])): ?>
		<div id="messaggio" class="messaggio neut column last">
			<img alt="!" src="<?php if($_SESSION['message'][0] == 0) echo IMGSURL.'lock.png'; else echo IMGSURL.'attention-big.png'; ?>" title="Attenzione!" />
			<span><?php echo $_SESSION['message'][1]; ?></span>
		</div>
		<script type="text/javascript">
 		$(document).ready(function() {$('#messaggio').effect('pulsate',{times: 3 }); });
		$("#messaggio").click(function () {
			$("div#messaggio").fadeOut("slow");
		});
		</script>
	<?php unset($_SESSION['message']); endif; ?>
	<?php if($_SESSION['logged'] == TRUE): ?>
		<?php require (TPLDIR.'operazioni.tpl.php'); ?>
	<?php else: ?>
		<h2>Classifica</h2>
		<table id="classifica-home" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<th>Squadra</th>
					<th width="1px">P.ti</th>
				</tr>
				<?php $i=0; ?>
				<?php //echo "<pre>".print_r($this->classifica,1)."</pre>" ?>
				<?php //echo "<pre>".print_r($this->squadre,1)."</pre>" ?>
				<?php foreach ($this->classifica as $key => $val): ?>
					<tr <?php if($this->differenza[$i] < 0): ?>
							<?php echo 'class="rosso" title="' . $this->differenza[$i]. ' Pos."'; ?>
						<?php elseif($this->differenza[$i] > 0): ?>
							<?php echo 'class="verde" title="+ ' . $this->differenza[$i]. ' Pos."'; ?>
						<?php endif; ?>>
						<td><?php echo $this->squadre[$key][1]; ?></td>
						<td><?php echo $val; ?></td>
					</tr>
				<?php $i++; endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
