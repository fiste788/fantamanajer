<?php if($_SESSION['roles'] == "2"): ?><h3 class="riga">Amministrazione lega</h3><?php endif; ?>
<div class="box column last">
	<img class="column" alt="->" src="<?php echo IMGSURL . 'squadre.png'; ?>" title="Gestione Squadre" />
	<h3><a href="<?php echo $this->linksObj->getLink('creaSquadra',array('a'=>'new','id'=>'0','lega'=>$_SESSION['datiLega']['idLega'])); ?>">Gestione squadre</a></h3>
	<div>Crea una nuova squadra o modifica/cancella una esistente</div>
</div>
<div class="box column last">
	<img class="column" alt="->" src="<?php echo IMGSURL . 'transfert-other.png'; ?>" title="Gestione trasferimenti" />
	<h3><a href="<?php echo $this->linksObj->getLink('nuovoTrasferimento'); ?>">Gestione trasferimenti</a></h3>
	<div>Effettua un nuovo trasferimento o uno scambio</div>
</div>
<div class="box column last">
	<img class="column" alt="->" src="<?php echo IMGSURL . 'formazione.png'; ?>" title="Gestione formazioni" />
	<h3><a href="<?php echo $this->linksObj->getLink('inserisciFormazione'); ?>">Gestione formazioni</a></h3>
	<div>Inserisci una vecchia formazione e calcolane i punti</div>
</div>
<div class="box column last">
	<img class="column" alt="->" src="<?php echo IMGSURL . 'contatti.png'; ?>" title="Newsletter" />
	<h3><a href="<?php echo $this->linksObj->getLink('newsletter'); ?>">Newsletter</a></h3>
	<div>Invia le mail con le ultime novità sul FantaManajer</div>
</div>
<div class="box column last">
	<img class="column" alt="->" src="<?php echo IMGSURL . 'penalita.png'; ?>" title="Gestione penalità" />
	<h3><a href="<?php echo $this->linksObj->getLink('penalita'); ?>">Gestione penalità</a></h3>
	<div>Inserisci e vedi le penalità inflitte alle squadre</div>
</div>
<div class="box column last">
	<img class="column" alt="->" src="<?php echo IMGSURL . 'impostazioni.png'; ?>" title="Modifica impostazioni" />
	<h3><a href="<?php echo $this->linksObj->getLink('impostazioni'); ?>">Modifica impostazioni</a></h3>
	<div>Cambia le opzioni per la tua lega come il n° di trasferimenti, il capitano doppio...</div>
</div>
<?php if($_SESSION['roles'] == "2"): ?>
<h3 class="riga">Amministrazione FantaManajer</h3>
<div class="box column last">
	<img class="column" alt="->" src="<?php echo IMGSURL . 'freeplayer.png'; ?>" title="Modifica giocatore" />
	<h3><a href="<?php echo $this->linksObj->getLink('modificaGiocatore'); ?>">Modifica giocatore</a></h3>
	<div>Cambia nome e cognome e carica una nuova foto ai giocatori</div>
</div>
<div class="box column last">
	<img class="column" alt="->" src="<?php echo IMGSURL . 'run.png'; ?>" title="Lancia script" />
	<h3><a href="<?php echo $this->linksObj->getLink('lanciaScript'); ?>">Lancia script</a></h3>
	<div>Da questa pagina puoi lanciare i 4 script che gestiscono il FantaManajer</div>
</div>
<div class="box column last">
	<img class="column" alt="->" src="<?php echo IMGSURL . 'gestione-database.png'; ?>" title="Gestione database" />
	<h3><a href="<?php echo $this->linksObj->getLink('gestioneDatabase'); ?>">Gestione database</a></h3>
	<div>Sincronizza il tuo db in locale o esegui una query</div>
</div>
<div class="box column last">
	<img class="column" alt="->" src="<?php echo IMGSURL . 'calendario.png'; ?>" title="Gestione giornate" />
	<h3><a href="<?php echo $this->linksObj->getLink('giornate'); ?>">Gestione giornate</a></h3>
	<div>Modifica gli orari di inizio o di fine delle giornate</div>
</div>
<?php endif; ?>
