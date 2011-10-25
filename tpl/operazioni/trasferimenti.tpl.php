<form class="column last" action="<?php echo Links::getLink('trasferimenti'); ?>" method="post">
	<fieldset class="no-margin fieldset  max-large">
		<h3 class="no-margin">Seleziona la squadra:</h3>
		<input type="hidden" name="p" value="<?php echo $this->request->get('p'); ?>" />
		<select name="id" onchange="this.form.submit();">
		<?php foreach($this->elencoSquadre as $key => $val): ?>
			<option<?php if($this->request->get('p') == $val->id) echo ' selected="selected"'; ?> value="<?php echo $val->id; ?>"><?php echo $val->nomeSquadra; ?></option>
		<?php endforeach ?>
		</select>
	</fieldset>
</form>
