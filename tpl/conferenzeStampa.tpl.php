<?php if(!empty($this->articoli)):?>
	<?php foreach($this->articoli as $key => $val): ?>
		<div class="articolo">
			<?php if($_SESSION['logged'] && $_SESSION['idUtente'] == $val->idUtente): ?>
				<a class="edit column last" href="<?php echo Links::getLink('modificaConferenza',array('id'=>$val->id)); ?>" title="Modifica">M</a>
			<?php endif; ?>
			<em>
				<span class="right"><?php echo $val->insertDate->format("Y-m-d H:i:s"); ?></span>
				<span class="right last"><?php echo $val->username; ?></span>
			</em>
			<h3 class="title"><?php echo $val->title; ?></h3>
			<?php if(isset($val->abstract)): ?><div class="abstract"><?php echo $val->abstract; ?></div><?php endif; ?>
			<div class="text"><?php echo nl2br($val->text); ?></div>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	Non sono presenti articoli
<?php endif; ?>
