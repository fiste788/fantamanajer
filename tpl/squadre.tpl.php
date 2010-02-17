<?php foreach($this->elencosquadre as $key => $val): ?>
	<div class="box column last">
	<?php if(file_exists(UPLOADDIR . $val->idUtente . '-med.jpg')): ?>
		<a rel="group" title="<?php echo $val->nome; ?>" class="column fancybox" href="<?php echo UPLOADURL . $val->idUtente . '-original.jpg'; ?>" >
			<img <?php $appo = getimagesize(UPLOADDIR . $val->idUtente . '-med.jpg'); echo $appo[3]; ?> alt="<?php echo $val->idUtente; ?>" src="<?php echo UPLOADURL . $val->idUtente . '-med.jpg'; ?>" title="Clicca per vedere l'immagine ingrandita" />
		</a>
	<?php else: ?>
		<img height="93" width="124" class="logo column last" alt="<?php echo $val->idUtente; ?>" src="<?php echo IMGSURL . 'no-foto.png'; ?>" title="<?php echo $val->nome; ?>" />
	<?php endif; ?>
		<h3><a href="<?php echo $this->linksObj->getLink('dettaglioSquadra',array('squadra'=>$val->idUtente)); ?>" title="Maggiori informazioni"><?php echo $val->nome; ?></a></h3>	
		<div class="column data">
			<div>Proprietario: <?php echo $val->username; ?></div>
			<div>Pos. in classifica: <?php echo $this->posizioni[$val->idUtente]; ?></div>
			<div>Giornate vinte: <?php echo $val->giornateVinte; ?></div>
		</div>
		<ul class="column link">
			<li>
				<a href="<?php echo $this->linksObj->getLink('trasferimenti',array('squadra'=>$val->idUtente)); ?>" title="Trasferimenti">Trasferimenti</a>
			</li>
			<li>
				<a href="<?php echo $this->linksObj->getLink('altreFormazioni',array('squadra'=>$val->idUtente,'giornata'=>GIORNATA)); ?>" title="Formazione">Formazione</a>
			</li>
			<li>
				<a href="<?php echo $this->linksObj->getLink('dettaglioGiornata',array('giornata'=>$this->ultimaGiornata,'squadra'=>$val->idUtente)); ?>" title="Ultima giornata">Ultima giornata</a>
			</li>
		</ul>
	</div>
<?php endforeach; ?>
