<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'admin-area-big.png'; ?>" alt="Logo Squadre" />
	</div>
	<h2 class="column">Area amministrativa</h2>
</div>
<div id="other" class="main-content">
	<div class="box-squadra column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">	
			<img class="column last" alt="->" src="<?php echo IMGSURL.'rose-big.png'; ?>" title="Gestione Squadre" />
			<h3><a href="<?php echo $this->linksObj->getLink('creaSquadra',array('a'=>'new','id'=>'0')); ?>">Crea una nuova squadra o modifica/cancella una esistente</a></h3>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<div class="box-squadra column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<img class="column last" alt="->" src="<?php echo IMGSURL.'transfert-other.png'; ?>" title="Premi" />
			<h3><a href="<?php echo $this->linksObj->getLink('nuovoTrasferimento'); ?>">Effettua un nuovo trasferimento o uno scambio</a></h3>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<div class="box-squadra column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<img class="column last" alt="->" src="<?php echo IMGSURL.'formazione-other.png'; ?>" title="Formazione" />
			<h3><a href="<?php echo $this->linksObj->getLink('inserisciFormazione'); ?>">Inserisci una vecchia formazione e calcolane i punti</a></h3>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<div class="box-squadra column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<img class="column last" alt="->" src="<?php echo IMGSURL.'contatti-other.png'; ?>" title="Newsletter" />
			<h3><a href="<?php echo $this->linksObj->getLink('newsletter'); ?>">Newsletter</a></h3>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<div class="box-squadra column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<h3>Manutenzione</h3>	
			<img class="column last" alt="->" src="<?php echo IMGSURL.'freeplayer-other.png'; ?>" title="Giocatori liberi" />
			<ul>
				<li><a href="<?php echo $this->linksObj->getLink('lanciaScript'); ?>">Lancia script</a></li>
				<li><a href="<?php echo $this->linksObj->getLink('gestioneDatabase'); ?>">Gestione database</a></li>
			</ul>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
</div>
	<?php if($_SESSION['logged'] == TRUE): ?>
	<div id="squadradett" class="column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
		<?php if(isset($_SESSION['message']) && $_SESSION['message'][0] == 0): ?>
			<div class="messaggio good column last">
				<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
				<span><?php echo $_SESSION['message'][1]; ?></span>
			</div>
			<script type="text/javascript">
			$(document).ready(function() {$('.messaggio').show('pulsate',{times: 3 }); });
			$(".messaggio").click(function () {
				$("div.messaggio").fadeOut("slow");
			});
			</script>
			<?php unset($_SESSION['message']); ?>
		<?php endif; ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<?php endif; ?>
