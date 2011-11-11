<?php if($this->message->show || isset($this->generalMessage)): ?>
	<div id="messaggioContainer" title="Clicca per nascondere">
		<?php if(isset($this->generalMessage)): ?>
			<div title="Clicca per nascondere" class="messaggio error"><?php echo $this->generalMessage; ?></div>
		<?php endif; ?>
		<?php if($this->message->show): ?>
			<?php switch($this->message->level):
				 case 0: ?>
					<div class="messaggio success"><?php echo $this->message->text; ?></div>
				<?php break; case 1: ?>
					<div class="messaggio notice"><?php echo $this->message->text; ?></div>
				<?php break; case 2: ?>
					<div class="messaggio error"><?php echo $this->message->text; ?></div>
			<?php endswitch; ?>
		<?php endif; ?>
	</div>
<?php endif; ?>