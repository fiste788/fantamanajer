<h4>Giornata: <span><?php echo (isset($this->giornata)) ? $this->giornata : ''; ?></span></h4>
<h4>Squadra: <span><?php echo (isset($this->squadra)) ? $this->squadraDett->nome : ''; ?></span></h4>
<h4>Punteggio: <span><?php echo (isset($this->somma)) ? $this->somma : ''; ?></span></h4>
<?php if(isset($this->titolari) && 	$this->titolari != FALSE && $this->titolari != NULL): ?>
	<?php if(isset($this->penalità)): ?>
		<img class="column" alt="!" src="<?php echo IMGSURL . 'penalita.png'; ?>" />
		<div class="penalita column last">
			<h5>Penalità: <?php echo $this->penalità->punteggio; ?></h5>
			<h5>Motivazione: <?php echo $this->penalità->penalità; ?></h5>
		</div>
	<?php endif; ?>
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
			<?php foreach($this->titolari as $key => $val): ?>
					<?php if($val->considerato == 0): ?>
						<tr class="rosso">
							<td class="tableimg">
								<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGioc)); ?>"><img alt="Sostituito" title="Sostituito" src="<?php echo IMGSURL . 'player-rosso.png'; ?>"/></a>
							</td>
					<?php elseif($val->considerato == 2): ?>
						<tr>
							<td class="tableimg">
								<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGioc)); ?>"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL . 'player-cap.png'; ?>"/></a>
							</td>
					<?php $val->punti *= 2; else: ?>
						<tr>
							<td class="tableimg">
								<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGioc)); ?>"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL . 'player-tit.png'; ?>"/></a>
							</td>
					<?php endif; ?>		
							<td><?php echo $val->cognome; ?></td>
							<td><?php echo ($val->considerato == 2) ? $val->nome . '<span id="cap">(C)</span>' : $val->nome; ?></td>
							<td><?php echo $val->ruolo; ?></td>
							<td><?php echo strtoupper(substr($val->nomeClub,0,3)); ?></td>
							<td><?php echo ($val->titolare) ? "X" : "&nbsp;"; ?></td>
							<td><?php echo (!empty($val->punti)) ? $val->punti : "&nbsp;"; ?></td>
						</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php if(!empty($this->panchinari)): ?>
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
			<?php foreach($this->panchinari as $key => $val): ?>
				<?php if($val->considerato == 1): ?>
					<tr class="verde">
						<td class="tableimg">
							<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGioc)); ?>"><img alt="Sostituito" title="Sostituito" src="<?php echo IMGSURL . 'player-tit.png' ?>"/></a>
						</td>
				<?php elseif($val->considerato == 2): ?>
					<tr>
						<td class="tableimg">
							<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGioc)); ?>"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL . 'player-cap.png' ?>"/></a>
						</td>
				<?php else: ?>
					<tr>
						<td class="tableimg">
							<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGioc)); ?>"><img alt="Panchinaro" title="Panchinaro" src="<?php echo IMGSURL . 'player-panch.png' ?>"/></a>
						</td>
				<?php endif; ?>
						<td><?php echo $val->cognome; ?></td>
						<td><?php echo $val->nome; ?></td>
						<td><?php echo $val->ruolo; ?></td>
						<td><?php echo strtoupper(substr($val->nomeClub,0,3)); ?></td>
						<td><?php echo ($val->titolare) ? "X" : "&nbsp;"; ?></td>
						<td><?php echo (!empty($val->punti)) ? $val->punti : "&nbsp;"; ?></td>
					</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
<?php elseif(isset($this->formazione) && $this->formazione == FALSE): ?>
	<span class="column" style="clear:both;">Formazione non settata</span>
<?php else: ?>
	<span class="column" style="clear:both;">Parametri mancanti o errati</span>
<?php endif; ?>