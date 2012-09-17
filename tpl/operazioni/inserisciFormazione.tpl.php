<form class="form-inline" action="<?php echo Links::getLink('inserisciFormazione'); ?>" method="post">
	<fieldset class="no-margin fieldset max-large">
		<?php if($_SESSION['usertype'] == 'superadmin'): ?>
			<label>Lega:</label>
			<select class="input-medium" id="legaSelect" name="idLega">
				<?php if(!isset($this->mod)): ?><option></option><?php endif; ?>
				<?php foreach($this->elencoleghe as $key => $val): ?>
					<option<?php echo ($this->lega == $val->id) ? ' selected="selected"' : ''; ?> value="<?php echo $val->id; ?>"><?php echo $val; ?></option>
				<?php endforeach ?>
			</select>
		<?php endif; ?>
	
		<label>Giornata:</label>
		<input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
		<select class="input-mini" id="giorn" name="idGiornata">
			<?php if(!isset($this->giornata)): ?><option></option><?php endif; ?>
			<?php for($i = GIORNATA ; $i > 0 ; $i--): ?>
				<option<?php echo ($this->giornata == $i) ? ' selected="selected"' : ''; ?> value="<?php echo $i ?>"><?php echo $i ?></option>
			<?php endfor; ?>
		</select>
		<label>Modulo:</label>
		<select class="input-small" id="mod" name="modulo">
			<?php if(!isset($this->mod)): ?><option></option><?php endif; ?>
			<option value="1-4-4-2"<?php if ($this->mod == '1-4-4-2') echo ' selected="selected"'; ?>>4-4-2</option>
			<option value="1-3-5-2"<?php if ($this->mod == '1-3-5-2') echo ' selected="selected"'; ?>>3-5-2</option>
			<option value="1-3-4-3"<?php if ($this->mod == '1-3-4-3') echo ' selected="selected"'; ?>>3-4-3</option>
			<option value="1-4-5-1"<?php if ($this->mod == '1-4-5-1') echo ' selected="selected"'; ?>>4-5-1</option>
			<option value="1-4-3-3"<?php if ($this->mod == '1-4-3-3') echo ' selected="selected"'; ?>>4-3-3</option>
			<option value="1-5-4-1"<?php if ($this->mod == '1-5-4-1') echo ' selected="selected"'; ?>>5-4-1</option>
			<option value="1-5-3-2"<?php if ($this->mod == '1-5-3-2') echo ' selected="selected"'; ?>>5-3-2</option>
		</select>
		<label>Squadra:</label>
			<select id="squadraSelect"<?php if(!isset($this->elencosquadre)) echo ' disabled="disabled"'; ?> name="idUtente" onchange="this.form.submit()">
			<?php if($this->squadra == NULL): ?><option></option><?php endif; ?>
			<?php if(isset($this->elencosquadre)): ?>
				<?php foreach($this->elencosquadre as $key => $val): ?>
					<option <?php echo ($this->squadra == $val->id) ? ' selected="selected"' : '' ?> value="<?php echo $val->id; ?>"><?php echo $val->nomeSquadra; ?></option>
				<?php endforeach ?>
			<?php endif; ?>
		</select>
		<input type="submit" class="btn btn-primary" value="OK" />
	</fieldset>		
</form>
