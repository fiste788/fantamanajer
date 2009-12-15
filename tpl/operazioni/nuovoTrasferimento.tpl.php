<form class="column last" action="<?php echo $this->linksObj->getLink('nuovoTrasferimento'); ?>" method="post">
	<?php if($_SESSION['roles'] == '2'): ?>
	<fieldset class="no-margin fieldset max-large">
		<h3 class="no-margin">Seleziona la lega:</h3>
		<input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
		<select name="lega" onchange="this.form.submit();">
			<?php if($this->lega == NULL): ?><option></option><?php endif; ?>
			<?php foreach($this->elencoLeghe as $key => $val): ?>
				<option<?php if($this->lega == $val->idLega) echo ' selected="selected"'; ?> value="<?php echo $val->idLega; ?>"><?php echo $val->nomeLega; ?></option>
			<?php endforeach ?>
		</select>
	</fieldset>
	<?php endif; ?>
	<fieldset class="no-margin fieldset max-large">
		<h3 class="no-margin">Seleziona la squadra:</h3>
		<?php if(!isset($this->elencoSquadre)): ?>
			<select disabled="disabled" name="squada">
				<option value="NULL">Nessuna squadra presente</option>
		<?php else: ?>
			<select name="squadra" onchange="this.form.submit();">
			<?php if($this->squadra == NULL): ?><option></option><?php endif; ?>
			<?php foreach($this->elencoSquadre as $key => $val): ?>
				<option<?php if($this->squadra == $val->idUtente) echo ' selected="selected"'; ?> value="<?php echo $val->idUtente; ?>"><?php echo $val->nome; ?></option>
			<?php endforeach ?>
		<?php endif; ?>
		</select>
	</fieldset>
</form>
