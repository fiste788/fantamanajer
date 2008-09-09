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
	<?php if($this->formazione != FALSE && $this->formazione != 2): ?>
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
            <?php foreach($tito as $key=>$val): ?>
					<?php if($val['Considerato'] == 0 or ($val['Voto']=="" and $val['Considerato']>0)): ?>
						<tr class="rosso">
							<td class="tableimg"><a href="index.php?p=dettaglioGiocatore&amp;id=<?php echo $val['gioc']; ?>"><img alt="Sostituito" title="Sostituito" src="<?php echo IMGSURL.'player-sost.png' ?>"/></a></td>
					<?php elseif($val['Considerato'] == 2): ?>
						<tr>
							<td class="tableimg"><a href="index.php?p=dettaglioGiocatore&amp;id=<?php echo $val['gioc']; ?>"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL.'player-cap.png' ?>"/></a></td>
					<?php $val['Voto']*=2; else: ?>
						<tr>
							<td class="tableimg"><a href="index.php?p=dettaglioGiocatore&amp;id=<?php echo $val['gioc']; ?>"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL.'player-tit.png' ?>"/></a></td>
					<?php endif; ?>		
							<td><?php echo $val['Cognome']; ?></td>
							<td><?php echo $val['Nome']; if($val['Considerato'] ==2) echo '<span id="cap">(C)</span>'; ?></td>
							<td><?php echo $val['Ruolo']; ?></td>
							<td><?php echo $val['Club']; ?></td>
							<td><?php if($val['Considerato'] > 0) echo $val['Voto']; else echo "&nbsp;"; ?></td>
						</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
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
			<?php foreach($panch as $key=>$val): ?>
					<?php if($val['Considerato'] == 1): ?>
						<tr class="verde">
							<td class="tableimg"><a href="index.php?p=dettaglioGiocatore&amp;id=<?php echo $val['gioc']; ?>"><img alt="Sostituito" title="Sostituito" src="<?php echo IMGSURL.'player-sost-in.png' ?>"/></a></td>
					<?php elseif($val['Considerato']==2): ?>
						<tr>
							<td class="tableimg"><a href="index.php?p=dettaglioGiocatore&amp;id=<?php echo $val['gioc']; ?>"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL.'player-cap.png' ?>"/></a></td>
					<?php else: ?>
						<tr>
							<td class="tableimg"><a href="index.php?p=dettaglioGiocatore&amp;id=<?php echo $val['gioc']; ?>"><img alt="Panchinaro" title="Panchinaro" src="<?php echo IMGSURL.'player-panch.png' ?>"/></a></td>
					<?php endif; ?>
							<td><?php echo $val['Cognome']; ?></td>
							<td><?php echo $val['Nome']; ?></td>
							<td><?php echo $val['Ruolo']; ?></td>
							<td><?php echo $val['Club']; ?></td>
							<td><?php if($val['Considerato'] > 0) echo $val['Voto']; else echo "&nbsp;"; ?></td>
						</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php elseif($this->formazione == 2): ?>
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
		<?php if($_SESSION['logged'] == TRUE): ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		<?php endif; ?>
		<div id="operazioni-other" class="column last">
			<ul class="operazioni-content">
				<?php if(!$this->giornprec): ?>
					<li class="simil-link undo-punteggi-unactive column last">Indietro di una giornata</li>
				<?php else: ?>
					<li class="column last"><a class="undo-punteggi-active column last operazione" href="index.php?p=punteggidettaglio&amp;giorn=<?php echo $this->giornprec; ?>&amp;squad=<?php echo $_GET['squad']; ?>">Indietro di una giornata</a></li>
				<?php endif; ?>
				<?php if(!$this->giornsucc): ?>
					<li class="simil-link redo-punteggi-unactive column last">Avanti di una giornata</li>
				<?php else: ?>
				<li class="column last"><a class="redo-punteggi-active column last operazione" href="index.php?p=punteggidettaglio&amp;giorn=<?php echo $this->giornsucc; ?>&amp;squad=<?php echo $_GET['squad']; ?>">Avanti di una giornata</a></li>
				<?php endif; ?>
			</ul>
		</div>
		<form class="column last" name="selsq" action="index.php?p=punteggidettaglio" method="get">
			<input type="hidden" name="p" value="<?php echo $_GET['p'];?>" />
			<fieldset class="no-margin fieldset  max-large">
				<h3 class="no-margin">Seleziona la giornata</h3>
				<select name="giorn" onchange="document.selsq.submit();">
					<option></option>
					<?php krsort($this->punteggi[1]); ?>
					<?php foreach($this->punteggi[1] as $key=>$val): ?>
						<option <?php if($this->getgiornata == $key) echo "selected=\"selected\"" ?> value="<?php echo $key?>"><?php echo $key?></option>
					<?php endforeach; ?>
				</select>
				<h3 class="no-margin">Seleziona la squadra</h3>
				<select name="squad" onchange="document.selsq.submit();">
					<option></option>
					<?php foreach($this->squadre as $key=>$val): ?>
						<option <?php if($this->getsquadra == $val[0]) echo "selected=\"selected\"" ?> value="<?php echo $val[0]?>"><?php echo $val[1]?></option>
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
