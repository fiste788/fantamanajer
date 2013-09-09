<?php
/**
 * Creo un array in cui specifico titolo,js e css dell'head
 */

require_once(LIBDIR . 'Pages.lib.php');

$pages = new Pages();
$pages->addPage('home','Home',-1,'',0);
$pages->addPage('home_login','Home',-1,'',0);
$pages->addPage('squadre','Le squadre',-1,'squadre',1,array('fancybox'=>'jquery.fancybox'));
$pages->addPage('clubsA','Clubs di A',-1,'clubsA',4);
$pages->addPage('club_index','Rose',-1,'clubsA',1);
$pages->addPage('club_show','Club',-1,'clubsA',FALSE,array('tablesorter'=>'tablesorter'));
$pages->addPage('probabili_formazioni','Probabili formazioni',-1,'clubsA',1);
$pages->addPage('altro',"Altro...",-1,'altro',6);
$pages->addPage('articoli','Conferenze stampa',-1,'altro',1);
$pages->addPage('classifica','Classifica',-1,'classifica',3,array('flot'=>array('jquery.flot','jquery.flot.selection')));
$pages->addPage('squadra_show','Dettaglio squadra',-1,'squadre',FALSE,array('fancybox'=>'jquery.fancybox','fileupload'=>array('jquery.iframe-transport','vendor/jquery.ui.widget','jquery.fileupload','jquery.fileupload-ui')));
$pages->addPage('area_amministrativa','Area admin',1,'area_amministrativa',7);
$pages->addPage('giocatore_show','Dettaglio giocatore',-1,'altro',FALSE,array('flot'=>array('jquery.flot','jquery.flot.selection')));
$pages->addPage('punteggio_show','Dettaglio giornata',-1,'classifica');
$pages->addPage('feed','Vedi gli eventi',-1,'altro',FALSE);
$pages->addPage('premi','Premi',-1,'altro',FALSE);
$pages->addPage('contatti','Contatti',-1,'altro',3);
$pages->addPage('sendMail','Invio mail formazione',-1,'altro');
$pages->addPage('clean','Pulisci filesystem',-1,'altro');
$pages->addPage('doTransfert','Lancia trasferimenti',-1,'altro');
$pages->addPage('backup','Backup',-1,'altro');
$pages->addPage('minify','Minify',1,'area_amministrativa');
$pages->addPage('less2css','Less2css',1,'area_amministrativa');
$pages->addPage('updateOrariGiornata','Aggiorna orari giornata corrente',-1,'altro');
$pages->addPage('weeklyScript','Calcolo punteggi',-1,'altro');
$pages->addPage('updateGiocatori','Aggiorna lista giocatori',-1,'altro');
$pages->addPage('trasferimento_index','Trasferimenti',-1,'altro');
$pages->addPage('formazione','Formazione',0,'formazione',2,array('ui'=>array('jquery.ui.core','jquery.ui.widget','jquery.ui.mouse','jquery.ui.draggable','jquery.ui.droppable'),'custom'=>'createFormazione'));
$pages->addPage('formazione_show','Formazione',0,'formazione',FALSE,array('ui'=>array('jquery.ui.core','jquery.ui.widget','jquery.ui.mouse','jquery.ui.draggable','jquery.ui.droppable'),'custom'=>'createFormazione'));
$pages->addPage('formazioneBasic','Formazione',0,'altro');
$pages->addPage('utente_show','Modifica informazioni utente',0,'');
$pages->addPage('trasferimento_index','Trasferimenti',0,'altro',2);
$pages->addPage('giocatori_liberi','Giocatori liberi',0,'altro',3,array('tablesorter'=>'tablesorter'));
$pages->addPage('altreFormazioni','Altre formazioni',0,'altro',FALSE,array('ui'=>array('jquery.ui.core','jquery.ui.widget','jquery.ui.mouse','jquery.ui.draggable','jquery.ui.droppable'),'custom'=>'createFormazione'));
$pages->addPage('articolo_edit','Nuova conferenza',0,'altro',4);
$pages->addPage('download','Downloads',0,'altro',5);
$pages->addPage('squadra_new','Crea una nuova squadra',1,'area_amministrativa',5,array('ui'=>array('jquery.ui.core','jquery.ui.widget','jquery.ui.button','jquery.ui.mouse','jquery.ui.position','jquery.ui.dialog')));
$pages->addPage('trasferimento_new','Nuovo trasferimento',1,'area_amministrativa',6);
$pages->addPage('formazione_insert','Inserisci formazione mancante',1,'area_amministrativa',7);
$pages->addPage('newsletter','Newsletter',1,'area_amministrativa',8);
$pages->addPage('penalità','Penalità',1,'area_amministrativa',9);
$pages->addPage('impostazioni','Impostazioni lega',1,'area_amministrativa',10);
$pages->addPage('gestione_database','Gestione database',2,'area_amministrativa',1);
$pages->addPage('lancia_script','Lancia script',2,'area_amministrativa',2);
//$pages->addPage('modificaGiocatore','Modifica giocatore',2,'area_amministrativa',3,array('flot'=>array('jquery.flot','jquery.flot.selection')));
$pages->addPage('giornata','Giornata',2,'area_amministrativa',4,array('ui'=>array('jquery.ui.core','jquery.ui.datepicker')));
$pages->finalize();
