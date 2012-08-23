<form class="form-inline" action="<?php echo Links::getLink('dettaglioClub'); ?>" method="post">
	<fieldset>
		<input type="hidden" value="<?php echo $_GET['p']; ?>" />
		<label for="club">Seleziona il club:</label>
		<select name="club" onchange="this.form.submit();">
			<?php if($this->elencoClub != FALSE): ?>
			<?php foreach ($this->elencoClub as $key => $val): ?>
				<option<?php echo ($key == $this->request->get('club')) ? ' selected="selected"' : ''; ?> value="<?php echo $key;?>"><?php echo $val->nome ?></option>
			<?php endforeach; ?>
			<?php endif; ?>
		</select>
	</fieldset>
</form>
