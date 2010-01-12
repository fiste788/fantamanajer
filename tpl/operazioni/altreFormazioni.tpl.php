<form class="right last" action="<?php echo $this->linksObj->getLink('altreFormazioni'); ?>" method="post">
	<fieldset class="no-margin fieldset">
		<h3 class="no-margin">Guarda le altre formazioni</h3>
		<input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
		<?php if(empty($this->formazioniImpostate)): ?>
			<select name="squadra" disabled="disabled">
				<option>Nessuna form. impostata</option>
		<?php else:?>
			<select name="squadra" onchange="this.form.submit();">
			<?php foreach($this->formazioniImpostate as $key => $val): ?>
				<option<?php if($this->squadra == $val->idUtente) echo ' selected="selected"'; ?> value="<?php echo $val->idUtente; ?>"><?php echo $val->nome; ?></option>
			<?php endforeach; ?>
		<?php endif; ?>
		</select>
	</fieldset>
	<fieldset class="no-margin fieldset max-large">
		<h3 class="no-margin">Guarda la formazione della giornata</h3>
		<select name="giornata" onchange="this.form.submit();">
			<?php for($j = GIORNATA ; $j  > 0 ; $j--): ?>
				<option<?php if($this->giornata == $j) echo ' selected="selected"'; ?>><?php echo $j; ?></option>
			<?php endfor; ?>
		</select>
	</fieldset>
</form>
