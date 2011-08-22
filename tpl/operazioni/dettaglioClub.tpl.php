<form class="column last" action="<?php echo Links::getLink('dettaglioClub'); ?>" method="post">
	<fieldset class="no-margin fieldset">
		<input type="hidden" value="<?php echo $_GET['p'];?>" />
		<h3 class="no-margin">Seleziona il club:</h3>
		<select name="club" onchange="this.form.submit();">
			<?php if($this->elencoClub != FALSE): ?>
			<?php foreach ($this->elencoClub as $key => $val): ?>
				<option<?php echo ($key == $this->idClub) ? ' selected="selected"' : ''; ?> value="<?php FirePHP::getInstance()->log($key);echo $key;?>"><?php echo $val->nomeClub ?></option>
			<?php endforeach; ?>
			<?php endif; ?>
		</select>
	</fieldset>
</form>
