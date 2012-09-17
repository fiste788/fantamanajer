<?php if ($_SESSION['logged'] == TRUE): ?>
    <div class="row-fluid">
        <div class="span6 well">
            <h3><a href="<?php echo Links::getLink('trasferimenti', array('id' => $_SESSION['idUtente'])); ?>">Trasferimenti</a></h3>
            <img height="64" width="64" class="logo left" alt="->" src="<?php echo IMGSURL . 'transfert.png'; ?>" title="Trasferimenti" />
            <p>Vedi i trasferimenti effettuati dalla tua e dalle altre squadre e seleziona il tuo acquisto</p>
        </div>
        <div class="span6 well">
            <h3><a href="<?php echo Links::getLink('giocatoriLiberi'); ?>">Giocatori Liberi</a></h3>
            <img height="64" width="64" class="logo left" alt="->" src="<?php echo IMGSURL . 'freeplayer.png'; ?>" title="Giocatori liberi" />
            <p>Guarda quì i giocatori liberi e le loro statistiche come la media voto e le partite giocate</p>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span6 well">
            <h3><a href="<?php echo Links::getLink('premi'); ?>">Premi</a></h3>
            <img height="64" width="64" class="logo left" alt="->" src="<?php echo IMGSURL . 'premi.png'; ?>" title="Premi" />
            <p>Consulta i premi che sono in palio per il vincitore e per i primi  quattro posti</p>
        </div>
        <div class="span6 well">
            <h3><a href="<?php echo Links::getLink('download'); ?>">Download</a></h3>
            <img height="64" width="64" class="logo left" alt="->" src="<?php echo IMGSURL . 'download.png'; ?>" title="Download" />
            <p>Scarica i voti di ogni giornata per controllare da te il punteggio ottenuto</p>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span6 well">
            <h3><a href="<?php echo Links::getLink('feed'); ?>">Eventi</a></h3>
            <img height="64" width="64" class="logo left" alt="->" src="<?php echo IMGSURL . 'eventi.png'; ?>" title="Eventi" />
            <p>Guarda tutto quello che succede al FantaManajer e seguilo grazie ai feed</p>
        </div>
        <?php if (!STAGIONEFINITA): ?>
            <div class="span6 well">
                <h3><a href="<?php echo Links::getLink('formazione'); ?>">Formazione</a></h3>
                <img height="64" width="64" class="logo left" alt="->" src="<?php echo IMGSURL . 'formazione.png'; ?>" title="Formazione" />
                <p>Imposta la squadra per la prossima giornata o vedi quella dei tuoi avversari</p>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<div class="row-fluid">
    <div class="span6 well">
        <h3><a href="<?php echo Links::getLink('contatti'); ?>">Contatti</a></h3>
        <img height="64" width="64" class="logo left" alt="->" src="<?php echo IMGSURL . 'contatti.png'; ?>" title="Contatti" />
        <p>Chiedi maggiori informazioni agli sviluppatori del FantaManajer attraverso le mail</p>
    </div>
    <div class="span6 well">
        <h3><a target="_blank" href="http://trac6.assembla.com/fantamanajer/wiki">Progetto</a></h3>
        <img height="64" width="64" class="logo left" alt="->" src="<?php echo IMGSURL . 'progetto.png'; ?>" title="Progetto" />
        <p>Per saperne di più sul FantaManajer, e per aiutarci a migliorarlo</p>
    </div>
</div>