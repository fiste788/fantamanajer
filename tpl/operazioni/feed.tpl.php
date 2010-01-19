<form class="column last" action="<?php echo $this->linksObj->getLink('feed'); ?>" method="post">
	<fieldset class="no-margin fieldset max-large">
		<h3 class="no-margin">Seleziona il tipo di evento:</h3>
		<input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
		<select name="evento" onchange="this.form.submit();">
			<option value="0">Tutti gli eventi</option>
			<option<?php echo ($this->evento == '1') ? ' selected="selected"' : ''; ?> value="1">Conferenze stampa</option>
			<option<?php echo ($this->evento == '2') ? ' selected="selected"' : ''; ?> value="2">Giocatore selezionato</option>
			<option<?php echo ($this->evento == '3') ? ' selected="selected"' : ''; ?> value="3">Formazione impostata</option>
			<option<?php echo ($this->evento == '4') ? ' selected="selected"' : ''; ?> value="4">Trasferimento</option>
			<option<?php echo ($this->evento == '5') ? ' selected="selected"' : ''; ?> value="5">Ingresso nuovo giocatore</option>
			<option<?php echo ($this->evento == '6') ? ' selected="selected"' : ''; ?> value="6">Uscita giocatore</option>
		</select>
	</fieldset>
</form>
