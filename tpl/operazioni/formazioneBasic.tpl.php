<form class="column last" name="form_modulo" action="<?php echo $this->linksObj->getLink('formazioneBasic'); ?>" method="post">
	<fieldset id="modulo" class="no-margin fieldset">
		<h3 class="no-margin">Seleziona il modulo:</h3>
		<select name="mod" onchange="this.form.submit();">
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
<form class="right last" name="formazione_other" action="<?php echo $this->linksObj->getLink('altreFormazioni'); ?>" method="post">
	<fieldset class="no-margin fieldset">
	  <input type="hidden" name="p" value="formazioniAll" />
		<h3 class="no-margin">Guarda le altre formazioni</h3>
		<?php if(empty($this->formazioniImpostate)): ?>
			<select name="squadra" disabled="disabled">
				<option>Nessuna form. impostata</option>
		<?php else:?>
			<select name="squadra" onchange="this.form.submit();">
				<option value="<?php echo $_SESSION['idSquadra']; ?>"></option>
			<?php foreach($this->formazioniImpostate as $key => $val): ?>
				<option <?php if($this->squadra == $val->idUtente) echo ' selected="selected"'; ?> value="<?php echo $val->idUtente; ?>"><?php echo $val->nome; ?></option>
			<?php endforeach;?>
		<?php endif; ?>
		</select>
	</fieldset>
	<fieldset class="no-margin fieldset max-large">
		<h3 class="no-margin">Guarda la formazione della giornata</h3>
			<select name="giornata" onchange="this.form.submit();">
				<?php for($j = GIORNATA ; $j  > 0 ; $j--): ?>
					<option <?php if(GIORNATA == $j) echo ' selected="selected"'; ?>><?php echo $j; ?></option>
				<?php endfor; ?>
		</select>
	</fieldset>
</form>