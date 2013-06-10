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
				<th>Nome</th>
				<th>Ruolo</th>
				<th>Club</th>
                <th class="hidden-phone"><abbr title="Titolare">Tit</abbr></th>
                <th class="hidden-phone"><abbr title="Ammonito">Amm</abbr></th>
                <th class="hidden-phone"><abbr title="Espulso">Esp</abbr></th>
                <th class="hidden-phone">Assist</th>
                <th class="hidden-phone">Gol</th>
                <th>Punti</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->titolari as $key => $giocatore): ?>
				<tr<?php echo ($giocatore->considerato == 0) ? ' class="alert-error"' : '' ?>>
					<td><a href="<?php echo $this->router->generate('giocatore_show',array('id'=>$giocatore->idGiocatore)); ?>"><?php echo ($giocatore->considerato == 2) ? $giocatore . '<span id="cap">(C)</span>' : $giocatore; ?></a></td>
					<td><?php echo $giocatore->ruolo; ?></td>
					<td><?php echo strtoupper(substr($giocatore->nomeClub,0,3)); ?></td>
					<td class="hidden-phone"><?php if($giocatore->titolare): ?><i class="icon-ok"></i><?php endif; ?></td>
                    <td class="hidden-phone"><?php if($giocatore->ammonito): ?><i class="icon-ok"></i><?php endif; ?></td>
                    <td class="hidden-phone"><?php if($giocatore->espulso): ?><i class="icon-ok"></i><?php endif; ?></td>
                    <td class="hidden-phone"><?php echo ($giocatore->gol != 0) ? $giocatore->gol : ""; ?></td>
                    <td class="hidden-phone"><?php echo ($giocatore->assist != 0) ? $giocatore->assist : ""; ?></td>
					<td><?php if(!empty($giocatore->punti)) echo ($giocatore->considerato == '2') ? $giocatore->punti * 2 : $giocatore->punti; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php if(!empty($this->panchinari)): ?>
		<table class="table">
			<caption>Panchinari</caption>
			<thead>
				<tr>
					<th>Nome</th>
                    <th>Ruolo</th>
                    <th>Club</th>
                    <th class="hidden-phone"><abbr title="Titolare">Tit</abbr></th>
                    <th class="hidden-phone"><abbr title="Ammonito">Amm</abbr></th>
                    <th class="hidden-phone"><abbr title="Espulso">Esp</abbr></th>
                    <th class="hidden-phone">Assist</th>
                    <th class="hidden-phone">Gol</th>
                    <th>Punti</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->panchinari as $key => $giocatore): ?>
					<tr<?php echo ($giocatore->considerato == 1) ? ' class="alert-success"' : '' ?>>
						<td><a href="<?php $this->router->generate('giocatore_show',array('id'=>$giocatore->idGiocatore)); ?>"><?php echo $giocatore; ?></a></td>
						<td><?php echo $giocatore->ruolo; ?></td>
						<td><?php echo strtoupper(substr($giocatore->nomeClub,0,3)); ?></td>
						<td class="hidden-phone"><?php if($giocatore->titolare): ?><i class="icon-ok"></i><?php endif; ?></td>
                        <td class="hidden-phone"><?php if($giocatore->ammonito): ?><i class="icon-ok"></i><?php endif; ?></td>
                        <td class="hidden-phone"><?php if($giocatore->espulso): ?><i class="icon-ok"></i><?php endif; ?></td>
                        <td class="hidden-phone"><?php echo ($giocatore->gol != 0) ? $giocatore->gol : ""; ?></td>
                        <td class="hidden-phone"><?php echo ($giocatore->assist != 0) ? $giocatore->assist : ""; ?></td>
						<td><?php echo (!empty($giocatore->punti)) ? $giocatore->punti : ""; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
<?php endif; ?>