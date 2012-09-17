<?php if(!PARTITEINCORSO || !STAGIONEFINITA): ?>
	<form action="<?php echo Links::getLink('trasferimenti',array('squadra'=>$_SESSION['idUtente'])); ?>" method="post">
		<fieldset class="no-margin no-padding">
<?php endif; ?>
			<table class="table tablesorter">
				<thead>
					<tr>
						<?php if(!PARTITEINCORSO && GIORNATA != 1 && $_SESSION['legaView'] == $_SESSION['idLega']): ?><th>Acq.</th><?php endif; ?>
						<th>Nome</th>
						<th class="hidden-phone">Club</th>
						<th>M. p.ti</th>
						<th>M. voti</th>
						<th>Partite</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($this->freeplayer as $key => $val): ?>
					<tr>
						<?php if(!PARTITEINCORSO && GIORNATA != 1 && $_SESSION['legaView'] == $_SESSION['idLega']): ?><td class="check"><input class="radio" type="radio" name="acquista" value="<?php echo $val->id; ?>" /></td><?php endif; ?>
						<td><?php echo $val; ?></td>
						<td class="hidden-phone"><?php echo strtoupper(substr($val->nomeClub,0,3)); ?></td>
						<td<?php if($val->avgPunti >= $this->defaultSufficenza && GIORNATA != 1) echo ' class="alert-success"';elseif(GIORNATA != 1)echo ' class="alert-error"'; ?>><?php echo (!empty($val->avgPunti)) ? $val->avgPunti : "&nbsp;"; ?></td>
						<td<?php if($val->avgVoti >= $this->defaultSufficenza && GIORNATA != 1)echo ' class="alert-success"';elseif(GIORNATA != 1)echo ' class="alert-error"'; ?>><?php echo (!empty($val->avgVoti)) ? $val->avgVoti : "&nbsp;"; ?></td>
						<td<?php if($val->presenzeVoto >= $this->defaultPartite && GIORNATA != 1)echo ' class="alert-success"';elseif(GIORNATA != 1)echo ' class="alert-error"'; ?>><?php echo $val->presenzeVoto; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
<?php if(!PARTITEINCORSO || !STAGIONEFINITA && $_SESSION['legaView'] == $_SESSION['idLega']): ?>
			<p class="alert-message alert alert-info">Se clicchi sul bottone sottostante selezionerai il giocatore per l'acquisto che comunque non avverrà subito e che può essere annullato. Nella pagina che ti apparirà dopo aver cliccato sul bottone ci sono altre informazioni</p>
			<input type="submit" class="btn btn-primary" value="Acquista" />
		</fieldset>
	</form>
<?php endif; ?>
