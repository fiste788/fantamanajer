<form action="<?php echo Links::getLink('dettaglioGiornata'); ?>" method="post">
	<fieldset>
		<input type="hidden" name="p" value="<?php echo $this->request->get('p');?>" />
		<label for="giornata">Seleziona la giornata:</label>
		<select name="giornata" onchange="this.form.submit();">
			<?php if(!$this->request->has('giornata')): ?><option></option><?php endif; ?>
			<?php for($i = $this->giornate ; $i > 0 ; $i--): ?>
				<option<?php echo ($this->request->get('giornata') == $i) ? ' selected="selected"' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
			<?php endfor; ?>
		</select>
		<label for="squadra">Seleziona la squadra:</label>
		<select name="squadra" onchange="this.form.submit();">
			<?php if(!$this->request->has('squadra')): ?><option></option><?php endif; ?>
			<?php foreach($this->squadre as $key => $val): ?>
				<option<?php echo ($this->request->get('squadra') == $val->id) ? ' selected="selected"' : ''; ?> value="<?php echo $val->id; ?>"><?php echo $val->nomeSquadra; ?></option>
			<?php endforeach; ?>
		</select>
	</fieldset>
</form>
