<form class="column last" name="datiFormazione" action="<?php echo $this->linksObj->getLink('inserisciFormazione'); ?>" method="post">
	<?php if($_SESSION['usertype'] == 'superadmin'): ?>
	<fieldset class="no-margin fieldset max-large">
		<h3 class="no-margin">Seleziona la lega:</h3>
		<select id="lega" name="lega">
			<?php if(!isset($this->mod)): ?><option></option><?php endif; ?>
			<?php foreach($this->elencoleghe as $key => $val): ?>
				<option<?php echo ($this->lega == $val->idLega) ? ' selected="selected"' : ''; ?> value="<?php echo $val->idLega; ?>"><?php echo $val->nomeLega; ?></option>
			<?php endforeach ?>
		</select>
	</fieldset>
	<?php endif; ?>
	<fieldset class="no-margin fieldset max-large">
		<h3 class="no-margin">Seleziona la giornata:</h3>
		<input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
		<select id="giorn" name="giornata">
			<?php if(!isset($this->giornata)): ?><option></option><?php endif; ?>
			<?php for($i = GIORNATA ; $i > 0 ; $i--): ?>
				<option<?php echo ($this->giornata == $i) ? ' selected="selected"' : ''; ?> value="<?php echo $i ?>"><?php echo $i ?></option>
			<?php endfor; ?>
		</select>
	</fieldset>
	<fieldset id="modulo" class="no-margin fieldset">
		<h3 class="no-margin">Seleziona il modulo:</h3>
		<select id="mod" name="mod" onchange="this.form.submit()">
			<?php if(!isset($this->mod)): ?><option></option><?php endif; ?>
			<option value="1-4-4-2"<?php if ($this->mod == '1-4-4-2') echo ' selected="selected"'; ?>>4-4-2</option>
			<option value="1-3-5-2"<?php if ($this->mod == '1-3-5-2') echo ' selected="selected"'; ?>>3-5-2</option>
			<option value="1-3-4-3"<?php if ($this->mod == '1-3-4-3') echo ' selected="selected"'; ?>>3-4-3</option>
			<option value="1-4-5-1"<?php if ($this->mod == '1-4-5-1') echo ' selected="selected"'; ?>>4-5-1</option>
			<option value="1-4-3-3"<?php if ($this->mod == '1-4-3-3') echo ' selected="selected"'; ?>>4-3-3</option>
			<option value="1-5-4-1"<?php if ($this->mod == '1-5-4-1') echo ' selected="selected"'; ?>>5-4-1</option>
			<option value="1-5-3-2"<?php if ($this->mod == '1-5-3-2') echo ' selected="selected"'; ?>>5-3-2</option>
		</select>
	</fieldset>
	<?php if(isset($this->lega) && $this->lega != NULL): ?>
	<fieldset class="no-margin fieldset max-large">
		<h3 class="no-margin">Seleziona la squadra:</h3>
		<?php if(!$this->elencosquadre): ?>
			<select disabled="disabled" name="squadra">
				<option value="NULL">Nessuna squadra presente</option>
		<?php else: ?>
			<select name="squadra">
			<?php if($this->squadra == NULL): ?><option></option><?php endif; ?>
			<?php foreach($this->elencosquadre as $key => $val): ?>
				<option <?php echo ($this->squadra == $val->idUtente) ? ' selected="selected"' : '' ?> value="<?php echo $val->idUtente; ?>"><?php echo $val->nome; ?></option>
			<?php endforeach ?>
		<?php endif; ?>
		</select>
		<input type="submit" class="submit" value="OK" />
	</fieldset>
	<?php endif; ?>			
</form>