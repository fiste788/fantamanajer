<form action="<?php echo Links::getLink('classifica'); ?>" method="post">
	<fieldset>
		<label for="giornata">Guarda la classifica alla giornata</label>
		<select id="giornata" name="giornata" onchange="this.form.submit();">
			<?php for($j = $this->giornate ; $j  > 0 ; $j--): ?>
				<option<?php echo ($this->getGiornata == $j) ? ' selected="selected"' : ''; ?>><?php echo $j; ?></option>
			<?php endfor; ?>
		</select>
	</fieldset>
</form>
