<?php if(GIORNATA == 1): ?>
<h2 class="no-margin center">Ricordati di impostare il nome della squadra!</h2>
<p class="center">Lo trovi nella pagina rosa e lo puoi cambiare entro la seconda giornata di campionato</p>
<?php endif; ?>
<div id="bestPlayer">
	<h3>Migliori giocatori giornata <?php echo $this->giornata; ?></h3>
	<?php foreach($this->bestPlayer as $ruolo=>$giocatori): ?>
		<div id="<?php echo $ruolo ?>" class="column">
			<?php foreach($giocatori as $key=>$val): ?>
			<?php if($key == 0): ?>
				<?php if(file_exists(IMGDIR . 'foto/' . $val->idGioc . '.jpg')): ?>
					<img src="<?php echo IMGSURL . 'foto/' . $val->idGioc; ?>" />
				<?php else: ?>
					<img src="<?php echo IMGSURL . 'no-photo.png'; ?>" />
				<?php endif; ?>
				<h4><?php echo $val->cognome . " " . $val->nome . "(" . substr(strtoupper($val->nomeClub),0,3) ."): " . $val->punti; ?></h4>
			<?php else: ?>
				<p><?php echo $val->cognome . " " . $val->nome . ": " . $val->punti; ?></p>
			<?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
</div>

<?php if($this->eventi != FALSE) :?>
<div id="eventi" class="column" style="clear:both;">
<h3 class="column">Ultimi eventi</h3>
	<div class="conf-stampa column last" style="clear:both;">
		<?php foreach($this->eventi as $key =>$val): ?>
			<h4>
			<?php if($val->tipo != 2): ?>
				<a href="<?php echo $val->link; ?>" title="<?php echo $val->content; ?>">
			<?php endif;?>
			<?php echo $val->titolo; ?>
			<?php if($val->tipo != 2): ?>
				</a>
			<?php endif;?>
			</h4>
		<?php endforeach; ?>
		<a class="right" href="<?php echo $this->linksObj->getLink('feed'); ?>">Vedi tutti gli eventi &raquo;</a>
	</div>
</div>
<?php endif; ?>
<?php if($this->articoli != FALSE) :?>
<div id="confStampa" class="column last">
<h3 class="column">Ultime news</h3>
<?php foreach($this->articoli as $key => $val): ?>
	<div class="conf-stampa column last" style="clear:both;">
			<?php if(isset($_SESSION['idSquadra']) && $_SESSION['idSquadra'] == $val->idSquadra): ?>
				<a class="column last" href="<?php echo $this->linksObj->getLink('modificaConferenza',array('a'=>'edit','id'=>$val->idArticolo)); ?>">
					<img src="<?php echo IMGSURL . 'edit.png'; ?>" alt="m" title="Modifica" />
				</a>
				<a class="column" href="<?php echo $this->linksObj->getLink('modificaConferenza',array('a'=>'cancel','id'=>$val->idArticolo)); ?>">
					<img src="<?php echo IMGSURL . 'cancel.png'; ?>" alt="e" title="Cancella" />
				</a>
			<?php endif; ?>
			<em>
				<span class="column last"><?php echo $val->username; ?></span>
				<span class="right"><?php echo $val->insertDate; ?></span>
			</em>
			<h3 class="title"><?php echo $val->title; ?></h3>
			<div class="abstract"><?php echo $val->abstract; ?></div>
			<div class="text"><?php echo nl2br($val->text); ?></div>
	</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
<div id="squadradett" class="right last">
	<?php if(PARTITEINCORSO == FALSE): ?>
	<div id="countdown">Tempo rimanente per la formazione:<br /><div>&nbsp;</div></div>
	<script type="text/javascript">
		// <![CDATA[
		var d = new Date();
		d.setFullYear(<?php echo $this->dataFine['year'] . ',' . ($this->dataFine['month'] -1) . ',' . $this->dataFine['day']; ?>);
		d.setHours(<?php echo $this->dataFine['hour'] . ',' . $this->dataFine['minute'] . ',' . $this->dataFine['second']; ?>);
		$('#countdown div').countdown({
			msgFormat: '<span class="number">%d</span> [giorno|giorni], <span class="number">%h</span> [ora|ore] <span class="number">%m</span> [minuto|minuti] e <span class="number">%s</span> [secondo|secondi]',
			date: d,
			msgNow:'Tempo scaduto'
		});
		// ]]>
	</script>
	<?php endif; ?>
</div>
