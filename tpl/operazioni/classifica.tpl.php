<form class="column last" action="<?php echo Links::getLink('classifica'); ?>" method="post">
	<fieldset class="no-margin fieldset max-large">
		<h3 class="no-margin">Guarda la classifica alla giornata</h3>
		<select name="giornata" onchange="this.form.submit();">
			<?php for($j = $this->giornate ; $j  > 0 ; $j--): ?>
				<option<?php echo ($this->getGiornata == $j) ? ' selected="selected"' : ''; ?>><?php echo $j; ?></option>
			<?php endfor; ?>
		</select>
	</fieldset>
</form>
