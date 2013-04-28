<?php $i = 0; ?>
<?php if($this->trasferimenti != FALSE): ?>
    <table class="table">
        <thead>
            <tr>
                <th>N.</th>
                <th>Giocatore nuovo</th>
                <th>Giocatore vecchio</th>
                <th>Giornata</th>
                <th>Obbligato</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->trasferimenti as $key => $val): ?>
                <tr>
                    <td><?php echo $i + 1; ?></td>
                    <td><a href="<?php echo $this->router->generate('giocatore_show',array('id'=>$val->idGiocatoreNew)); ?>"><?php echo $val->getGiocatoreNew()->cognome . " " . $val->getGiocatoreNew()->nome; ?></a></td>
                    <td><a href="<?php echo $this->router->generate('giocatore_show',array('id'=>$val->idGiocatoreOld)); ?>"><?php echo $val->getGiocatoreOld()->cognome . " " . $val->getGiocatoreOld()->nome; ?></a></td>
                    <td><?php echo $val->idGiornata; ?></td>
                    <td><?php if($val->isObbligato()): ?><i class="icon-ok"></i><?php endif; ?></td>
                </tr>
            <?php $i++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
	<p>Non ha effettuato alcun trasferimento</p>
<?php endif; ?>
<?php if($_SESSION['logged'] && $_SESSION['idUtente'] == $this->filterId && count($this->trasferimenti) < $_SESSION['datiLega']->numTrasferimenti && $this->currentGiornata != 1): ?>
	<h3>Acquista un giocatore</h3>
	<p class="alert-block alert alert-info">Quì è possibile indicare il nome del giocatore che volete acquistare. Se il giocatore è stato già selezionato da una squadra inferiore alla tua in classifica allora riceverai un messaggio di errore.<br />Al contrario il giocatore sarà selezionato per la tua squadra.<br />Se il proprietario di una squadra inferiore alla tua seleziona il tuo stesso giocatore il giocatore diventerà suo e una mail ti avviserà dell'accaduto in modo che tu puoi selezionare un nuovo giocatore.<br/>I trasferimenti saranno eseguiti nella nottata del giorno della giornata. Ad esempio se la giornata è il 25-12-2007 alora saranno eseguiti nella notte del 25-12-2007 in modo tale che nella mattinata e nel pomeriggio che mancano all'inizio della giornata voi potrete schierare il nuovo giocatore acquistato.Ora è possibile cambiare il giocatore selezionato 2 sole volte.</p>
	<form action="<?php echo $this->router->generate('trasferimento_index'); ?>" method="post">
		<fieldset>
			<input type="hidden" name="idUtente" value="<?php echo $_SESSION['idUtente']; ?>" />
			<div class="formbox">
				<label for="player-old">Giocatore vecchio:</label>
				<select id="player-old" name="idGiocatoreOld">
					<option></option>
					<?php foreach($this->ruoli as $keyRuoli => $valRuoli): ?>
						<optgroup label="<?php echo $valRuoli; ?>">
						<?php foreach($this->giocatoriSquadra as $key => $val): ?>
							<?php if($val->ruolo == $keyRuoli): ?>
								<option value="<?php echo $val->id; ?>"<?php echo (isset($this->selezione->idGiocatoreOld) && $this->selezione->idGiocatoreOld == $val->id) ? ' selected="selected"' : ''; ?>><?php echo $val->cognome . " " . $val->nome; ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</optgroup>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="formbox">
				<label for="player-new">Giocatore nuovo:</label>
				<select id="player-new" name="idGiocatoreNew">
					<option></option>
					<?php foreach($this->ruoli as $keyRuoli => $valRuoli): ?>
						<optgroup label="<?php echo $valRuoli; ?>">
						<?php foreach($this->freePlayer as $key => $val): ?>
							<?php if($val->ruolo == $keyRuoli): ?>
								<option value="<?php echo $val->id; ?>"<?php echo (isset($this->selezione->idGiocatoreNew) && $this->selezione->idGiocatoreNew == $val->id) ? '  selected="selected"' : ''; ?>><?php echo $val->cognome . " " . $val->nome; ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</optgroup>
					<?php endforeach; ?>
				</select>
			</div>
		</fieldset>
		<fieldset>
			<input class="btn btn-primary" type="submit" name="submit" value="OK" />
			<?php if(!is_null($this->selezione)): ?>
				<input class="btn" type="submit" name="submit" value="Cancella acq." />
			<?php endif; ?>
		</fieldset>
	</form>
<?php endif; ?>

