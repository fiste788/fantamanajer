<html>
	<?php $i = 0; ?>
	<body style="font-family:'Trebuchet MS',Arial,sans-serif;overflow:hidden">
		<a title="Home" href="<?php echo PROTO . $_SERVER['SERVER_NAME']; ?>">
			<img style="border:0 none" alt="Header-logo" src="<?php echo IMGSURL . 'header.png'; ?>" />
		</a>
		<div>
			<?php foreach ($this->squadre as $key => $squadra): ?>
				<?php if($i % 2 == 0): ?>
					<div style="margin: 0;clear:both;width:100%;float:left;">
				<?php endif; ?>
				<div style="float:left;width:50%;">
					<h3>
						<a style="color:#00a2ff;text-decoration:none;" href="<?php echo PROTO . $_SERVER['SERVER_NAME'] . Links::getLink('altreFormazione',array('giornata'=>GIORNATA,'squadra'=>$key)); ?>"><?php echo $squadra->nomeSquadra; ?></a>
					</h3>
				<?php if (isset($this->formazione[$key])): ?>
					<h4 style="color:#00a2ff;text-decoration:none;">Titolari</h4>
					<table style="border-collapse:separate;border-spacing:0;margin:0 0 1.4em 0;width:100%;clear:both;">
					<?php for($i = 0; $i < 11 ; $i++): ?>
						<tr>
						<?php if(FALSE) : ?>
							<td>
								<a href="<?php echo PROTO . $_SERVER['SERVER_NAME'] . Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$this->formazione[$key]->giocatori[$i])); ?>">
									<img style="border:0 none" alt="Capitano" title="Capitano" src="<?php echo IMGSURL . 'player-cap.png'; ?>"/>
								</a>
							</td>
						<?php else: ?>
							<td>
								<a href="<?php echo PROTO . $_SERVER['SERVER_NAME'] . Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$this->formazione[$key]->giocatori[$i]->idGiocatore)); ?>">
									<img style="border:0 none" alt="Titolare" title="Titolare" src="<?php echo IMGSURL . 'player-tit.png'; ?>"/>
								</a>
							</td>
						<?php endif; ?>
							<td style="text-align:left;"><?php echo $this->giocatori[$key][$this->formazione[$key]->giocatori[$i]->idGiocatore]->cognome; ?></td>
							<td style="text-align:left;"><?php echo $this->giocatori[$key][$this->formazione[$key]->giocatori[$i]->idGiocatore]->nome; ?></td>

						</tr>
					<?php endfor; ?>
					</table>
					<?php if(isset($this->formazione[$key]->giocatori[11])): ?>
						<h4 style="color:#00a2ff;text-decoration:none;">Panchinari</h4>
						<table style="border-collapse:separate;border-spacing:0;margin:0 0 1.4em 0;width:100%;clear:both;">
						<?php for($i = 11; $i < 18 ; $i++): ?>
							<?php if(isset($this->formazione[$key]->giocatori[$i])): ?>
							<tr>
								<td>
									<a href="<?php echo PROTO . $_SERVER['SERVER_NAME'] . Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$this->formazione[$key]->giocatori[$i]->idGiocatore)); ?>">
										<img style="border:0 none" alt="Panchinaro" title="Panchinaro" src="<?php echo IMGSURL . 'player-panch.png'; ?>"/>
									</a>
								</td>
								<td style="text-align:left;"><?php echo $this->giocatori[$key][$this->formazione[$key]->giocatori[$i]->idGiocatore]->cognome; ?></td>
								<td style="text-align:left;"><?php echo $this->giocatori[$key][$this->formazione[$key]->giocatori[$i]->idGiocatore]->nome; ?></td>
							</tr>
							<?php endif; ?>
						<?php endfor; ?>
						</table>
					<?php endif; ?>
				<?php else: ?>
					<p>Non ha settato la formazione</p>
				<?php endif; ?>
				</div>
				<?php $i++; ?>
				<?php if($i%2 == 0): ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			<p style="font-size:12px;clear:both;float:left;width:100%;">
				Si prega di non rispondere a questa mail in quanto non verrà presa in considerazione.<br />
				Per domande o chiarimenti contatta gli amministratori all'indirizzo <a style="color:#00a2ff;text-decoration:none;" href="mailto:admin@fantamanajer.it">admin@fantamanajer.it</a>
			</p>
		</div>
	</body>
</html>
