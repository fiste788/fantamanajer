<?php if ($_SESSION['roles'] == "2"): ?>
    <h3 class="riga">Amministrazione lega</h3>
<?php endif; ?>
<div class="row-fluid">
    <div class="span6 well">
        <img height="64" width="64" alt="->" src="<?php echo IMGSURL . 'squadre.png'; ?>" title="Gestione Squadre" />
        <h3><a href="<?php echo Links::getLink('creaSquadra', array('a' => 'new', 'id' => '0', 'lega' => $_SESSION['datiLega']->idLega)); ?>">Gestione squadre</a></h3>
        <p>Crea una nuova squadra o modifica/cancella una esistente</p>
    </div>
    <div class="span6 well">
        <img height="64" width="64" alt="->" src="<?php echo IMGSURL . 'transfert.png'; ?>" title="Gestione trasferimenti" />
        <h3><a href="<?php echo Links::getLink('nuovoTrasferimento'); ?>">Gestione trasferimenti</a></h3>
        <p>Effettua un nuovo trasferimento o uno scambio</p>
    </div>
</div>
<div class="row-fluid">
    <div class="span6 well">
        <img height="64" width="64" alt="->" src="<?php echo IMGSURL . 'formazione.png'; ?>" title="Gestione formazioni" />
        <h3><a href="<?php echo Links::getLink('inserisciFormazione'); ?>">Gestione formazioni</a></h3>
        <p>Inserisci una vecchia formazione e calcolane i punti</p>
    </div>
    <div class="span6 well">
        <img height="64" width="64" alt="->" src="<?php echo IMGSURL . 'contatti.png'; ?>" title="Newsletter" />
        <h3><a href="<?php echo Links::getLink('newsletter'); ?>">Newsletter</a></h3>
        <p>Invia le mail con le ultime novità sul FantaManajer</p>
    </div>
</div>
<div class="row-fluid">
    <div class="span6 well">
        <img height="64" width="64" alt="->" src="<?php echo IMGSURL . 'penalita.png'; ?>" title="Gestione penalità" />
        <h3><a href="<?php echo Links::getLink('penalita'); ?>">Gestione penalità</a></h3>
        <p>Inserisci e vedi le penalità inflitte alle squadre</p>
    </div>
    <div class="span6 well">
        <img height="64" width="64" alt="->" src="<?php echo IMGSURL . 'impostazioni.png'; ?>" title="Modifica impostazioni" />
        <h3><a href="<?php echo Links::getLink('impostazioni'); ?>">Modifica impostazioni</a></h3>
        <p>Cambia le opzioni per la tua lega come il n° di trasferimenti, il capitano doppio...</p>
    </div>
</div>
<?php if ($_SESSION['roles'] == "2"): ?>
    <h3 class="riga">Amministrazione FantaManajer</h3>
    <div class="row-fluid">
        <div class="span6 well">
            <img height="64" width="64" alt="->" src="<?php echo IMGSURL . 'freeplayer.png'; ?>" title="Modifica giocatore" />
            <h3><a href="<?php echo Links::getLink('modificaGiocatore'); ?>">Modifica giocatore</a></h3>
            <p>Cambia nome e cognome e carica una nuova foto ai giocatori</p>
        </div>
        <div class="span6 well">
            <img height="64" width="64" alt="->" src="<?php echo IMGSURL . 'run.png'; ?>" title="Lancia script" />
            <h3><a href="<?php echo Links::getLink('lanciaScript'); ?>">Lancia script</a></h3>
            <p>Da questa pagina puoi lanciare i 4 script che gestiscono il FantaManajer</p>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span6 well">
            <img height="64" width="64" alt="->" src="<?php echo IMGSURL . 'gestione-database.png'; ?>" title="Gestione database" />
            <h3><a href="<?php echo Links::getLink('gestioneDatabase'); ?>">Gestione database</a></h3>
            <p>Sincronizza il tuo db in locale o esegui una query</p>
        </div>
        <div class="span6 well">
            <img height="64" width="64" alt="->" src="<?php echo IMGSURL . 'calendario.png'; ?>" title="Gestione giornate" />
            <h3><a href="<?php echo Links::getLink('giornate'); ?>">Gestione giornate</a></h3>
            <p>Modifica gli orari di inizio o di fine delle giornate</p>
        </div>
    </div>
<?php endif; ?>
