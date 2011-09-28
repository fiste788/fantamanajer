<?php if($this->validFilter): ?>
	<?php if(!PARTITEINCORSO || !STAGIONEFINITA): ?>
		<form action="<?php echo Links::getLink('trasferimenti',array('squadra'=>$_SESSION['idUtente'])); ?>" method="post">
			<fieldset class="no-margin no-padding">
	<?php endif; ?>
	<table cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<?php if(!PARTITEINCORSO && GIORNATA != 1 && $_SESSION['legaView'] == $_SESSION['idLega']): ?><th class="check">Acq.</th><?php endif; ?>
				<th class="tableimg">&nbsp;</th>
				<th class="cognome"><a href="<?php echo $this->link['cognome']; ?>">Cognome</a></th>
				<th class="nome"><a href="<?php echo $this->link['nome']; ?>">Nome</a></th>
				<th class="club"><a href="<?php echo $this->link['nomeClub']; ?>">Club</a></th>
				<th class="club"><a href="<?php echo $this->link['avgPunti']; ?>">M. p.ti</a></th>
				<th class="club"><a href="<?php echo $this->link['avgVoti']; ?>">M. voti</a></th>
				<th class="club"><a href="<?php echo $this->link['presenzeVoto']; ?>">Partite</a></th>
			</tr>
			<?php foreach($this->freeplayer as $key => $val): ?>
			<tr>
				<?php if(!PARTITEINCORSO && GIORNATA != 1 && $_SESSION['legaView'] == $_SESSION['idLega']): ?><td class="check"><input class="radio" type="radio" name="acquista" value="<?php echo $val->idGioc; ?>" /></td><?php endif; ?>
				<td class="tableimg">
					<a href="<?php echo Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->idGioc)); ?>">
				<?php if($val->voti >= $this->suff && $val->presenzeVoto >= $this->partite ||GIORNATA == 1): ?>
					<img width="21" height="21" alt="Verde" title="Verde" src="<?php echo IMGSURL . 'player-tit.png'; ?>"/>
				<?php elseif($val->voti >= $this->suff || $val->presenzeVoto >= $this->partite): ?>
					<img width="21" height="21" alt="Giallo" title="Giallo" src="<?php echo IMGSURL . 'player-panch.png'; ?>"/>
				<?php else: ?>
					<img width="21" height="21" alt="Rosso" title="Rosso" src="<?php echo IMGSURL . 'player-rosso.png'; ?>"/>
				<?php endif; ?>
					</a>
				</td>
				<td><?php echo $val->cognome; ?></td>
				<td><?php echo (!empty($val->nome)) ? $val->nome : "&nbsp;"; ?></td>
				<td><?php echo strtoupper(substr($val->nomeClub,0,3)); ?></td>
				<td<?php if($val->avgPunti >= $this->suff && GIORNATA != 1)echo ' class="verde"';elseif(GIORNATA != 1)echo ' class="rosso"'; ?>><?php echo (!empty($val->avgPunti)) ? $val->avgPunti : "&nbsp;"; ?></td>
				<td<?php if($val->avgVoti >= $this->suff && GIORNATA != 1)echo ' class="verde"';elseif(GIORNATA != 1)echo ' class="rosso"'; ?>><?php echo (!empty($val->avgVoti)) ? $val->avgVoti : "&nbsp;"; ?></td>
				<td<?php if($val->presenzeVoto >= $this->partite && GIORNATA != 1)echo ' class="verde"';elseif(GIORNATA != 1)echo ' class="rosso"'; ?>><?php echo $val->presenzeVoto; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php if(!PARTITEINCORSO || !STAGIONEFINITA && $_SESSION['legaView'] == $_SESSION['idLega']): ?><p>Se clicchi sul bottone sottostante selezionerai il giocatore per l'acquisto che comunque non avverrà subito e che può essere annullato. Nella pagina che ti apparirà dopo aver cliccato sul bottone ci sono altre informazioni</p>
		<input type="submit" class="submit" value="Acquista" />
		</fieldset>
	</form>
	<?php endif; ?>
<?php else: ?>
	<span>Parametri non validi</span>
<?php endif; ?>
