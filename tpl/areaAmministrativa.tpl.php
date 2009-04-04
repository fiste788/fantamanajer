<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'admin-area-big.png'; ?>" alt="Logo Squadre" />
	</div>
	<h2 class="column">Area amministrativa</h2>
</div>
<div id="other" class="main-content">
	<?php if($_SESSION['usertype'] == "superadmin"): ?><h3 class="riga">Amministrazione lega</h3><?php endif; ?>
	<div class="box-squadra column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">	
			<img class="column last" alt="->" src="<?php echo IMGSURL.'rose.png'; ?>" title="Gestione Squadre" />
			<h3><a href="<?php echo $this->linksObj->getLink('creaSquadra',array('a'=>'new','id'=>'0')); ?>">Gestione squadre</a></h3>
			<div>Crea una nuova squadra o modifica/cancella una esistente</div>
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
			<img class="column last" alt="->" src="<?php echo IMGSURL.'transfert-other.png'; ?>" title="Gestione trasferimenti" />
			<h3><a href="<?php echo $this->linksObj->getLink('nuovoTrasferimento'); ?>">Gestione trasferimenti</a></h3>
			<div>Effettua un nuovo trasferimento o uno scambio</div>
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
			<img class="column last" alt="->" src="<?php echo IMGSURL.'formazione-other.png'; ?>" title="Gestione formazioni" />
			<h3><a href="<?php echo $this->linksObj->getLink('inserisciFormazione'); ?>">Gestione formazioni</a></h3>
			<div>Inserisci una vecchia formazione e calcolane i punti</div>
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
			<div>Invia le mail con le ultime novità sul FantaManajer</div>
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
			<img class="column last" alt="->" src="<?php echo IMGSURL.'penalita.png'; ?>" title="Gestione penalità" />
			<h3><a href="<?php echo $this->linksObj->getLink('penalita'); ?>">Gestione penalità</a></h3>
			<div>Inserisci e vedi le penalità inflitte alle squadre</div>
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
			<img class="column last" alt="->" src="<?php echo IMGSURL.'impostazioni.png'; ?>" title="Modifica impostazioni" />
			<h3><a href="<?php echo $this->linksObj->getLink('impostazioni'); ?>">Modifica impostazioni</a></h3>
			<div>Modifica le opzioni per la tua lega come il n° di trasferimenti, il capitano doppio...</div>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<?php if($_SESSION['usertype'] == 'superadmin'): ?>
	<h3 class="riga">Amministrazione FantaManajer</h3>
	<div class="box-squadra column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<img class="column last" alt="->" src="<?php echo IMGSURL.'freeplayer-other.png'; ?>" title="Modifica giocatore" />
			<h3><a href="<?php echo $this->linksObj->getLink('modificaGiocatore'); ?>">Modifica giocatore</a></h3>
			<div>Cambia nome e cognome e carica una nuova foto ai giocatori</div>
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
			<img class="column last" alt="->" src="<?php echo IMGSURL.'run.png'; ?>" title="Lancia script" />
			<h3><a href="<?php echo $this->linksObj->getLink('lanciaScript'); ?>">Lancia script</a></h3>
			<div>Da questa pagina puoi lanciare i 4 script che gestiscono il FantaManajer</div>
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
			<img class="column last" alt="->" src="<?php echo IMGSURL.'gestione-database.png'; ?>" title="Gestione database" />
			<h3><a href="<?php echo $this->linksObj->getLink('gestioneDatabase'); ?>">Gestione database</a></h3>
			<div>Sincronizza il tuo db in locale o esegui una query</div>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<?php endif; ?>
</div>
	<?php if($_SESSION['logged'] == TRUE): ?>
	<div id="squadradett" class="column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
		<?php if(isset($_SESSION['message']) && $_SESSION['message'][0] == 0): ?>
			<div id="messaggio" class="messaggio good column last">
				<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
				<span><?php echo $_SESSION['message'][1]; ?></span>
			</div>
			<script type="text/javascript">
			window.onload = (function(){
	 			$("#messaggio").effect("pulsate", { times: 3 }, 1000);
				$("#messaggio").click(function () {
					$("div#messaggio").fadeOut("slow");
				});
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
