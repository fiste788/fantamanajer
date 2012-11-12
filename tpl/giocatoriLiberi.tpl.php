<?php if(!STAGIONEFINITA): ?>
	<form action="<?php echo Links::getLink('trasferimenti',array('squadra'=>$_SESSION['idUtente'])); ?>" method="post">
		<fieldset class="no-margin no-padding">
<?php endif; ?>
			<table class="table tablesorter">
				<thead>
					<tr>
						<?php if(GIORNATA != 1 && $_SESSION['legaView'] == $_SESSION['idLega']): ?><th>Acq.</th><?php endif; ?>
						<th>Nome</th>
						<th class="hidden-phone">Club</th>
                        <th><abbr title="Media punti">MP</abbr></th>
                        <th><abbr title="Media voti">MV</abbr></th>
						<th>Partite</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($this->freeplayer as $giocatore): ?>
					<tr>
						<?php if(GIORNATA != 1 && $_SESSION['legaView'] == $_SESSION['idLega']): ?><td class="check"><input class="radio" type="radio" name="acquista" value="<?php echo $giocatore->id; ?>" /></td><?php endif; ?>
                        <td><a href="<?php Links::getLink('dettaglioGiocatore', array('id'=>$giocatore->getId())) ?>"><?php echo $giocatore; ?></a></td>
						<td class="hidden-phone"><?php echo strtoupper(substr($giocatore->nomeClub,0,3)); ?></td>
						<td<?php if($giocatore->avgPunti >= $this->defaultSufficenza && GIORNATA != 1) echo ' class="alert-success"';elseif(GIORNATA != 1)echo ' class="alert-error"'; ?>><?php echo (!empty($giocatore->avgPunti)) ? $giocatore->avgPunti : "&nbsp;"; ?></td>
						<td<?php if($giocatore->avgVoti >= $this->defaultSufficenza && GIORNATA != 1)echo ' class="alert-success"';elseif(GIORNATA != 1)echo ' class="alert-error"'; ?>><?php echo (!empty($giocatore->avgVoti)) ? $giocatore->avgVoti : "&nbsp;"; ?></td>
						<td<?php if($giocatore->presenzeVoto >= $this->defaultPartite && GIORNATA != 1)echo ' class="alert-success"';elseif(GIORNATA != 1)echo ' class="alert-error"'; ?>><?php echo $giocatore->presenzeVoto; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
<?php if(!STAGIONEFINITA && $_SESSION['legaView'] == $_SESSION['idLega']): ?>
			<p class="alert-message alert alert-info">Se clicchi sul bottone sottostante selezionerai il giocatore per l'acquisto che comunque non avverrà subito e che può essere annullato. Nella pagina che ti apparirà dopo aver cliccato sul bottone ci sono altre informazioni</p>
			<input type="submit" class="btn btn-primary" value="Acquista" />
		</fieldset>
	</form>
<?php endif; ?>
