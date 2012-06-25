<?php if($this->titolari != NULL): ?>
	<h4>Punteggio: <span><?php echo (isset($this->somma)) ? $this->somma : ''; ?></span></h4>
	<?php if($this->penalità != FALSE): ?>
		<img class="column" alt="!" src="<?php echo IMGSURL . 'penalita.png'; ?>" />
		<div class="penalita column last">
			<h5>Penalità: <?php echo $this->penalità->punteggio; ?></h5>
			<h5>Motivazione: <?php echo $this->penalità->penalità; ?></h5>
		</div>
	<?php endif; ?>
	<table class="table">
		<caption>Titolari</caption>
		<thead>
			<tr>
				<th class="tableimg">&nbsp;</th>
				<th class="cognome">Cognome</th>
				<th class="nome">Nome</th>
				<th class="ruolo">Ruolo</th>
				<th class="club">Club</th>
				<th class="club">Titolare</th>
				<th class="punt">Punt.</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->titolari as $key => $val): ?>
				<tr<?php echo ($val->considerato == 0) ? ' class="alert-error"' : '' ?>">
					<td class="tableimg"><a href="<?php echo Links::getLink('dettaglioGiocatore',array('edit'=>'view','giocatore'=>$val->idGiocatore)); ?>"><img alt="->" title="<?php if($val->considerato == 1) echo 'Sostituito'; elseif($val->considerato == 2) echo 'Titolare'; else echo 'Panchinaro' ?>"  src="<?php echo IMGSURL . 'player-'; if($val->considerato == 1) echo 'tit'; elseif($val->considerato == 2) echo 'cap'; else echo 'rosso';echo '.png' ?>"/></a></td>
					<td><?php echo $val->cognome; ?></td>
					<td><?php echo ($val->considerato == 2) ? $val->nome . '<span id="cap">(C)</span>' : $val->nome; ?></td>
					<td><?php echo $val->ruolo; ?></td>
					<td><?php echo strtoupper(substr($val->nomeClub,0,3)); ?></td>
					<td><?php echo ($val->titolare) ? "X" : "&nbsp;"; ?></td>
					<td><?php if(!empty($val->punti))  echo ($val->considerato == '2') ? $val->punti * 2 : $val->punti; else "&nbsp;"; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php if(!empty($this->panchinari)): ?>
		<table class="table">
			<caption>Panchinari</caption>
			<thead>
				<tr>
					<th class="tableimg">&nbsp;</th>
					<th class="cognome">Cognome</th>
					<th class="nome">Nome</th>
					<th class="ruolo">Ruolo</th>
					<th class="club">Club</th>
					<th class="club">Titolare</th>
					<th class="punt">Punt.</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->panchinari as $key => $val): ?>
					<tr<?php echo ($val->considerato == 1) ? ' class="alert-success"' : '' ?>">
						<td class="tableimg"><a href="<?php echo Links::getLink('dettaglioGiocatore',array('edit'=>'view','giocatore'=>$val->idGiocatore)); ?>"><img alt="->" title="<?php if($val->considerato == 1) echo 'Sostituito'; elseif($val->considerato == 2) echo 'Titolare'; else echo 'Panchinaro' ?>"  src="<?php echo IMGSURL . 'player-'; if($val->considerato == 1) echo 'tit'; elseif($val->considerato == 2) echo 'cap'; else echo 'panch';echo '.png' ?>"/></a></td>
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
<?php endif; ?>