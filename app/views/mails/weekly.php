<html>
	<body style="font-family:'Trebuchet MS',Arial,sans-serif;overflow:hidden">
        <h2>
            <a style="color:#00a2ff;text-decoration:none;" title="Home" href="<?php echo FULLBASEURL . \Lib\Router::generate('home'); ?>">FantaManajer</a>
        </h2>
		<div>
			<h3>
				<a style="color:#00a2ff;text-decoration:none;" href="<?php echo FULLBASEURL . \Lib\Router::generate('punteggio_show',array('giornata'=>$this->giornata,'squadra'=>$this->utente->id)); ?>">Punteggio: <?php echo $this->somma; ?></a>
			</h3>
			<?php if($this->formazione != FALSE): ?>
				<?php if(!is_null($this->penalità)): ?>
					<div>
						<span>Penalità: <?php echo $this->penalità->punteggio; ?> punti<br />Motivazione: <?php echo $this->penalità->penalità; ?></span>
					</div>
				<?php endif; ?>
				<table width="100%">
					<caption style="text-align:left;color:#00a2ff;font-weight:bold;">Titolari</caption>
					<tbody>
						<tr>
							<th style="text-align:left;" width="5%">&nbsp;</th>
							<th style="text-align:left;" width="25%">Cognome</th>
							<th style="text-align:left;" width="40%">Nome</th>
							<th style="text-align:left;" width="10%">Ruolo</th>
							<th style="text-align:left;" width="10%">Club</th>
							<th style="text-align:left;" width="5%">Tit</th>
							<th style="text-align:left;" width="5%">P.ti</th>
						</tr>
						<?php $panch = $this->formazione;$tito = array_splice($panch,0,11);?>
                        <?php foreach($tito as $key => $val): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo FULLBASEURL . \Lib\Router::generate('giocatore_show',array('id'=>$val->id)); ?>">
                                        <?php if($val->considerato == 0): ?>
                                            <img style="border:0 none" alt="Sostituito" title="Sostituito" src="<?php echo IMGSURL . 'player-rosso.png'; ?>"/>
                                        <?php elseif($val->considerato == 2): ?>
                                            <img style="border:0 none" alt="Capitano" title="Capitano" src="<?php echo IMGSURL . 'player-cap.png'; ?>"/>
                                        <?php else: ?>
                                            <img style="border:0 none" alt="Titolare" title="Titolare" src="<?php echo IMGSURL . 'player-tit.png'; ?>"/>
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td><?php echo $val->cognome; ?></td>
                                <td><?php echo ($val->considerato == 2) ? $val->nome . '<span id="cap">(C)</span>' : $val->nome; ?></td>
                                <td><?php echo $val->ruolo; ?></td>
                                <td><?php echo strtoupper(substr($val->nomeClub,0,3)); ?></td>
                                <td><?php echo ($val->titolare) ? "X" : "&nbsp;"; ?></td>
                                <td style="text-align:right"><?php if(!empty($val->punti) && $val->considerato == 2) echo $val->punti * 2;elseif(!empty($val->punti)) echo $val->punti;else echo "&nbsp;"; ?></td>
                            </tr>
                        <?php endforeach; ?>
					</tbody>
				</table>
				<br />
				<?php if(!empty($panch)): ?>
				<table width="100%">
					<caption style="text-align:left;color:#00a2ff;font-weight:bold;">Panchinari</caption>
					<tbody>
						<tr>
							<th style="text-align:left;" width="5%">&nbsp;</th>
							<th style="text-align:left;" width="25%">Cognome</th>
							<th style="text-align:left;" width="40%">Nome</th>
							<th style="text-align:left;" width="10%">Ruolo</th>
							<th style="text-align:left;" width="10%">Club</th>
							<th style="text-align:left;" width="5%">Tit</th>
							<th style="text-align:left;" width="5%">P.ti</th>
						</tr>
						<?php foreach($panch as $key => $val): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo FULLBASEURL . \Lib\Router::generate('giocatore_show',array('id'=>$val->id)); ?>">
                                        <?php if($val->considerato == 1): ?>
                                            <img style="border:0 none" alt="Sostituito" title="Sostituito" src="<?php echo IMGSURL . 'player-tit.png'; ?>"/>
                                        <?php elseif($val->considerato == 2): ?>
                                            <img style="border:0 none" alt="Titolare" title="Titolare" src="<?php echo IMGSURL . 'player-cap.png'; ?>"/>
                                        <?php else: ?>
                                            <img style="border:0 none" alt="Panchinaro" title="Panchinaro" src="<?php echo IMGSURL . 'player-panch.png'; ?>"/>
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td><?php echo $val->cognome; ?></td>
                                <td><?php echo $val->nome; ?></td>
                                <td><?php echo $val->ruolo; ?></td>
                                <td><?php echo strtoupper(substr($val->nomeClub,0,3)); ?></td>
                                <td><?php echo ($val->titolare) ? "X" : "&nbsp;"; ?></td>
                                <td style="text-align:right"><?php echo (!empty($val->punti)) ? $val->punti : "&nbsp;"; ?></td>
                            </tr>
                        <?php endforeach; ?>
					</tbody>
				</table>
				<?php endif; ?>
			<?php else: ?>
				<h3 style="color:#00a2ff;text-decoration:none;">Formazione non impostata</h3>
			<?php endif; ?>
			<?php if(isset($this->classifica)): ?>
			<div>
				<h3>
					<a style="color:#00a2ff;text-decoration:none;" href="<?php echo FULLBASEURL . \Lib\Router::generate('classifica'); ?>">Classifica</a>
				</h3>
				<table width="300">
					<tbody>
						<tr>
							<th>Squadra</th>
							<th>P.ti</th>
						</tr>
						<?php $i = 0; ?>
						<?php foreach ($this->classifica as $key => $val): ?>
							<tr>
								<td><?php echo $this->squadre[$key]->nomeSquadra; ?></td>
								<td style="text-align:right;"><?php echo array_sum($val); ?></td>
							</tr>
						<?php $i++; endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php endif; ?>
		</div>
	</body>
</html>
