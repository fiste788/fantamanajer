<form class="column last" action="<?php echo Links::getLink('dettaglioGiocatore'); ?>" method="post">
	<fieldset class="no-margin fieldset">
		<input type="hidden" value="<?php echo $_GET['p'];?>" />
		<input type="hidden" value="<?php echo $_GET['edit'];?>" name="edit" />
		<h3 class="no-margin">Seleziona il giocatore:</h3>
		<select name="id" onchange="this.form.submit();">
			<?php if($this->elencoGiocatori != FALSE): ?>
			<?php foreach ($this->elencoGiocatori as $key => $val): ?>
				<option<?php echo ($key == $this->idGioc) ? ' selected="selected"' : ''; ?> value="<?php echo $key;?>"><?php echo $val->cognome . " " . $val->nome; ?></option>
			<?php endforeach; ?>
			<?php endif; ?>
		</select>
	</fieldset>
</form>
