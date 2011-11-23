<form class="column last" action="<?php echo Links::getLink('classifica'); ?>" method="post">
	<fieldset class="no-margin fieldset max-large">
		<label class="no-margin">Guarda la classifica alla giornata</label>
		<select name="idGiornata" onchange="this.form.submit();">
			<?php for($j = $this->giornate ; $j  > 0 ; $j--): ?>
				<option<?php echo ($this->getGiornata == $j) ? ' selected="selected"' : ''; ?>><?php echo $j; ?></option>
			<?php endfor; ?>
		</select>
	</fieldset>
</form>
