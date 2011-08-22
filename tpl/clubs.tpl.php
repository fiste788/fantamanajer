<?php foreach($this->elencoclub as $key => $val): ?>
	<div class="box column last">
		<a href="<?php echo Links::getLink('dettaglioClub',array('club'=>$val->idClub)); ?>" class="column fancybox" title="Rosa <?php echo $val->partitivo." ".$val->nomeClub?>">		
		<?php if(file_exists(CLUBSDIR . $val->idClub . '.png'))
			$pathshield=CLUBSURL . $val->idClub . '.png';
		else
			$pathshield=CLUBSURL . $val->idClub . '.gif';		
		?>                                          
		<img height="50%" width="50%" alt="<?php echo $val->idClub; ?>" src="<?php echo $pathshield	; ?>" W/>
		<h3><?php echo $val->nomeClub; ?></a></h3>	
		<div class="column data">

		</div>
		<ul class="column link">

		</ul>
	</div>
<?php endforeach; ?>
