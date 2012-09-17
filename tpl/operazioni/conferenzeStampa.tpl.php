<form class="form-inline" action="<?php echo Links::getLink('conferenzeStampa'); ?>" method="post">
	<fieldset>
		<label for="giornata">Seleziona la giornata:</label>
		<select id="giornata" name="giornata" onchange="this.form.submit();">
		<?php if($this->giornateWithArticoli != FALSE): ?>
			<?php foreach ($this->giornateWithArticoli as $key => $val): ?>
				<option<?php echo ($val == $this->giornata) ? ' selected="selected"' : ''; ?>><?php echo $val; ?></option>
			<?php endforeach; ?>
		<?php endif; ?>
		</select>
		<?php if($_SESSION['logged']): ?>
			<a class="btn btn-primary" href="<?php echo Links::getLink('modificaConferenza'); ?>"><i class="icon-plus icon-white"></i>Nuova conferenza stampa</a>
		<?php endif; ?>
	</fieldset>
</form>
