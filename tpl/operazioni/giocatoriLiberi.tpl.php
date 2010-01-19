<form id="freeplayeropt" class="column last" action="<?php echo $this->linksObj->getLink('giocatoriLiberi'); ?>" method="post">
	<fieldset class="no-margin fieldset">
		<input type="hidden" name="p" value="<?php echo $_GET['p'];?>" />
		<?php if(isset($this->order)): ?>
		<input type="hidden" name="order" value="<?php echo $this->order ;?>" />
		<?php endif; ?>
		<?php if(isset($this->v)): ?>
		<input type="hidden" name="v" value="<?php echo $this->v;?>" />
		<?php endif; ?>
		<h3 class="no-margin">Seleziona il ruolo:</h3>
		<select name="ruolo" onchange="this.form.submit();">
			<?php foreach($this->ruoli as $key => $val): ?>
				<option<?php echo ($this->ruolo == $key) ? ' selected="selected"' : ''; ?> value="<?php echo $key?>"><?php echo $val; ?></option>
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
