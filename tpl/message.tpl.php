<?php if($this->message->show): ?>
	<?php switch($this->message->level):
		 case 0: ?>
			<div title="Clicca per nascondere" id="messaggio" class="alert alert-success"><?php echo $this->message->text; ?></div>
		<?php break; case 1: ?>
			<div title="Clicca per nascondere" id="messaggio" class="alert alert-notice"><?php echo $this->message->text; ?></div>
		<?php break; case 2: ?>
			<div title="Clicca per nascondere" id="messaggio" class="alert alert-error"><?php echo $this->message->text; ?></div>
	<?php endswitch; ?>
<?php endif; ?>
