<?php ?>
<html>
	<body style="font-family:'Trebuchet MS',Arial,sans-serif;overflow:hidden">
		<a title="Home" href="<?php echo PROTO . $_SERVER['SERVER_NAME']; ?>">
			<img style="border:0 none" alt="Header-logo" src="<?php echo IMGSURL . 'header.png'; ?>" />
		</a>
		<div>
			<h3>
				<a style="color:#00a2ff;text-decoration:none;" href="<?php echo PROTO . $_SERVER['SERVER_NAME'] . Links::getLink('dettaglioGiornata',array('giornata'=>$this->giornata,'squadra'=>$this->utente->id)); ?>">Punteggio: <?php echo $this->somma; ?></a>
			</h3>
			<?php if($this->formazione != FALSE): ?>
				<?php if(isset($this->penalità)): ?>
					<div>
						<?php foreach($this->penalità as $key => $val): ?>
							<span>Penalità: <?php echo $val->punteggio; ?> punti<br />Motivazione: <?php echo $val->penalità; ?></span>
						<?php endforeach; ?>
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
						<?php if($val->considerato == 0): ?>
							<td>
								<a href="<?php echo PROTO . $_SERVER['SERVER_NAME'] . Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGioc)); ?>">
									<img style="border:0 none" alt="Sostituito" title="Sostituito" src="<?php echo IMGSURL . 'player-rosso.png'; ?>"/>
								</a>
							</td>
						<?php elseif($val->considerato == 2): ?>
							<td>
								<a href="<?php echo PROTO . $_SERVER['SERVER_NAME'] . Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGioc)); ?>">
									<img style="border:0 none" alt="Capitano" title="Capitano" src="<?php echo IMGSURL . 'player-cap.png'; ?>"/>
								</a>
							</td>
						<?php else: ?>
							<td>
								<a href="<?php echo PROTO . $_SERVER['SERVER_NAME'] . Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGioc)); ?>">
									<img style="border:0 none" alt="Titolare" title="Titolare" src="<?php echo IMGSURL . 'player-tit.png'; ?>"/>
								</a>
							</td>
						<?php endif; ?>
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
						<?php if($val->considerato == 1): ?>
							<td>
								<a href="<?php echo PROTO . $_SERVER['SERVER_NAME'] . Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGioc)); ?>">
									<img style="border:0 none" alt="Sostituito" title="Sostituito" src="<?php echo IMGSURL . 'player-tit.png'; ?>"/>
								</a>
							</td>
						<?php elseif($val->considerato == 2): ?>
							<td>
								<a href="<?php echo PROTO . $_SERVER['SERVER_NAME'] . Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGioc)); ?>">
									<img style="border:0 none" alt="Titolare" title="Titolare" src="<?php echo IMGSURL . 'player-cap.png'; ?>"/>
								</a>
							</td>
						<?php else: ?>
							<td>
								<a href="<?php echo PROTO . $_SERVER['SERVER_NAME'] . Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGioc)); ?>">
									<img style="border:0 none" alt="Panchinaro" title="Panchinaro" src="<?php echo IMGSURL . 'player-panch.png'; ?>"/>
								</a>
							</td>
						<?php endif; ?>
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
					<a style="color:#00a2ff;text-decoration:none;" href="<?php echo PROTO . $_SERVER['SERVER_NAME'] . Links::getLink('classifica'); ?>">Classifica</a>
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
								<td><?php echo $this->squadre[$key]->nome; ?></td>
								<td style="text-align:right;"><?php echo $val; ?></td>
							</tr>
						<?php $i++; endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php endif; ?>
		</div>
	</body>
</html>
