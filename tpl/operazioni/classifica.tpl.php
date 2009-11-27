<form class="column last" action="<?php echo $this->linksObj->getLink('classifica'); ?>" method="post">
	<fieldset class="no-margin fieldset max-large">
		<h3 class="no-margin">Guarda la classifica alla giornata</h3>
		<select name="giorn" onchange="this.form.submit();">
			<?php for($j = $this->giornate ; $j  > 0 ; $j--): ?>
				<option<?php if($this->getGiornata == $j) echo 'selected="selected"'; ?>><?php echo $j; ?></option>
			<?php endfor; ?>
		</select>
	</fieldset>
</form>
