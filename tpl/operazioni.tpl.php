<?php if(isset($_GET['squadra'])) $squadra = $_GET['squadra']; else $squadra=$_SESSION['idSquadra']; ?>
<div id="operazioni" class="column last">
	<a title="Nascondi" href="#oper" class="toggle operazioni-title column last">Operazioni</a>
		<ul class="operazioni-content">
		<?php if(TIMEOUT != '0'): ?>
		<?php if($this->timeout == false): ?>
			<li><a class="imp-formazione column last operazione" href="<?php echo $this->linksObj->getLink('altreFormazioni'); ?>">Guarda la formazione</a></li>
		<?php else: ?>
			<li><a class="imp-formazione column last operazione" href="<?php echo $this->linksObj->getLink('formazione'); ?>">Imposta la formazione</a></li>
		<?php endif; ?>
	   <?php endif; ?>
			<li><a class="new-stampa column last operazione" href="<?php echo $this->linksObj->getLink('modificaConferenza',array('a'=>'new','id'=>'0')); ?>">Rilascia una conferenza</a></li>
			<li><a class="see-transfert column last operazione" href="<?php echo $this->linksObj->getLink('trasferimenti',array('squad'=>$squadra)); ?>">Guadra i trasferimenti</a></li>
			<li><a class="see-freeplayer column last operazione" href="<?php echo $this->linksObj->getLink('giocatoriLiberi'); ?>">Guadra i giocatori liberi</a></li>
			<li><a class="see-premi column last operazione" href="<?php echo $this->linksObj->getLink('premi'); ?>">Guadra i premi</a></li>
			<?php if($_SESSION['usertype'] == 'admin'): ?>
				<li><a class="admin-area column last operazione" href="<?php echo $this->linksObj->getLink('areaAmministrativa'); ?>">Area amministrativa</a></li>
			<?php endif; ?>
		</ul>
	</div>
	<script type="text/javascript">
			$(document).ready(function() {
				$("a.toggle").click(function() {
					$("#operazioni > .operazioni-content").slideToggle();
				})
			});
		</script>
