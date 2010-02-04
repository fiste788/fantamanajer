<form class="column last" action="<?php echo $this->linksObj->getLink('nuovoTrasferimento'); ?>" method="post">
	<?php if($_SESSION['roles'] == '2'): ?>
	<fieldset class="no-margin fieldset max-large">
		<h3 class="no-margin">Seleziona la lega:</h3>
		<input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
		<select id="legaSelect" name="lega">
			<?php if($this->lega == NULL): ?><option></option><?php endif; ?>
			<?php foreach($this->elencoLeghe as $key => $val): ?>
				<option<?php echo ($this->lega == $val->idLega) ? ' selected="selected"' : ''; ?> value="<?php echo $val->idLega; ?>"><?php echo $val->nomeLega; ?></option>
			<?php endforeach ?>
		</select>
	</fieldset>
	<?php endif; ?>
	<fieldset class="no-margin fieldset max-large">
		<h3 class="no-margin">Seleziona la squadra:</h3>
		<select id="squadraSelect" <?php echo (!isset($this->elencoSquadre)) ? 'disabled="disabled"' : ''; ?> onchange="this.form.submit();" name="squadra">
			<?php if($this->squadra == NULL): ?><option></option><?php endif; ?>
			<?php if(isset($this->elencoSquadre)): ?>
				<?php foreach($this->elencoSquadre as $key => $val): ?>
					<option<?php if($this->squadra == $val->idUtente) echo ' selected="selected"'; ?> value="<?php echo $val->idUtente; ?>"><?php echo $val->nome; ?></option>
				<?php endforeach ?>
			<?php endif; ?>
		</select>
	</fieldset>
</form>
<script type="text/javascript">
	var url = '<?php echo AJAXURL; ?>';
</script>
