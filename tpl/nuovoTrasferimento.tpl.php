<?php if(isset($this->squadra) && $this->squadra != NULL): ?>
<form class="form-horizontal" action="<?php echo Links::getLink('nuovoTrasferimento'); ?>" method="post">
	<fieldset>
		<input type="hidden" name="idUtente" value="<?php echo $this->squadra; ?>" />
		<input type="hidden" name="idLega" value="<?php echo $this->lega; ?>" />
		<div class="control-group">
			<label class="control-label" for="player-old">Giocatore vecchio:</label>
			<div class="controls">
				<select id="player-old" name="idGiocatoreOld">
					<option></option>
					<?php foreach($this->ruoli as $keyRuoli=>$valRuoli): ?>
			    	<optgroup label="<?php echo $valRuoli; ?>">
						<?php foreach($this->giocatoriSquadra as $key => $val): ?>
							<?php if($val->ruolo == $keyRuoli): ?>
								<option value="<?php echo $val->id; ?>"<?php echo (isset($this->giocLasciato) && $this->giocLasciato == $val->id) ? ' selected="selected"' : ''; ?>><?php echo $val ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</optgroup>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="player-new">Giocatore nuovo:</label>
			<div class="controls">
				<select id="player-new" name="idGiocatoreNew">
					<option></option>
					<?php foreach($this->ruoli as $keyRuoli=>$valRuoli): ?>
					<optgroup label="<?php echo $valRuoli; ?>">
						<?php foreach($this->freePlayer as $key => $val): ?>
							<?php if($val->ruolo == $keyRuoli): ?>
								<option value="<?php echo $val->id; ?>"<?php echo (isset($this->giocAcquisto) && $this->giocAcquisto == $val->id) ? ' selected="selected"' : ''; ?>><?php echo $val ?> - <?php echo (!empty($val->idUtente)) ? substr($this->elencoSquadre[$val->idUtente]->nomeSquadra,0,18) : "Libero"; ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</optgroup>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<input class="btn btn-primary" type="submit" name="submit" value="OK" />
	</fieldset>
</form>
<?php else: ?>
<span>Seleziona la squadra</span>
<?php endif; ?>
