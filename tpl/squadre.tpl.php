<?php foreach($this->elencoSquadre as $key => $val): ?>
	<div class="box column last">
	<?php if(file_exists(UPLOADDIR . $val->id . '-med.jpg')): ?>
		<a rel="group" title="<?php echo $val->nome; ?>" class="column fancybox" href="<?php echo UPLOADURL . $val->id . '-original.jpg'; ?>" >
			<img <?php $appo = getimagesize(UPLOADDIR . $val->id . '-med.jpg'); echo $appo[3]; ?> alt="<?php echo $val->id; ?>" src="<?php echo UPLOADURL . $val->id . '-med.jpg'; ?>" title="Clicca per vedere l'immagine ingrandita" />
		</a>
	<?php else: ?>
		<img height="93" width="124" class="logo column last" alt="<?php echo $val->id; ?>" src="<?php echo IMGSURL . 'no-foto.png'; ?>" title="<?php echo $val->nomeSquadra; ?>" />
	<?php endif; ?>
		<h3><a href="<?php echo Links::getLink('dettaglioSquadra',array('squadra'=>$val->id)); ?>" title="Maggiori informazioni"><?php echo $val->nomeSquadra; ?></a></h3>
		<div class="column data">
			<div>Proprietario: <?php echo $val->username; ?></div>
			<?php if(!empty($this->posizioni)): ?>
				<div>Pos. in classifica: <?php echo $this->posizioni[$val->id]; ?></div>
			<?php endif; ?>
			<div>Giornate vinte: <?php echo ($val->giornateVinte != NULL) ? $val->giornateVinte : 0; ?></div>
		</div>
		<ul class="column link">
			<li>
				<a href="<?php echo Links::getLink('trasferimenti',array('id'=>$val->id)); ?>" title="Trasferimenti">Trasferimenti</a>
			</li>
			<li>
				<a href="<?php echo Links::getLink('altreFormazioni',array('squadra'=>$val->id,'giornata'=>GIORNATA)); ?>" title="Formazione">Formazione</a>
			</li>
			<?php if(GIORNATA > 1): ?>
			<li>
				<a href="<?php echo Links::getLink('dettaglioGiornata',array('giornata'=>$this->ultimaGiornata,'squadra'=>$val->id)); ?>" title="Ultima giornata">Ultima giornata</a>
			</li>
			<?php endif; ?>
		</ul>
	</div>
<?php endforeach; ?>
