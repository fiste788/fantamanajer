<form class="column last" action="<?php echo Links::getLink('conferenzeStampa'); ?>" method="post">
	<fieldset class="no-margin fieldset">
		<label for="giornata" class="no-margin">Seleziona la giornata:</label>
		<select name="giornata" onchange="this.form.submit();">
		<?php if($this->giornateWithArticoli != FALSE): ?>
			<?php foreach ($this->giornateWithArticoli as $key => $val): ?>
				<option<?php echo ($val == $this->request->get('giornata')) ? ' selected="selected"' : ''; ?>><?php echo $val; ?></option>
			<?php endforeach; ?>
		<?php endif; ?>
		</select>
	</fieldset>
</form>
