<?php if(!PARTITEINCORSO || !STAGIONEFINITA): ?>
	<form action="<?php echo Links::getLink('trasferimenti',array('squadra'=>$_SESSION['idUtente'])); ?>" method="post">
		<fieldset class="no-margin no-padding">
<?php endif; ?>
			<table class="tablesorter">
				<thead>
					<tr>
						<?php if(!PARTITEINCORSO && GIORNATA != 1 && $_SESSION['legaView'] == $_SESSION['idLega']): ?><th>Acq.</th><?php endif; ?>
						<th>Cognome</th>
						<th>Nome</th>
						<th>Club</th>
						<th>M. p.ti</th>
						<th>M. voti</th>
						<th>Partite</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($this->freeplayer as $key => $val): ?>
					<tr>
						<?php if(!PARTITEINCORSO && GIORNATA != 1 && $_SESSION['legaView'] == $_SESSION['idLega']): ?><td class="check"><input class="radio" type="radio" name="acquista" value="<?php echo $val->id; ?>" /></td><?php endif; ?>
						<td><?php echo $val->cognome; ?></td>
						<td><?php echo (!empty($val->nome)) ? $val->nome : "&nbsp;"; ?></td>
						<td><?php echo strtoupper(substr($val->nomeClub,0,3)); ?></td>
						<td<?php if($val->avgPunti >= $this->request->get('sufficenza') && GIORNATA != 1) echo ' class="verde"';elseif(GIORNATA != 1)echo ' class="rosso"'; ?>><?php echo (!empty($val->avgPunti)) ? $val->avgPunti : "&nbsp;"; ?></td>
						<td<?php if($val->avgVoti >= $this->request->get('sufficenza') && GIORNATA != 1)echo ' class="verde"';elseif(GIORNATA != 1)echo ' class="rosso"'; ?>><?php echo (!empty($val->avgVoti)) ? $val->avgVoti : "&nbsp;"; ?></td>
						<td<?php if($val->presenzeVoto >= $this->request->get('partite') && GIORNATA != 1)echo ' class="verde"';elseif(GIORNATA != 1)echo ' class="rosso"'; ?>><?php echo $val->presenzeVoto; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
<?php if(!PARTITEINCORSO || !STAGIONEFINITA && $_SESSION['legaView'] == $_SESSION['idLega']): ?>
			<p>Se clicchi sul bottone sottostante selezionerai il giocatore per l'acquisto che comunque non avverrà subito e che può essere annullato. Nella pagina che ti apparirà dopo aver cliccato sul bottone ci sono altre informazioni</p>
			<input type="submit" class="btn primary" value="Acquista" />
		</fieldset>
	</form>
<?php endif; ?>
