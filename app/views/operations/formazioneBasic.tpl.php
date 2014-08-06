<form class="form-inline" action="<?php echo Links::getLink('formazioneBasic'); ?>" method="post">
	<fieldset>
		<label>Modulo:</label>
		<select class="input-small" name="modulo" onchange="this.form.submit()">
			<?php if(!isset($this->mod)): ?><option></option><?php endif; ?>
			<option value="1-4-4-2"<?php if ($this->mod == '1-4-4-2') echo ' selected="selected"'; ?>>4-4-2</option>
			<option value="1-3-5-2"<?php if ($this->mod == '1-3-5-2') echo ' selected="selected"'; ?>>3-5-2</option>
			<option value="1-3-4-3"<?php if ($this->mod == '1-3-4-3') echo ' selected="selected"'; ?>>3-4-3</option>
			<option value="1-4-5-1"<?php if ($this->mod == '1-4-5-1') echo ' selected="selected"'; ?>>4-5-1</option>
			<option value="1-4-3-3"<?php if ($this->mod == '1-4-3-3') echo ' selected="selected"'; ?>>4-3-3</option>
			<option value="1-5-4-1"<?php if ($this->mod == '1-5-4-1') echo ' selected="selected"'; ?>>5-4-1</option>
			<option value="1-5-3-2"<?php if ($this->mod == '1-5-3-2') echo ' selected="selected"'; ?>>5-3-2</option>
		</select>
	</fieldset>
</form>
