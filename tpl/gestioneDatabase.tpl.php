<div id="gestioneDb" class="main-content">
	<ul>
		<li><a href="<?php echo $this->linksObj->getLink('gestioneDatabase',array('action'=>'optimize')); ?>">Ottimizza</a></li>
		<?php if(substr($_SERVER['REMOTE_ADDR'],0,7) == '192.168' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_NAME'] == 'localhost'): ?>
		<li><a href="<?php echo $this->linksObj->getLink('gestioneDatabase',array('action'=>'sincronize')); ?>">Sincronizza</a></li>
		<?php endif; ?>
	</ul>
	<form action="<?php echo $this->linksObj->getLink('gestioneDatabase'); ?>" name="eseguiQuery" method="post">
		<p class="no-margin">Inserisci quì la tua query</p>
		<textarea name="query" rows="30" cols="100"><?php if(isset($this->sql)) echo $this->sql; ?></textarea>
		<input class="submit dark" type="submit" value="Eegui" />
	</form>
</div>
