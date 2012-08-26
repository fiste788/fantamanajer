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
				<th class="cognome">Nome</th>
				<th class="ruolo">Ruolo</th>
				<th class="club">Club</th>
				<th class="club">Titolare</th>
				<th class="punt">Punt.</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->titolari as $key => $val): ?>
				<tr<?php echo ($val->considerato == 0) ? ' class="alert-error"' : '' ?>">
					<td><?php echo $val; ?></td>
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
					<th class="cognome">Nome</th>
					<th class="ruolo">Ruolo</th>
					<th class="club">Club</th>
					<th class="club">Titolare</th>
					<th class="punt">Punt.</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->panchinari as $key => $val): ?>
					<tr<?php echo ($val->considerato == 1) ? ' class="alert-success"' : '' ?>">
						<td><?php echo $val; ?></td>
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