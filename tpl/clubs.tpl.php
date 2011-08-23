<?php foreach($this->elencoclub as $key => $val): ?>
	<a class="column" style="width:140px;text-align:center;margin:25px;" href="<?php echo Links::getLink('dettaglioClub',array('club'=>$val->idClub)); ?>" class="column" title="Rosa <?php echo $val->partitivo." ".$val->nomeClub?>">
		<img alt="<?php echo $val->idClub; ?>" src="<?php echo CLUBSURL . $val->idClub . '.png'; ?>" />
	</a>
<?php endforeach; ?>
