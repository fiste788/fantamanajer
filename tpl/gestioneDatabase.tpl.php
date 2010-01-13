<ul>
	<li><a href="<?php echo $this->linksObj->getLink('gestioneDatabase',array('action'=>'optimize')); ?>">Ottimizza</a></li>
	<?php if(LOCAL): ?>
	<li><a href="<?php echo $this->linksObj->getLink('gestioneDatabase',array('action'=>'sincronize')); ?>">Sincronizza</a></li>
	<?php endif; ?>
</ul>
<form action="<?php echo $this->linksObj->getLink('gestioneDatabase'); ?>" method="post">
	<fieldset class="no-margin no-padding">
		<p class="no-margin">Inserisci quì la tua query</p>
		<textarea name="query" rows="30" cols="100"><?php if(isset($this->sql)) echo $this->sql; ?></textarea>
		<input class="submit dark" type="submit" value="Eegui" />
	</fieldset>
</form>
