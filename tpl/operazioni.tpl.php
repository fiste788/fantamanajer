<?php if(isset($_GET['squadra'])) $squadra = $_GET['squadra']; else $squadra = $_SESSION['idSquadra']; ?>
<div id="operazioni" class="column last">
	<ul class="operazioni-content">
	<?php if(STAGIONEFINITA == FALSE): ?>
	<?php if(PARTITEINCORSO): ?>
		<li><a class="imp-formazione column last operazione" href="<?php echo $this->linksObj->getLink('altreFormazioni'); ?>">Guarda la formazione</a></li>
	<?php else: ?>
		<li><a class="imp-formazione column last operazione" href="<?php echo $this->linksObj->getLink('formazione'); ?>">Imposta la formazione</a></li>
	<?php endif; ?>
	<?php endif; ?>
		<li><a class="new-stampa column last operazione" href="<?php echo $this->linksObj->getLink('modificaConferenza',array('a'=>'new','id'=>'0')); ?>">Rilascia una conferenza</a></li>
		<li><a class="see-transfert column last operazione" href="<?php echo $this->linksObj->getLink('trasferimenti',array('squadra'=>$squadra)); ?>">Guadra i trasferimenti</a></li>
		<li><a class="see-freeplayer column last operazione" href="<?php echo $this->linksObj->getLink('giocatoriLiberi'); ?>">Guadra i giocatori liberi</a></li>
		<li><a class="see-premi column last operazione" href="<?php echo $this->linksObj->getLink('premi'); ?>">Guadra i premi</a></li>
		<li><a class="download column last operazione" href="<?php echo $this->linksObj->getLink('download'); ?>">Area download</a></li>
		<?php if($_SESSION['roles'] > 0): ?>
			<li><a class="admin-area column last operazione" href="<?php echo $this->linksObj->getLink('areaAmministrativa'); ?>">Area amministrativa</a></li>
		<?php endif; ?>
	</ul>
</div>
