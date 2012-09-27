<?php
/**
 * Creo un array in cui specifico titolo,js e css dell'head
 */

require_once(INCDIR . 'pages.inc.php');

$pages = new Pages();
$pages->addPage('home','Home',-1,'',0);
$pages->addPage('squadre','Le squadre',-1,'squadre',1,array('fancybox'=>'fancybox'));
$pages->addPage('clubsA','Clubs di A',-1,'clubsA',4);
$pages->addPage('clubs','Rose',-1,'clubsA',1);
$pages->addPage('dettaglioClub','Club',-1,'clubsA',FALSE,array('tablesorter'=>'tablesorter'));
$pages->addPage('probabiliFormazioni','Probabili formazioni',-1,'clubsA',1);
$pages->addPage('altro',"Altro...",-1,'altro',6);
$pages->addPage('conferenzeStampa','Conferenze stampa',-1,'altro',1);
$pages->addPage('classifica','Classifica',-1,'classifica',3,array('flot'=>array('jquery.flot','jquery.flot.selection')));
$pages->addPage('dettaglioSquadra','Dettaglio squadra',-1,'squadre',FALSE,array('tablesorter'=>'tablesorter','fancybox'=>'fancybox'));
$pages->addPage('areaAmministrativa','Area admin',1,'areaAmministrativa',7);
$pages->addPage('dettaglioGiocatore','Dettaglio giocatore',-1,'altro',FALSE,array('flot'=>array('jquery.flot','jquery.flot.selection')));
$pages->addPage('dettaglioGiornata','Dettaglio giornata',-1,'classifica');
$pages->addPage('premi','Premi',-1,'altro',FALSE);
$pages->addPage('contatti','Contatti',-1,'altro',3);
$pages->addPage('sendMail','Invio mail formazione',-1,'altro');
$pages->addPage('clean','Pulisci filesystem',-1,'altro');
$pages->addPage('doTransfert','Lancia trasferimenti',-1,'altro');
$pages->addPage('backup','Backup',-1,'altro');
$pages->addPage('minify','Minify',1,'areaAmministrativa');
$pages->addPage('less2css','Less2css',1,'areaAmministrativa');
$pages->addPage('updateOrariGiornata','Aggiorna orari giornata corrente',-1,'altro');
$pages->addPage('weeklyScript','Calcolo punteggi',-1,'altro');
$pages->addPage('updateGiocatori','Aggiorna lista giocatori',-1,'altro');
$pages->addPage('feed','Vedi gli eventi',-1,'altro',FALSE);
$pages->addPage('formazione','Formazione',0,'formazione',2,array('ui'=>array('jquery.ui.core','jquery.ui.widget','jquery.ui.mouse','jquery.ui.draggable','jquery.ui.droppable'),'custom'=>'createFormazione'));
$pages->addPage('formazioneBasic','Formazione',0,'altro');
$pages->addPage('utente','Modifica informazioni utente',0,'');
$pages->addPage('trasferimenti','Trasferimenti',0,'altro',2);
$pages->addPage('giocatoriLiberi','Giocatori liberi',0,'altro',3,array('tablesorter'=>'tablesorter'));
$pages->addPage('altreFormazioni','Altre formazioni',0,'altro',FALSE,array('ui'=>array('jquery.ui.core','jquery.ui.widget','jquery.ui.mouse','jquery.ui.draggable','jquery.ui.droppable'),'custom'=>'createFormazione'));
$pages->addPage('modificaConferenza','Nuova conferenza',0,'altro',4);
$pages->addPage('download','Downloads',0,'altro',5);
$pages->addPage('creaSquadra','Crea una nuova squadra',1,'areaAmministrativa',5,array('ui'=>array('jquery.ui.core','jquery.ui.widget','jquery.ui.button','jquery.ui.mouse','jquery.ui.position','jquery.ui.dialog')));
$pages->addPage('nuovoTrasferimento','Nuovo trasferimento',1,'areaAmministrativa',6);
$pages->addPage('inserisciFormazione','Inserisci formazione mancante',1,'areaAmministrativa',7);
$pages->addPage('newsletter','Newsletter',1,'areaAmministrativa',8);
$pages->addPage('penalita','Penalità',1,'areaAmministrativa',9);
$pages->addPage('impostazioni','Impostazioni lega',1,'areaAmministrativa',10);
$pages->addPage('gestioneDatabase','Gestione database',2,'areaAmministrativa',1);
$pages->addPage('lanciaScript','Lancia script',2,'areaAmministrativa',2);
$pages->addPage('modificaGiocatore','Modifica giocatore',2,'areaAmministrativa',3,array('flot'=>array('jquery.flot','jquery.flot.selection')));
$pages->addPage('giornata','Giornata',2,'areaAmministrativa',4,array('ui'=>array('jquery.ui.core','jquery.ui.datepicker')));
$pages->finalize();
?>
