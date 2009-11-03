<div id="freeplayer" class="main-content">
	<?php if($this->validFilter): ?>
	<?php if(!PARTITEINCORSO || !STAGIONEFINITA): ?>
	<form name="acq" action="<?php echo $this->linksObj->getLink('trasferimenti',array('squad'=>$_SESSION['idSquadra'])); ?>" method="post"><?php endif; ?>
	<table cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<?php if(!PARTITEINCORSO && GIORNATA != 1 && $_SESSION['legaView'] == $_SESSION['idLega']): ?><th class="check">Acq.</th><?php endif; ?>
				<th class="tableimg">&nbsp;</th>
				<th class="cognome"><a href="<?php echo $this->link['cognome'] ?>">Cognome</a></th>
				<th class="nome"><a href="<?php echo $this->link['nome'] ?>">Nome</a></th>
				<th class="club"><a href="<?php echo $this->link['club'] ?>">Club</a></th>
				<th class="club"><a href="<?php echo $this->link['voti'] ?>">M. p.ti</a></th>
				<th class="club"><a href="<?php echo $this->link['votiEff'] ?>">M. voti</a></th>
				<th class="club"><a href="<?php echo $this->link['partiteGiocate'] ?>">Partite</a></th>
			</tr>
			<?php foreach($this->freeplayer as $key => $val): ?>
			<tr>
				<?php if(!PARTITEINCORSO && GIORNATA != 1 && $_SESSION['legaView'] == $_SESSION['idLega']): ?><td class="check"><input class="radio" type="radio" name="acquista" value="<?php echo $val['idGioc']; ?>" /></td><?php endif; ?>
				<td class="tableimg">
					<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val['idGioc'])) ?>">
				<?php if($val['voti'] >= $this->suff && $val['partiteGiocate'] >= $this->partite ||GIORNATA == 1): ?>
					<img alt="Verde" title="Verde" src="<?php echo IMGSURL.'player-tit.png' ?>"/>
				<?php elseif($val['voti'] >= $this->suff || $val['partiteGiocate'] >= $this->partite): ?>
					<img alt="Giallo" title="Giallo" src="<?php echo IMGSURL.'player-panch.png' ?>"/>
				<?php else: ?>
					<img alt="Rosso" title="Rosso" src="<?php echo IMGSURL.'player-rosso.png' ?>"/>
				<?php endif; ?>
					</a>
				</td>
				<td><?php echo $val['cognome']; ?></td>
				<td><?php if(!empty($val['nome'])) echo $val['nome']; else echo "&nbsp;" ?></td>
				<td><?php echo strtoupper(substr($val['club'],0,3)); ?></td>
				<td<?php if(!empty($val['votiAll'])) echo ' title="' . $val['votiAll'] . '"'; ?><?php if($val['voti'] >= $this->suff && GIORNATA != 1) echo ' class="verde"'; elseif(GIORNATA != 1) echo ' class="rosso"'; ?>><?php if(!empty($val['voti'])) echo $val['voti']; else echo "&nbsp;" ?></td>
				<td<?php if(!empty($val['votiEffAll'])) echo ' title="' . $val['votiEffAll'] . '"'; ?><?php if($val['voti'] >= $this->suff && GIORNATA != 1) echo ' class="verde"'; elseif(GIORNATA != 1) echo ' class="rosso"'; ?>><?php if(!empty($val['votiEff'])) echo $val['votiEff']; else echo "&nbsp;" ?></td>
				<td<?php if($val['partiteGiocate'] >= $this->partite && GIORNATA != 1) echo ' class="verde"'; elseif(GIORNATA != 1) echo ' class="rosso"'; ?>><?php echo $val['partiteGiocate']; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php if(!PARTITEINCORSO || !STAGIONEFINITA && $_SESSION['legaView'] == $_SESSION['idLega']): ?><p>Se clicchi sul bottone sottostante selezionerai il giocatore per l'acquisto che comunque non avverrà subito e che può essere annullato. Nella pagina che ti apparirà dopo aver cliccato sul bottone ci sono altre informazioni</p>
	<input type="submit" class="submit dark" value="Acquista" />
	</form><?php endif; ?>
<?php else: ?>
Parametri non validi
<?php endif; ?>
</div>
