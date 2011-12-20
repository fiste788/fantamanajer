<?php if($this->message->show): ?>
	<?php switch($this->message->level):
		 case 0: ?>
			<div title="Clicca per nascondere" id="messaggio" class="alert-message success"><?php echo $this->message->text; ?></div>
		<?php break; case 1: ?>
			<div title="Clicca per nascondere" id="messaggio" class="alert-message notice"><?php echo $this->message->text; ?></div>
		<?php break; case 2: ?>
			<div title="Clicca per nascondere" id="messaggio" class="alert-message error"><?php echo $this->message->text; ?></div>
	<?php endswitch; ?>
<?php endif; ?>
