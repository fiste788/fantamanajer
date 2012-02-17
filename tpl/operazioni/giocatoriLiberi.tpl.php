<form action="<?php echo Links::getLink('giocatoriLiberi'); ?>" method="post">
	<fieldset>
		<input type="hidden" name="p" value="<?php echo $this->request->get('p');?>" />
		<label for="ruolo">Seleziona il ruolo:</label>
		<select id="ruolo" name="ruolo" onchange="this.form.submit();">
			<?php foreach($this->ruoli as $key => $val): ?>
				<option<?php echo ($this->request->get('ruolo') == $key) ? ' selected="selected"' : ''; ?> value="<?php echo $key ?>"><?php echo $val; ?></option>
			<?php endforeach ?>
		</select>
		<label for="sufficenza">Soglia sufficienza:</label>
		<input id="sufficenza" maxlength="3" name="sufficenza" type="text" class="small" value="<?php echo ($this->validFilter) ? $this->request->get('sufficenza') : 6; ?>" />
		<label for="partite">Soglia partite:</label>
		<input id="partite" maxlength="2" name="partite" type="text" class="small" value="<?php echo ($this->validFilter) ? $this->request->get('partite') : (floor((GIORNATA - 1) / 2) + 1); ?>" />
		<input class="btn-primary" type="submit" value="OK"/>
	</fieldset>
</form>
