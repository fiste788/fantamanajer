<form class="column last" name="eventi" action="<?php echo $this->linksObj->getLink('feed'); ?>" method="post">
	<fieldset class="no-margin fieldset max-large">
		<h3 class="no-margin">Seleziona il tipo di evento:</h3>
		<input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
		<select name="evento" onchange="document.eventi.submit();">
			<option value="0">Tutti gli eventi</option>
			<option<?php if($this->evento == '1') echo ' selected="selected"'; ?> value="1">Conferenze stampa</option>
			<option<?php if($this->evento == '2') echo ' selected="selected"'; ?> value="2">Giocatore selezionato</option>
			<option<?php if($this->evento == '3') echo ' selected="selected"'; ?> value="3">Formazione impostata</option>
			<option<?php if($this->evento == '4') echo ' selected="selected"'; ?> value="4">Trasferimento</option>
			<option<?php if($this->evento == '5') echo ' selected="selected"'; ?> value="5">Ingresso nuovo giocatore</option>
			<option<?php if($this->evento == '6') echo ' selected="selected"'; ?> value="6">Uscita giocatore</option>
		</select>
	</fieldset>
</form>