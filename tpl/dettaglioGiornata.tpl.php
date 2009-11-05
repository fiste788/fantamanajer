<div id="punteggidett" class=" main-content">
	<h4>Giornata: <span><?php if(isset($this->idGiornata)) echo $this->idGiornata; ?></span></h4>
	<h4>Squadra: <span><?php if(isset($this->idSquadra)) echo $this->squadraDett['nome']; ?></span></h4>
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
				<th class="club">Titolare</th>
				<th class="punt">Punt.</th>
			</tr>
			<?php $panch = $this->formazione; $tito = array_splice($panch,0,11); ?>
			<?php foreach($tito as $key => $val): ?>
					<?php if($val['considerato'] == 0 || ($val['voto'] == "" && $val['considerato'] > 0)): ?>
						<tr class="rosso">
							<td class="tableimg">
								<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val['idGioc'])); ?>"><img alt="Sostituito" title="Sostituito" src="<?php echo IMGSURL.'player-rosso.png' ?>"/></a>
							</td>
					<?php elseif($val['considerato'] == 2): ?>
						<tr>
							<td class="tableimg">
								<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val['gioc'])); ?>"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL.'player-cap.png' ?>"/></a>
							</td>
					<?php $val['punti'] *= 2; else: ?>
						<tr>
							<td class="tableimg">
								<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val['gioc'])); ?>"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL.'player-tit.png' ?>"/></a>
							</td>
					<?php endif; ?>		
							<td><?php echo $val['cognome']; ?></td>
							<td><?php echo $val['nome']; if($val['considerato'] == 2) echo '<span id="cap">(C)</span>'; ?></td>
							<td><?php echo $val['ruolo']; ?></td>
							<td><?php echo strtoupper(substr($val['nomeClub'],0,3)); ?></td>
							<td><?php if($val['titolare']) echo "X"; ?></td>
							<td><?php if(!empty($val['voto'])) echo $val['punti']; else echo "&nbsp;"; ?></td>
						</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php if(!empty($panch)): ?>
	<table class="column last" cellpadding="0" cellspacing="0">
		<caption>Panchinari</caption>
		<tbody>
			<tr>
				<th class="tableimg">&nbsp;</th>
				<th class="cognome">Cognome</th>
				<th class="nome">Nome</th>
				<th class="ruolo">Ruolo</th>
				<th class="club">Club</th>
				<th class="club">Titolare</th>
				<th class="punt">Punt.</th>
			</tr>
			<?php foreach($panch as $key => $val): ?>
					<?php if($val['considerato'] == 1): ?>
						<tr class="verde">
							<td class="tableimg">
								<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('id'=>$val['gioc'])); ?>"><img alt="Sostituito" title="Sostituito" src="<?php echo IMGSURL.'player-tit.png' ?>"/></a>
							</td>
					<?php elseif($val['considerato'] == 2): ?>
						<tr>
							<td class="tableimg">
								<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('id'=>$val['gioc'])); ?>"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL.'player-cap.png' ?>"/></a>
							</td>
					<?php else: ?>
						<tr>
							<td class="tableimg">
								<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val['gioc'])); ?>"><img alt="Panchinaro" title="Panchinaro" src="<?php echo IMGSURL.'player-panch.png' ?>"/></a>
							</td>
					<?php endif; ?>
							<td><?php echo $val['cognome']; ?></td>
							<td><?php echo $val['nome']; ?></td>
							<td><?php echo $val['ruolo']; ?></td>
							<td><?php echo strtoupper(substr($val['nomeClub'],0,3)); ?></td>
							<td><?php if($val['titolare']) echo "X"; ?></td>
							<td><?php if(!empty($val['voto'])) echo $val['punti']; else echo "&nbsp;"; ?></td>
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