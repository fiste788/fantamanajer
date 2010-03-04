<?php $i = 0; ?>
<?php if($this->trasferimenti != FALSE): ?>
<table id="trasf" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<th>N.</th>
			<th>Giocatore nuovo</th>
			<th>Giocatore vecchio</th>
			<th>Giornata</th>
			<th>Obbligato</th>
		</tr>
		<?php foreach($this->trasferimenti as $key => $val): ?>
		<tr class="row">
			<td><?php echo $i + 1; ?></td>
			<td><a href="<?php echo Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGiocNew)); ?>"><?php echo $val->cognomeNew . " " . $val->nomeNew; ?></a></td>
			<td><a href="<?php echo Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGiocOld)); ?>"><?php echo $val->cognomeOld . " " . $val->nomeOld; ?></a></td>
			<td><?php echo $val->idGiornata; ?></td>
			<td><?php echo ($val->obbligato) ? "X" : "&nbsp;" ?></td>
		</tr>
		<?php $i++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php elseif($this->squadra != NULL && $this->squadra != ''): ?>
	<p>Non ha effettuato alcun trasferimento</p>
<?php endif; ?>
<?php if(isset($this->trasferiti) && $this->numTrasferimenti < $_SESSION['datiLega']->numTrasferimenti && $this->squadra == $_SESSION['idSquadra']): ?>
	<p>Uno o più dei tuoi giocatori non sono più presenti nella lista della gazzetta. Dalla form sottostante potrai selezionarne subito un altro e fare un trasferimento immediato
	<?php if(count($this->trasferiti) > ($_SESSION['datiLega']->numTrasferimenti - $this->numTrasferimenti) && $this->numTrasferimenti < $_SESSION['datiLega']->numTrasferimenti): ?><br /><strong>Attenzione!</strong> Ti rimangono solo <?php echo ($_SESSION['datiLega']->numTrasferimenti - $this->numTrasferimenti); ?> trasferimento/i e i giocatori da sostituire sono <?php echo count($this->trasferiti); ?>. Compila solo <?php echo ($_SESSION['datiLega']->numTrasferimenti - $this->numTrasferimenti); ?> giocatore/i a tua scelta.<?php endif; ?></p>
	<form class="column last" id="acquisti" name="edit-trasferimenti" action="<?php echo Links::getLink('trasferimenti',array('squadra'=>$this->squadra)); ?>" method="post">
	<?php foreach($this->trasferiti as $key => $val): ?>
		<fieldset>
			<input type="hidden" name="squadra" value="<?php echo $this->squadra; ?>" />
			<label for="player-old">Giocatore vecchio:</label>
			<select disabled="disabled" id="player-old" name="lascia[]">
				<option selected="selected" value="<?php echo $val->idGioc; ?>"><?php echo $val->cognome . " " . $val->nome; ?></option>
			</select>
			<label for="player-new">Giocatore nuovo:</label>
			<select id="player-new" name="acquista[]">
				<option></option>
					<?php foreach($this->freePlayerByRuolo[$val->idGioc] as $key => $val): ?>
						<option value="<?php echo $val->idGioc; ?>"><?php echo $val->cognome . " " . $val->nome; ?></option>
					<?php endforeach; ?>
			</select>
		</fieldset>
	<?php endforeach; ?>
		<fieldset>
			<input class="submit dark" type="submit" name="submit" value="OK" />
		</fieldset>
	</form>
<?php else: ?>
	<?php if($_SESSION['logged'] && $_SESSION['idSquadra'] == $this->squadra && $this->numTrasferimenti < $_SESSION['datiLega']->numTrasferimenti && PARTITEINCORSO == FALSE && GIORNATA != 1): ?>
	<br />
	<h3>Acquista un giocatore</h3>
	<a class="info" href="#info"><span>Clicca quì per informazioni</span></a>
	<p class="surprise hidden">Quì è possibile indicare il nome del giocatore che volete acquistare. Se il giocatore è stato già selezionato da una squadra inferiore alla tua in classifica allora riceverai un messaggio di errore.<br />Al contrario il giocatore sarà selezionato per la tua squadra.<br />Se il proprietario di una squadra inferiore alla tua seleziona il tuo stesso giocatore il giocatore diventerà suo e una mail ti avviserà dell'accaduto in modo che tu puoi selezionare un nuovo giocatore.<br/>I trasferimenti saranno eseguiti nella nottata del giorno della giornata. Ad esempio se la giornata è il 25-12-2007 alora saranno eseguiti nella notte del 25-12-2007 in modo tale che nella mattinata e nel pomeriggio che mancano all'inizio della giornata voi potrete schierare il nuovo giocatore acquistato.Ora è possibile cambiare il giocatore selezionato 2 sole volte.</p>
	<form class="column last" id="acquisti" action="<?php echo Links::getLink('trasferimenti',array('squadra'=>$_GET['squadra'])); ?>" method="post">
		<fieldset>
			<input type="hidden" name="squadra" value="<?php echo $this->squadra; ?>" />
			<label for="player-old">Giocatore vecchio:</label>
			<select id="player-old" name="lascia">
				<option></option>
				<?php foreach($this->ruoli as $keyRuoli => $valRuoli): ?>
					<optgroup label="<?php echo $valRuoli; ?>">
					<?php foreach($this->giocSquadra as $key => $val): ?>
						<?php if($val->ruolo == $keyRuoli): ?>
							<option value="<?php echo $val->idGioc; ?>"<?php echo (isset($this->giocLasciato) && $this->giocLasciato == $val->idGioc) ? ' selected="selected"' : ''; ?>><?php echo $val->cognome . " " . $val->nome; ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</optgroup>
				<?php endforeach; ?>
			</select>
			<label for="player-new">Giocatore nuovo:</label>
			<select id="player-new" name="acquista">
				<option></option>
				<?php foreach($this->ruoli as $keyRuoli => $valRuoli): ?>
					<optgroup label="<?php echo $valRuoli; ?>">
					<?php foreach($this->freePlayer as $key => $val): ?>
						<?php if($val->ruolo == $keyRuoli): ?>
							<option value="<?php echo $val->idGioc; ?>"<?php echo (isset($this->giocAcquisto) && $this->giocAcquisto == $val->idGioc) ? '  selected="selected"' : ''; ?>><?php echo $val->cognome . " " . $val->nome; ?></option>
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
	<?php endif; ?>
<?php endif; ?>
