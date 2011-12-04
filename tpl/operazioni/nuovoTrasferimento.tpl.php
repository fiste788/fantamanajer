<form action="<?php echo Links::getLink('nuovoTrasferimento'); ?>" method="post">
	<?php if($_SESSION['roles'] == '2'): ?>
		<fieldset>
			<input type="hidden" name="p" value="<?php echo $this->request->get('p'); ?>" />
			<label for="lega">Seleziona la lega:</label>
			<select name="lega">
				<?php if($this->lega == NULL): ?><option></option><?php endif; ?>
				<?php foreach($this->elencoLeghe as $key => $val): ?>
					<option<?php echo ($this->lega == $val->idLega) ? ' selected="selected"' : ''; ?> value="<?php echo $val->idLega; ?>"><?php echo $val->nomeLega; ?></option>
				<?php endforeach ?>
			</select>
		</fieldset>
	<?php endif; ?>
	<fieldset>
		<label for="squadra">Seleziona la squadra:</label>
		<select <?php echo (!isset($this->elencoSquadre)) ? 'disabled="disabled"' : ''; ?> onchange="this.form.submit();" name="squadra">
			<?php if($this->squadra == NULL): ?><option></option><?php endif; ?>
			<?php foreach($this->elencoSquadre as $key => $val): ?>
				<option<?php if($this->squadra == $val->idUtente) echo ' selected="selected"'; ?> value="<?php echo $val->idUtente; ?>"><?php echo $val->nome; ?></option>
			<?php endforeach ?>
		</select>
	</fieldset>
</form>
<script type="text/javascript">
	var url = '<?php echo AJAXURL; ?>';
</script>
