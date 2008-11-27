<div class="titolo-pagina">
	<div class="column logo-tit">
		<img alt="->" src="<?php echo IMGSURL. 'classifica-big.png'; ?>" />
	</div>
	<h2 class="column">Dettaglio punteggi</h2>
</div>
<div id="punteggidett" class=" main-content">
	<h4>Giornata: <span><?php if(isset($this->getgiornata)) echo $this->getgiornata; ?></span></h4>
	<h4>Squadra: <span><?php if(isset($this->getsquadra)) echo $this->squadradett['nome']; ?></span></h4>
	<h4>Punteggio: <span><?php if(isset($this->somma)) echo $this->somma; ?></span></h4>
	<?php if($this->formazione != FALSE && $this->formazione != NULL): ?>
	<table class="column last" cellpadding="0" cellspacing="0">
		<caption>Titolari</caption>
		<tbody>
			<tr>
				<th class="tableimg">&nbsp;</th>
				<th class="cognome">Cognome</th>
				<th class="nome">Nome</th>
				<th class="ruolo">Ruolo</th>
				<th class="club">Club</th>
				<th class="punt">Punt.</th>
			</tr>
			<?php $panch=$this->formazione;$tito=array_splice($panch,0,11);?>
            <?php foreach($tito as $key => $val): ?>
					<?php if($val['considerato'] == 0 || ($val['voto'] == "" && $val['considerato'] > 0)): ?>
						<tr class="rosso">
							<td class="tableimg"><a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('id'=>$val['gioc'])); ?>"><img alt="Sostituito" title="Sostituito" src="<?php echo IMGSURL.'player-sost.png' ?>"/></a></td>
					<?php elseif($val['considerato'] == 2): ?>
						<tr>
							<td class="tableimg"><a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('id'=>$val['gioc'])); ?>"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL.'player-cap.png' ?>"/></a></td>
					<?php $val['voto'] *= 2; else: ?>
						<tr>
							<td class="tableimg"><a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('id'=>$val['gioc'])); ?>"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL.'player-tit.png' ?>"/></a></td>
					<?php endif; ?>		
							<td><?php echo $val['cognome']; ?></td>
							<td><?php echo $val['nome']; if($val['considerato'] == 2) echo '<span id="cap">(C)</span>'; ?></td>
							<td><?php echo $val['ruolo']; ?></td>
							<td><?php echo strtoupper(substr($val['nomeClub'],0,3)); ?></td>
							<td><?php if($val['considerato'] > 0 && !empty($val['voto'])) echo $val['voto']; else echo "&nbsp;"; ?></td>
						</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php if(!empty($panchinari)): ?>
	<table class="column last" cellpadding="0" cellspacing="0">
		<caption>Panchinari</caption>
		<tbody>
			<tr>
				<th class="tableimg">&nbsp;</th>
				<th class="cognome">Cognome</th>
				<th class="nome">Nome</th>
				<th class="ruolo">Ruolo</th>
				<th class="club">Club</th>
				<th class="punt">Punt.</th>
			</tr>
			<?php foreach($panch as $key => $val): ?>
					<?php if($val['considerato'] == 1): ?>
						<tr class="verde">
							<td class="tableimg"><a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('id'=>$val['gioc'])); ?>"><img alt="Sostituito" title="Sostituito" src="<?php echo IMGSURL.'player-sost-in.png' ?>"/></a></td>
					<?php elseif($val['considerato'] == 2): ?>
						<tr>
							<td class="tableimg"><a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('id'=>$val['gioc'])); ?>"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL.'player-cap.png' ?>"/></a></td>
					<?php else: ?>
						<tr>
							<td class="tableimg"><a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('id'=>$val['gioc'])); ?>"><img alt="Panchinaro" title="Panchinaro" src="<?php echo IMGSURL.'player-panch.png' ?>"/></a></td>
					<?php endif; ?>
							<td><?php echo $val['cognome']; ?></td>
							<td><?php echo $val['nome']; ?></td>
							<td><?php echo $val['ruolo']; ?></td>
							<td><?php strtoupper(substr($val['nomeClub'],0,3)); ?></td>
							<td><?php if($val['considerato'] > 0 && !empty($val['voto'])) echo $val['voto']; else echo "&nbsp;"; ?></td>
						</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</div>
<?php elseif($this->formazione == FALSE): ?>
<span class="column" style="clear:both;">Formazione non settata</span></div>
<?php else: ?>
<span class="column" style="clear:both;">Parametri mancanti o errati</span></div>
<?php endif; ?>
<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
		<?php if(isset($this->penalità)): ?>
		<div class="messaggio neut column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-big.png'; ?>" title="Attenzione!" />
			<?php foreach($this->penalità as $key => $val): ?>
				<span>Penalità: <?php echo $val['punteggio']; ?> punti<br />Motivazione: <?php echo $val['penalità']; ?></span><br />
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		<?php if($_SESSION['logged'] == TRUE): ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		<?php endif; ?>
		<div id="operazioni-other" class="column last">
			<ul class="operazioni-content">
				<?php if(!$this->giornprec): ?>
					<li class="simil-link undo-punteggi-unactive column last">Indietro di una giornata</li>
				<?php else: ?>
					<li class="column last"><a class="undo-punteggi-active column last operazione" href="<?php echo $this->linksObj->getLink('dettaglioGiornata',array('giorn'=>$this->giornprec,'squad'=>$_GET['squad'])); ?>">Indietro di una giornata</a></li>
				<?php endif; ?>
				<?php if(!$this->giornsucc): ?>
					<li class="simil-link redo-punteggi-unactive column last">Avanti di una giornata</li>
				<?php else: ?>
				<li class="column last"><a class="redo-punteggi-active column last operazione" href="<?php echo $this->linksObj->getLink('dettaglioGiornata',array('giorn'=>$this->giornsucc,'squad'=>$_GET['squad'])); ?>">Avanti di una giornata</a></li>
				<?php endif; ?>
			</ul>
		</div>
		<form class="column last" name="selsq" action="<?php echo $this->linksObj->getLink('dettaglioGiornata') ?>" method="get">
			<input type="hidden" name="p" value="<?php echo $_GET['p'];?>" />
			<fieldset class="no-margin fieldset max-large">
				<h3 class="no-margin">Seleziona la giornata</h3>
				<select name="giorn" onchange="document.selsq.submit();">
					<?php if(!isset($this->getgiornata)): ?><option></option><?php endif; ?>
					<?php for($i = $this->giornate ; $i > 0 ; $i--): ?>
						<option<?php if($this->getgiornata == $i) echo ' selected="selected"'; ?> value="<?php echo $i?>"><?php echo $i?></option>
					<?php endfor; ?>
				</select>
				<h3 class="no-margin">Seleziona la squadra</h3>
				<select name="squad" onchange="document.selsq.submit();">
					<?php if(!isset($this->getsquadra)): ?><option></option><?php endif; ?>
					<?php foreach($this->squadre as $key => $val): ?>
						<option<?php if($this->getsquadra == $val['idUtente']) echo ' selected="selected"'; ?> value="<?php echo $val['idUtente']?>"><?php echo $val['nome']?></option>
					<?php endforeach; ?>
				</select>
			</fieldset>
		</form>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
