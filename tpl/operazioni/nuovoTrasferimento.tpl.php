<form class="form-inline" action="<?php echo Links::getLink('nuovoTrasferimento'); ?>" method="post">
	<fieldset>
	<?php if($_SESSION['roles'] == '2'): ?>
		<input type="hidden" name="p" value="<?php echo $this->request->get('p'); ?>" />
		<label for="lega">Seleziona la lega:</label>
		<select name="lega">
			<?php if($this->lega == NULL): ?><option></option><?php endif; ?>
			<?php foreach($this->elencoLeghe as $key => $val): ?>
				<option<?php echo ($this->lega == $val->id) ? ' selected="selected"' : ''; ?> value="<?php echo $val->id; ?>"><?php echo $val->nome; ?></option>
			<?php endforeach ?>
		</select>
	<?php endif; ?>
		<label for="squadra">Seleziona la squadra:</label>
		<select <?php echo (empty($this->elencoSquadre)) ? 'disabled="disabled"' : ''; ?> onchange="this.form.submit();" name="squadra">
			<?php if($this->squadra == NULL): ?><option></option><?php endif; ?>
			<?php foreach($this->elencoSquadre as $key => $val): ?>
				<option<?php if($this->squadra == $val->id) echo ' selected="selected"'; ?> value="<?php echo $val->id; ?>"><?php echo $val->nomeSquadra; ?></option>
			<?php endforeach ?>
		</select>
	</fieldset>
</form>