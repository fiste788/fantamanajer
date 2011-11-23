<?php if($_SESSION['logged'] == TRUE): ?>
	<div class="box column last">
		<h3><a href="<?php echo Links::getLink('trasferimenti',array('id'=>$_SESSION['idUtente'])); ?>">Trasferimenti</a></h3>
		<img height="64" width="64" class="column" alt="->" src="<?php echo IMGSURL . 'transfert.png'; ?>" title="Trasferimenti" />
		<div>Vedi i trasferimenti effettuati dalla tua e dalle altre squadre e seleziona il tuo acquisto</div>
	</div>
	<div class="box column last">
		<h3><a href="<?php echo Links::getLink('giocatoriLiberi'); ?>">Giocatori Liberi</a></h3>	
		<img height="64" width="64" class="column" alt="->" src="<?php echo IMGSURL . 'freeplayer.png'; ?>" title="Giocatori liberi" />
		<div>Guarda quì i giocatori liberi e le loro statistiche come la media voto e le partite giocate</div>
	</div>
	<div class="box column last">
		<h3><a href="<?php echo Links::getLink('premi'); ?>">Premi</a></h3>
		<img height="64" width="64" class="column" alt="->" src="<?php echo IMGSURL . 'premi.png'; ?>" title="Premi" />
		<div>Consulta i premi che sono in palio per il vincitore e per i primi  quattro posti</div>
	</div>
	<div class="box column last">
		<h3><a href="<?php echo Links::getLink('download'); ?>">Download</a></h3>
		<img height="64" width="64" class="column" alt="->" src="<?php echo IMGSURL . 'download.png'; ?>" title="Download" />
		<div>Scarica i voti di ogni giornata per controllare da te il punteggio ottenuto</div>
	</div>
	<div class="box column last">
		<h3><a href="<?php echo Links::getLink('feed'); ?>">Eventi</a></h3>
		<img height="64" width="64" class="column" alt="->" src="<?php echo IMGSURL . 'eventi.png'; ?>" title="Eventi" />
		<div>Guarda tutto quello che succede al FantaManajer e seguilo grazie ai feed</div>
	</div>
	<?php if(!STAGIONEFINITA): ?>
	<div class="box column last">
		<h3><a href="<?php echo Links::getLink('formazione'); ?>">Formazione</a></h3>
		<img height="64" width="64" class="column" alt="->" src="<?php echo IMGSURL . 'formazione.png'; ?>" title="Formazione" />
		<div>Imposta la squadra per la prossima giornata o vedi quella dei tuoi avversari</div>
	</div>
	<?php endif; ?>
<?php endif; ?>
<div class="box column last">
	<h3><a href="<?php echo Links::getLink('contatti'); ?>">Contatti</a></h3>
	<img height="64" width="64" class="column" alt="->" src="<?php echo IMGSURL . 'contatti.png'; ?>" title="Contatti" />	
	<div>Chiedi maggiori informazioni agli sviluppatori del FantaManajer attraverso le mail</div>
</div>
<div class="box column last">
	<h3><a target="_blank" href="http://trac6.assembla.com/fantamanajer/wiki">Progetto</a></h3>	
	<img height="64" width="64" class="column" alt="->" src="<?php echo IMGSURL . 'progetto.png'; ?>" title="Progetto" />
	<div>Per saperne di più sul FantaManajer, e per aiutarci a migliorarlo</div>
</div>
