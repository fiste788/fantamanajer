<div id="squadre" class="main-content">
<?php foreach($this->elencosquadre as $key => $val): ?>
	<div class="box-squadra column last">
	<?php if(file_exists(UPLOADDIR. $val['idUtente'].'-small.jpg')): ?>
		<a rel="group" title="<?php echo $val['nome']; ?>" class="fancybox" href="<?php echo UPLOADIMGURL.$val['idUtente'].'-original.jpg'; ?>" >
			<img class="logo" alt="<?php echo $val['idUtente']; ?>" src="<?php echo UPLOADIMGURL. $val['idUtente'].'-med.jpg'; ?>" title="Clicca per vedere l'immagine ingrandita" />
		</a>
	<?php else: ?>
		<img class="logo column last" alt="<?php echo $val['idUtente']; ?>" src="<?php echo IMGSURL.'no-foto.png'; ?>" title="<?php echo $val['nome']; ?>" />
	<?php endif; ?>
		<h3><a href="<?php echo $this->linksObj->getLink('dettaglioSquadra',array('squadra'=>$val['idUtente'])); ?>" title="Maggiori informazioni"><?php echo $val['nome']; ?></a></h3>	
		<div class="column data">
			<div>Proprietario: <?php echo $val['username']; ?></div>
			<div>Pos. in classifica: <?php echo $this->posizioni[$val['idUtente']]; ?></div>
			<div>Giornate vinte: <?php echo $val['giornateVinte']; ?></div>
		</div>
		<ul class="column link">
			<li>
				<a href="<?php echo $this->linksObj->getLink('trasferimenti',array('squadra'=>$val['idUtente'])); ?>" title="Trasferimenti">Trasferimenti</a>
			</li>
			<li>
				<a href="<?php echo $this->linksObj->getLink('altreFormazioni',array('squadra'=>$val['idUtente'],'giornata'=>GIORNATA)); ?>" title="Formazione">Formazione</a>
			</li>
			<li>
				<a href="<?php echo $this->linksObj->getLink('dettaglioGiornata',array('giornata'=>$this->ultimaGiornata,'squadra'=>$val['idUtente'])); ?>" title="Ultima giornata">Ultima giornata</a>
			</li>
			<li>
				<a href="<?php echo $this->linksObj->getLink('conferenzeStampa',array('giornata'=>0,'squadra'=>$val['idUtente'])); ?>" title="Conferenze stampa">Conferenze stampa</a>
			</li>
		</ul>
	</div>
<?php unset($appo); endforeach; ?>
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
<script type="text/javascript">
	$(document).ready(function() { 
		$(".fancybox").fancybox({
			'zoomSpeedIn': 500,
			'zoomSpeedOut' : 500,
			'imageScale' : true,
			'zoomOpacity' : true,
			'overlayShow' : true,
			'overlayOpacity' : 0.6,
			'centerOnScroll' : true,
			'padding' : 0,
			'hideOnContentClick' : false
			})
	}); 
</script>
