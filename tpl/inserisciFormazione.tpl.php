<?php $j =0; $k = 0; ?>
<h3>Giornata <?php echo GIORNATA; ?></h3>
<?php if(!empty($this->squadra)): ?>
<form action="<?php echo Links::getLink('inserisciFormazione'); ?>" method="post">
	<fieldset id="titolari">
		<input type="hidden" name="modulo" value="<?php echo $this->mod; ?>">
		<input type="hidden" name="idLega" value="<?php echo $this->lega; ?>">
		<input type="hidden" name="idGiornata" value="<?php echo $this->giornata; ?>">
		<input type="hidden" name="idUtente" value="<?php echo $this->squadra; ?>">
		<h4>Titolare</h4>
		<?php foreach($this->giocatori as $key => $val): ?>
			<div class="ruolo" id="<?php echo $this->ruoliKey[$j] ?>">
			<h5><?php echo $this->ruo[$this->ruoliKey[$j]]; ?></h5>
			<div class="giocatori">
			<?php for($i = 0; $i < $this->modulo[$j] ; $i++): ?>
				<div class="giocatore">
					<select class="titolari" name="titolari[]">
						<option></option>
						<?php foreach($this->giocatori[$this->ruoliKey[$j]] as $key3=>$val3): ?>
							<option value="<?php echo $val3->id; ?>"<?php if(isset($this->formazione) && $val3->id == $this->formazione->giocatori[$k]->idGiocatore) echo ' selected="selected"' ?>><?php echo $val3 ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php $k++; endfor; ?>
			</div>
			</div>
		<?php $j++; endforeach; ?>
	</fieldset>
	<fieldset id="panchinari">
		<h4>Panchina</h4>
		<?php for( $i = 0 ; $i < 7 ; $i++): ?>
		<select class="panchinari" name="panchinari[]">
			<option></option>
			<?php for($j = 0 ; $j < count($this->ruoliKey) ; $j++): ?>
				<optgroup label="<?php echo $this->ruo[$this->ruoliKey[$j]]; ?>">
					<?php foreach($this->giocatori[$this->ruoliKey[$j]] as $key3=>$val3): ?>
						<option value="<?php echo $val3->id; ?>"<?php if(isset($this->formazione->giocatori[$i + $k]) && $val3->id == $this->formazione->giocatori[$i + $k]->idGiocatore) echo ' selected="selected"'; ?>><?php  echo $val3 ?></option>
					<?php endforeach; ?>
				</optgroup>
			<?php endfor; ?>
		</select>
		<?php endfor; ?>
		</fieldset>
	<fieldset id="capitani">
		<h4>Capitani</h4>
		<select id="C" name="C" data-oldvalue="<?php if(isset($this->formazione)) echo $this->formazione->idCapitano; ?>"></select>
		<select id="VC" name="VC" data-oldvalue="<?php if(isset($this->formazione)) echo $this->formazione->idVCapitano; ?>"></select>
		<select id="VVC" name="VVC" data-oldvalue="<?php if(isset($this->formazione)) echo $this->formazione->idVVCapitano; ?>"></select>
	</fieldset>
	<input class="btn btn-primary" type="submit" name="submit" value="Invia" />
	<input class="btn btn-primary" type="reset" value="Torna indietro" />
</form>
<?php endif; ?>