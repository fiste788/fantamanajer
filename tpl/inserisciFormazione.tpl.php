<?php $j =0; $k = 0; ?>	
<h3>Giornata <?php echo GIORNATA; ?></h3>
<?php if(isset($this->squadra) && $this->squadra != NULL && isset($this->mod) && $this->mod != NULL && isset($this->giornata) && $this->giornata != NULL): ?>	
	<form id="form-formazione" action="<?php echo $this->linksObj->getLink('inserisciFormazione'); ?>" method="post">
		<fieldset id="titolari">
		<input type="hidden" name="mod" value="<?php echo $this->mod; ?>">
		<input type="hidden" name="lega" value="<?php echo $this->lega; ?>">
		<input type="hidden" name="giornata" value="<?php echo $this->giornata; ?>">
		<input type="hidden" name="squadra" value="<?php echo $this->squadra; ?>">
		<h3 class="center">Titolare</h3>
		<?php foreach($this->giocatori as $key => $val): ?>
			<h4 class="bold no-margin"><?php echo $this->ruo[$this->ruoliKey[$j]]; ?></h4><hr />
			<?php for($i = 0; $i < $this->modulo[$j] ; $i++): ?>
				<select name="<?php echo $this->ruoliKey[$j] . '[' . $i . ']'; ?>">
					<option></option>
					<?php foreach($val as $key3=>$val3): ?>
						<option value="<?php echo $val3->idGioc; ?>"<?php if(isset($this->titolari[$k]) && $val3->idGioc == $this->titolari[$k]) {$selected = $val3->idGioc; echo ' selected="selected"';} ?>><?php echo $val3->cognome . " " . $val3->nome; ?></option>
					<?php endforeach; ?>
				</select>
				<?php FB::log($this->elencocap); ?>
				<?php if($this->ruoliKey[$j] == 'P' || $this->ruoliKey[$j] == 'D'): ?>
					<select class="cap" name="cap[<?php echo $this->ruoliKey[$j]; ?>-<?php echo $i; ?>]">
						<option></option>
						<?php foreach($this->elencocap as $key2=>$val2): ?>
							<option value="<?php echo $val2; ?>" <?php if(isset($this->cap[$val2]) && $this->cap[$val2] == $selected) echo ' selected="selected"'; ?>><?php echo $val2; ?></option>
						<?php endforeach; ?>
					</select>
				<?php endif; ?>
			<?php $k++; endfor; ?>
		<?php $j++; endforeach; ?>
			</fieldset>
			<fieldset id="panchinari">
				<h3 class="center">Panchina</h3>
				<h4 class="bold no-margin">Giocatori</h4><hr />
				<?php for( $i = 0 ; $i < 7 ; $i++): ?>
				<select class="panch" name="panch[]">
				<option></option>
					<?php for($j = 0 ; $j < count($this->ruo) ; $j++): ?>
						<optgroup label="<?php echo $this->ruo[$this->ruoliKey[$j]]; ?>">
							<?php foreach($this->giocatori[$this->ruoliKey[$j]] as $key3=>$val3): ?>
								<option value="<?php echo $val3->idGioc; ?>"<?php if(isset($this->panchinari[$i]) && $val3->idGioc == $this->panchinari[$i]) echo ' selected="selected"'; ?>><?php  echo $val3->cognome . " " . $val3->nome ;?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endfor; ?>
				</select>
				<?php endfor; ?>
				<div class="div-submit">
					<input class="submit dark" type="submit" name="button" value="Invia" />
					<input class="submit dark" type="reset" value="Torna indietro" />
				</div>
				</fieldset>
			</form>
	<?php endif; ?>
</div>