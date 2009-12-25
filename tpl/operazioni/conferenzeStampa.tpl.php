<form class="column last" action="<?php echo $this->linksObj->getLink('conferenzeStampa'); ?>" method="post">
	<fieldset class="no-margin fieldset">
		<input type="hidden" value="confStampa" name="p" />
		<h3 class="no-margin">Seleziona la giornata:</h3>
		<select name="giornata" onchange="this.form.submit();">
		<?php if($this->giornateWithArticoli != FALSE): ?>
			<?php foreach ($this->giornateWithArticoli as $key => $val): ?>
				<option<?php if($val == $this->idGiornata) echo ' selected="selected"'; ?>><?php echo $val; ?></option>
			<?php endforeach; ?>
		<?php endif; ?>
		</select>
	</fieldset>
</form>
