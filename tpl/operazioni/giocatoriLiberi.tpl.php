<form id="freeplayeropt" class="column last" name="ruolo_form" action="<?php echo $this->linksObj->getLink('giocatoriLiberi'); ?>" method="post">
	<fieldset class="no-margin fieldset">
		<input type="hidden" name="p" value="<?php echo $_GET['p'];?>" />
		<input type="hidden" name="order" value="<?php echo $this->order ;?>" />
		<input type="hidden" name="v" value="<?php echo $this->v;?>" />
		<h3 class="no-margin">Seleziona il ruolo:</h3>
		<select name="ruolo" onchange="document.ruolo_form.submit();">
			<?php foreach($this->ruoli as $key => $val): ?>
				<option<?php if($this->ruolo == $key) echo ' selected="selected"'; ?> value="<?php echo $key?>"><?php echo $val; ?></option>
			<?php endforeach ?>
		</select>
		<div class="field column last">
			<label>Soglia sufficienza:</label>
			<input maxlength="3" name="suff" type="text" class="text" value="<?php echo $this->suff; ?>" />
		</div>
		<div class="field column last">
			<label>Soglia partite:</label>
			<input maxlength="2" name="partite" type="text" class="text" value="<?php echo $this->partite; ?>" />
			</div>
			<input class="submit" type="submit" value="OK"/>
	</fieldset>
</form>
