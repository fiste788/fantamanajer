<form action="<?php echo Links::getLink('giocatoriLiberi'); ?>" method="post">
	<fieldset>
		<input type="hidden" name="p" value="<?php echo $_GET['p'];?>" />
		<?php if(isset($this->order)): ?><input type="hidden" name="order" value="<?php echo $this->order ;?>" /><?php endif; ?>
		<?php if(isset($this->v)): ?><input type="hidden" name="v" value="<?php echo $this->v;?>" /><?php endif; ?>
		<label for="ruolo">Seleziona il ruolo:</label>
		<select name="ruolo" onchange="this.form.submit();">
			<?php foreach($this->ruoli as $key => $val): ?>
				<option<?php echo ($this->ruolo == $key) ? ' selected="selected"' : ''; ?> value="<?php echo $key?>"><?php echo $val; ?></option>
			<?php endforeach ?>
		</select>
		<label for="suff">Soglia sufficienza:</label>
		<input id="suff" maxlength="3" name="suff" type="text" class="text small" value="<?php echo $this->suff; ?>" />
		<label for="partite">Soglia partite:</label>
		<input maxlength="2" name="partite" type="text" class="text small" value="<?php echo $this->partite; ?>" />
		<input class="submit" type="submit" value="OK"/>
	</fieldset>
</form>
