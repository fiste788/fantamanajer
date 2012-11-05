<?php if(isset($this->lega)): ?>
<form name="newsletter" class="column last" action="<?php echo Links::getLink('newsletter'); ?>" method="post">
	<fieldset class="column last">
		<input type="hidden" name="lega" value="<?php echo $this->lega; ?>">
		<div class="formbox">
			<label for="oggetto">Oggetto:</label>
			<input class="text" id="oggetto" type="text" name="object" maxlength="30"<?php echo (isset($_POST['object'])) ? ' value="' . $_POST['object'] .'"' : ''; ?> />
		</div>
		<?php if($this->lega == 0): ?>
		<div class="formbox">
			<label for="selezione">Leghe:</label>
			<select id="selezione" name="selezione[]" multiple="multiple" size="6" class="column newsletterBox">
				<?php foreach($this->elencoLeghe as $key => $val): ?>
					<option<?php echo (isset($_POST['selezione']) && array_search($val->idLega,$_POST['selezione']) !== FALSE) ? ' selected="selected"' : ''; ?> value="<?php echo $val->idLega; ?>"><?php echo $val->nomeLega; ?></option>
				<?php endforeach; ?>
			</select>
			<div class="selectAll column">
				<a href="#" onclick="setSelectOptions(true)">Seleziona tutto</a> /
				<a href="#" onclick="setSelectOptions(false)">Deseleziona tutto</a>
			</div>
		</div>
		<?php else: ?>
		<div class="formbox">
			<label for="selezione">Squadre:</label>
			<?php if($this->elencoSquadre == FALSE): ?>
			<select disabled="disabled" id="selezione" multiple="multiple" size="6" class="column newsletterBox">
				<option value="NULL">Nessuna squadra presente</option>
			<?php else: ?>
			<select id="selezione" name="selezione[]" multiple="multiple" size="6" class="column newsletterBox">
				<?php foreach($this->elencoSquadre as $key => $val): ?>
					<option<?php echo (isset($_POST['selezione']) && array_search($val->idUtente,$_POST['selezione']) !== FALSE) ? ' selected="selected"' : ''; ?> value="<?php echo $val->idUtente; ?>"><?php echo $val->nome; ?></option>
				<?php endforeach; ?>
			<?php endif; ?>
			</select>
			<?php if($this->elencoSquadre != FALSE): ?>
			<div class="selectAll column">
				<a onclick="setSelectOptions(true)">Seleziona tutto</a> /
				<a onclick="setSelectOptions(false)">Deseleziona tutto</a>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<div class="formbox">
			<label for="testo">Testo:</label>
			<textarea class="column" id="testo" rows="15" cols="50" name="text" onkeyup="return ismaxlength(this, 1000);"><?php echo (isset($_POST['text'])) ? $_POST['text'] : ''; ?></textarea>
			<input class="column text disabled" id="textCont" type="text" disabled="disabled" value="<?php echo (isset($_POST['text'])) ? 1000 - mb_strlen($_POST['text']) : '1000'; ?>" />
		</div>
		<div class="formbox">
			<label>Tipologia:</label>
			<input class="column radio" type="radio" value="C" name="type"<?php echo (isset($_POST['type']) && $_POST['type'] == 'C') ? ' checked="checked"' : ''; ?>><label>Comunicazione</label>
			<input class="column radio" type="radio" value="N" name="type"<?php echo (isset($_POST['type']) && $_POST['type'] == 'N') ? ' checked="checked"' : ''; ?>><label>Newsletter</label>
		</div>
		<div class="formbox">
			<label>Crea conferenza:</label>
			<input class="column checkbox" type="checkbox" name="conferenza"<?php echo (isset($_POST['conferenza'])) ? ' checked="checked"' : ''; ?>>
		</div>
	</fieldset>
	<fieldset class="column last">
		<input type="submit" name="button" class="column submit dark" value="Invia" />
	</fieldset>
</form>
<?php else: ?>
	<span>Seleziona la lega</span>
<?php endif; ?>
