<form class="column last" action="<?php echo Links::getLink('dettaglioClub'); ?>" method="post">
	<fieldset class="no-margin fieldset">
		<input type="hidden" value="<?php echo $_GET['p'];?>" />
		<label class="no-margin">Seleziona il club:</label>
		<select name="id" onchange="this.form.submit();">
			<?php if($this->elencoClub != FALSE): ?>
			<?php foreach ($this->elencoClub as $key => $val): ?>
				<option<?php echo ($key == $this->request->get('id')) ? ' selected="selected"' : ''; ?> value="<?php echo $key;?>"><?php echo $val->nome ?></option>
			<?php endforeach; ?>
			<?php endif; ?>
		</select>
	</fieldset>
</form>
