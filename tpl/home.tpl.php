<?php if(GIORNATA == 1): ?>
<h2 class="no-margin center">Ricordati di impostare il nome della squadra!</h2>
<p class="center">Lo trovi nella pagina rosa e lo puoi cambiare entro la seconda giornata di campionato</p>
<?php endif; ?>
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
<?php if(PARTITEINCORSO == FALSE): ?>
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
			<?php foreach ($this->classifica as $key => $val): ?>
				<tr <?php if($this->differenza[$i] < 0): ?>
						<?php echo 'class="rosso" title="' . $this->differenza[$i]. ' Pos."'; ?>
					<?php elseif($this->differenza[$i] > 0): ?>
						<?php echo 'class="verde" title="+ ' . $this->differenza[$i]. ' Pos."'; ?>
					<?php endif; ?>>
					<td><?php echo $this->squadre[$key]['nome']; ?></td>
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
