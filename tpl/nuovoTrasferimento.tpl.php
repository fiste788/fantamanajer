<?php if(isset($this->squadra) && $this->squadra != NULL): ?>
<form class="column last" id="acquisti" action="<?php echo $this->linksObj->getLink('nuovoTrasferimento'); ?>" method="post">
	<fieldset>
		<input type="hidden" name="squadra" value="<?php echo $this->squadra; ?>" />
		<input type="hidden" name="lega" value="<?php echo $this->lega; ?>" />
		<label for="player-old">Giocatore vecchio:</label>
		<select id="player-old" name="lascia">
			<option></option>
			<?php foreach($this->ruoli as $keyRuoli=>$valRuoli): ?>
		    <optgroup label="<?php echo $valRuoli; ?>">
				<?php foreach($this->giocSquadra as $key => $val): ?>
					<?php if($val->ruolo == $keyRuoli): ?>
						<option value="<?php echo $val->idGioc; ?>"<?php if(isset($this->giocLasciato) && $this->giocLasciato == $val->idGioc) echo ' selected="selected"'; ?>><?php echo $val->cognome . " " . $val->nome; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</optgroup>
			<?php endforeach; ?>
		</select>
		<label for="player-new">Giocatore nuovo:</label>
		<select id="player-new" name="acquista">
			<option></option>
			<?php foreach($this->ruoli as $keyRuoli=>$valRuoli): ?>
			<optgroup label="<?php echo $valRuoli; ?>">
				<?php foreach($this->freePlayer as $key => $val): ?>
					<?php if($val->ruolo == $keyRuoli): ?>
						<option value="<?php echo $val->idGioc; ?>"<?php if(isset($this->giocAcquisto) && $this->giocAcquisto == $val->idGioc) echo ' selected="selected"'; ?>><?php echo $val->cognome . " " . $val->nome; ?> - <?php if(!empty($val->idUtente)) echo substr($this->elencoSquadre[$val->idUtente]->nome,0,18); else echo "Libero"; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</optgroup>
			<?php endforeach; ?>
		</select>
	</fieldset>
	<fieldset>
		<input class="submit dark" type="submit" name="submit" value="OK" />
		<?php if(isset($this->isset)): ?>
			<input class="submit dark" type="submit" name="submit" value="Cancella acq." />
		<?php endif; ?>
	</fieldset>
</form>
<?php else: ?>
<span>Seleziona la squadra</span>
<?php endif; ?>
