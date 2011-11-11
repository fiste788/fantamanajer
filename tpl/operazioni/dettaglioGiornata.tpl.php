<form class="column last" action="<?php echo Links::getLink('dettaglioGiornata'); ?>" method="post">
	<fieldset class="no-margin fieldset max-large">
		<input type="hidden" name="p" value="<?php echo $this->request->get('p');?>" />
		<h3 class="no-margin">Seleziona la giornata</h3>
		<select name="giornata" onchange="this.form.submit();">
			<?php if(!$this->request->has('giornata')): ?><option></option><?php endif; ?>
			<?php for($i = $this->giornate ; $i > 0 ; $i--): ?>
				<option<?php echo ($this->request->get('giornata') == $i) ? ' selected="selected"' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
			<?php endfor; ?>
		</select>
		<h3 class="no-margin">Seleziona la squadra</h3>
		<select name="squadra" onchange="this.form.submit();">
			<?php if(!$this->request->has('squadra')): ?><option></option><?php endif; ?>
			<?php foreach($this->squadre as $key => $val): ?>
				<option<?php echo ($this->request->get('squadra') == $val->id) ? ' selected="selected"' : ''; ?> value="<?php echo $val->id; ?>"><?php echo $val->nomeSquadra; ?></option>
			<?php endforeach; ?>
		</select>
	</fieldset>
</form>
