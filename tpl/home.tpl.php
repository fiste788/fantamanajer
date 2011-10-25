<?php $i = 0; ?>
<?php if(GIORNATA == 1): ?>
<h2 class="no-margin center">Ricordati di impostare il nome della squadra!</h2>
<p class="center">Lo trovi nella pagina rosa e lo puoi cambiare entro la seconda giornata di campionato</p>
<?php else: ?>
<div id="bestPlayer">
	<h3>Migliori giocatori giornata <?php echo $this->giornata; ?></h3>
	<?php foreach($this->bestPlayer as $ruolo=>$giocatori): ?>
		<div id="<?php echo $ruolo ?>" class="column">
			<?php if($giocatori != FALSE): ?>
			<?php foreach($giocatori as $key=>$val): ?>
			<?php if($key == 0): ?>
				<a href="<?php echo Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->id)); ?>">
					<?php if(file_exists(PLAYERSDIR . $val->id . '.jpg')): ?>
						<img alt="<?php echo $val->cognome . ' ' . $val->nome; ?>" src="<?php echo PLAYERSURL . $val->id . '.jpg'; ?>" />
					<?php else: ?>
						<img alt="Foto sconosciuta" src="<?php echo IMGSURL . 'no-photo.png'; ?>" />
					<?php endif; ?>
				</a>
				<h4><a href="<?php echo Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->id)); ?>"><?php echo $val->cognome . " " . $val->nome . ": " . $val->punti; ?></a></h4>
			<?php else: ?>
				<a class="neutral" href="<?php echo Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->id)); ?>"><?php echo $val->cognome . " " . $val->nome . ": " . $val->punti; ?></a><br />
			<?php endif; ?>
			<?php endforeach; ?>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>
<?php if($this->eventi != FALSE): ?>
<div id="eventi" class="column last">
	<h3 class="column">Ultimi eventi</h3>
	<div class="column last" style="clear:both;">
		<ul>
		<?php foreach($this->eventi as $key =>$val): ?>
			<li class="eventoHome">
				<span><?php echo $val->data->format("Y-m-d H:i:s"); ?></span>
				<a<?php echo ($val->tipo != 2) ? ' href="' . $val->link . '"' : ''; ?> title="<?php echo $val->content; ?>"><?php echo $val->titolo; ?></a>
			</li>
		<?php endforeach; ?>
		</ul>
		<a class="right" href="<?php echo Links::getLink('feed'); ?>">Vedi tutti gli eventi &raquo;</a>
	</div>
</div>
<?php endif; ?>
<?php if($this->articoli != FALSE) :?>
<div id="conferenzeStampa" class="column last">
	<h3>Ultime news</h3>
	<?php foreach($this->articoli as $key => $val): ?>
		<div class="box column<?php if($i % 2 == 0) echo ' last'; ?>">
			<?php if(isset($_SESSION['idUtente']) && $_SESSION['idUtente'] == $val->idUtente): ?>
				<a class="edit column last" href="<?php echo Links::getLink('modificaConferenza',array('a'=>'edit','id'=>$val->idArticolo)); ?>" title="Modifica"></a>
				<a class="remove column" href="<?php echo Links::getLink('modificaConferenza',array('a'=>'cancel','id'=>$val->idArticolo)); ?>" title="Cancella"></a>
			<?php endif; ?>
			<em>
				<span class="column last"><?php echo $val->username; ?></span>
				<span class="right"><?php echo $val->insertDate->format("Y-m-d H:i:s"); ?></span>
			</em>
			<h3 class="title"><?php echo $val->title; ?></h3>
			<?php if(isset($val->abstract)): ?><div class="abstract"><?php echo $val->abstract; ?></div><?php endif; ?>
			<div class="text"><?php echo nl2br($val->text); ?></div>
		</div>
<?php endforeach; ?>
</div>
<?php endif; ?>