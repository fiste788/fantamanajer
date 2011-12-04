<form action="<?php echo Links::getLink('newsletter'); ?>" method="post">
	<fieldset>
		<label for="lega">Seleziona la lega</label>
		<select name="lega" onchange="this.form.submit();">
			<?php if(!isset($this->lega)): ?><option></option><?php endif; ?>
			<option<?php echo(isset($this->lega) && $this->lega == 0) ? ' selected="selected"' : ''; ?> value="0">Tutte le leghe</option>
			<?php foreach($this->elencoLeghe as $key => $val): ?>
				<option<?php echo ($this->lega == $val->idLega) ? ' selected="selected"' : ''; ?> value="<?php echo $val->idLega; ?>"><?php echo $val->nomeLega; ?></option>
			<?php endforeach;?>
		</select>
	</fieldset>
</form>
