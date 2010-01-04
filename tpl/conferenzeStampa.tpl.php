<?php $i = 0; ?>
<?php if(isset($this->articoli) && !empty($this->articoli)):?>
	<?php foreach($this->articoli as $key => $val): ?>
		<?php if($i % 2 == 0): ?>
			<div class="riga column last">
		<?php endif; ?>
		<?php $i++; ?>		
		<div class="box column<?php if($i % 2 == 0) echo ' last'; ?>">
			<?php if(isset($_SESSION['idSquadra']) && $_SESSION['idSquadra'] == $val->idSquadra): ?>
				<a class="edit column last" href="<?php echo $this->linksObj->getLink('modificaConferenza',array('a'=>'edit','id'=>$val->idArticolo)); ?>" title="Modifica"></a>
				<a class="remove column" href="<?php echo $this->linksObj->getLink('modificaConferenza',array('a'=>'cancel','id'=>$val->idArticolo)); ?>" title="Cancella"></a>
			<?php endif; ?>
			<em>
				<span class="column last"><?php echo $val->username; ?></span>
				<span class="right"><?php echo $val->insertDate; ?></span>
			</em>
			<h3 class="title"><?php echo $val->title; ?></h3>
			<?php if(isset($val->abstract)): ?><div class="abstract"><?php echo $val->abstract; ?></div><?php endif; ?>
			<div class="text"><?php echo nl2br($val->text); ?></div>
		</div>
		<?php if($i % 2 == 0 || $i == count($this->articoli)): ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
	<div>&nbsp;</div>
<?php else: ?>
	Non sono presenti articoli
<?php endif; ?>
