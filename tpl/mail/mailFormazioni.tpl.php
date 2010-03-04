<?php ?>
<html>
	<?php $i = 0; ?>
	<body style="font-family:'Trebuchet MS',Arial,sans-serif">
		<a title="Home" href="<?php echo PROTO . $_SERVER['SERVER_NAME']; ?>">
			<img style="border:0 none" alt="Header-logo" src="<?php echo IMGSURL . 'header.png'; ?>" />
		</a>
		<div>
			<?php foreach ($this->titolari as $squadra => $formazione): ?>
				<?php if($i % 2 == 0): ?>
					<div style="margin: 0;clear:both;width:100%;float:left;">
				<?php endif; ?>
				<div style="float:left;width:50%;">
					<h3>
						<a style="color:#00a2ff;text-decoration:none;" href="<?php echo PROTO . $_SERVER['SERVER_NAME'] . Links::getLink('altreFormazione',array('giornata'=>GIORNATA,'squadra'=>$squadra)); ?>"><?php echo $this->squadre[$squadra]->nome; ?></a>
					</h3>
				<?php if ($formazione != FALSE): ?>
					<h4 style="color:#00a2ff;text-decoration:none;">Titolari</h4>
					<table style="border-collapse:separate;border-spacing:0;margin:0 0 1.4em 0;width:100%;clear:both;">
					<?php foreach($formazione as $key => $val): ?>
						<tr>
						<?php if($this->cap[$squadra]->C == $val->idGioc) : ?>
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
							<td style="text-align:left;"><?php echo $val->cognome; ?></td>
							<td style="text-align:left;"><?php echo $val->nome; ?></td>
							<td style="text-align:left;"><?php if(array_search($val->idGioc,get_object_vars($this->cap[$squadra])) != FALSE)  echo array_search($val->idGioc,get_object_vars($this->cap[$squadra])); else echo '&nbsp;'; ?></td>
						</tr>
					<?php endforeach; ?>
					</table>
					<?php if($this->panchinari[$squadra] != FALSE): ?>
						<h4 style="color:#00a2ff;text-decoration:none;">Panchinari</h4>
						<table style="border-collapse:separate;border-spacing:0;margin:0 0 1.4em 0;width:100%;clear:both;">
						<?php foreach($this->panchinari[$squadra] as $key => $val): ?>
							<tr>
								<td>
									<a href="<?php echo PROTO . $_SERVER['SERVER_NAME'] . Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGioc)); ?>">
										<img style="border:0 none" alt="Panchinaro" title="Panchinaro" src="<?php echo IMGSURL . 'player-panch.png'; ?>"/>
									</a>
								</td>
								<td style="text-align:left;"><?php echo $val->cognome; ?></td>
								<td style="text-align:left;"><?php echo $val->nome; ?></td>
							</tr>
						<?php endforeach; ?>
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
