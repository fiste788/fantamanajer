<?php if($this->eventi != FALSE): ?>
	<?php foreach($this->eventi as $key =>$val): ?>
		<h4>
		<?php if($val['tipo'] != 2 && $_SESSION['logged']): ?>
			<a href="<?php echo $val['link']; ?>">
		<?php endif;?>
		<?php echo $val['titolo']; ?>  <em>(<?php echo $val['data']; ?>)</em>
		<?php if($val['tipo'] != 2 && $_SESSION['logged']): ?>
			</a>
		<?php endif;?>
		</h4>
		<?php if(isset($val['content'])): ?>
			<p><?php echo nl2br($val['content']); ?></p>
		<?php endif; ?>
	<?php endforeach; ?>
<?php else: ?>
	<p>Nessun evento</p>
<?php endif; ?>
