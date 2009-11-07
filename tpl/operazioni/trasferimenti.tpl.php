<form class="column last" name="trasferimenti" action="<?php echo $this->linksObj->getLink('trasferimenti'); ?>" method="post">
	<fieldset class="no-margin fieldset  max-large">
		<h3 class="no-margin">Seleziona la squadra:</h3>
		<input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
		<select name="squadra" onchange="document.trasferimenti.submit();">
		<?php foreach($this->elencoSquadre as $key => $val): ?>
			<option<?php if($this->squadra == $val['idUtente']) echo ' selected="selected"'; ?> value="<?php echo $val['idUtente']?>"><?php echo $val['nome']?></option>
		<?php endforeach ?>
		</select>
	</fieldset>
</form>
