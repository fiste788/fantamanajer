<?php $squadra = (isset($_GET['squadra'])) ? $_GET['squadra'] : $_SESSION['idSquadra']; ?>
<div id="operazioni" class="column last">
	<ul class="operazioni-content">
	<?php if(STAGIONEFINITA == FALSE): ?>
	<?php if(PARTITEINCORSO): ?>
		<li><a class="imp-formazione column last operazione" href="<?php echo Links::getLink('altreFormazioni'); ?>">Guarda la formazione</a></li>
	<?php else: ?>
		<li><a class="imp-formazione column last operazione" href="<?php echo Links::getLink('formazione'); ?>">Imposta la formazione</a></li>
	<?php endif; ?>
	<?php endif; ?>
		<li><a class="new-stampa column last operazione" href="<?php echo Links::getLink('modificaConferenza',array('a'=>'new','id'=>'0')); ?>">Rilascia una conferenza</a></li>
		<li><a class="see-transfert column last operazione" href="<?php echo Links::getLink('trasferimenti',array('squadra'=>$squadra)); ?>">Guarda i trasferimenti</a></li>
		<li><a class="see-freeplayer column last operazione" href="<?php echo Links::getLink('giocatoriLiberi'); ?>">Guarda i giocatori liberi</a></li>
		<li><a class="see-premi column last operazione" href="<?php echo Links::getLink('premi'); ?>">Guarda i premi</a></li>
		<li><a class="download column last operazione" href="<?php echo Links::getLink('download'); ?>">Area download</a></li>
		<?php if($_SESSION['roles'] > 0): ?>
			<li><a class="admin-area column last operazione" href="<?php echo Links::getLink('areaAmministrativa'); ?>">Area amministrativa</a></li>
		<?php endif; ?>
	</ul>
</div>
