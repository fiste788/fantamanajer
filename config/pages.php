<?php
/**
 * Creo un array in cui specifico titolo,js e css dell'head
 */

$pages = new \Lib\Pages();
$pages->addPage('home','Home',-1,'',0);
$pages->addPage('login','Login',-1,'',FALSE);
$pages->addPage('users_show','Modifica informazioni utente',0,'');
$pages->addPage('members_show','Dettaglio giocatore',-1,'altro',FALSE,array('flot'=>array('jquery.flot','jquery.flot.selection')));

$pages->addPage('clubsA','Clubs di A',-1,'clubsA',4);
$pages->addPage('clubs_index','Clubs di A',-1,'clubsA',4);
$pages->addPage('clubs_show','Club',-1,'clubsA',FALSE);
$pages->addPage('probabili_formazioni','Probabili formazioni',-1,'clubsA',1);

$pages->addPage('teams','Le squadre',-1,'championship',1,array('fancybox'=>'jquery.fancybox'));
$pages->addPage('articles','Conferenze stampa',-1,'championship',1);
$pages->addPage('ranking','Classifica',-1,'championship',3,array('flot'=>array('jquery.flot','jquery.flot.selection')));
$pages->addPage('scores_show','Dettaglio giornata',-1,'championship');
$pages->addPage('members_free','Giocatori liberi',0,'championship',3);
//$pages->addPage('altreFormazioni','Altre formazioni',0,'altro',FALSE,array('ui'=>array('jquery.ui.core','jquery.ui.widget','jquery.ui.mouse','jquery.ui.draggable','jquery.ui.droppable'),'custom'=>'createFormazione'));

$pages->addPage('altro',"Altro...",-1,'altro',6);
$pages->addPage('feed','Vedi gli eventi',-1,'altro',FALSE);
$pages->addPage('premi','Premi',-1,'altro',FALSE);
$pages->addPage('about','About',-1,'altro',3);
$pages->addPage('articles_new','Nuova conferenza',0,'altro',4);
$pages->addPage('articles_edit','Modifica conferenza',0,FALSE);
$pages->addPage('download','Downloads',0,'altro',5);

$pages->addPage('teams_show','Dettaglio squadra',-1,'team',FALSE,array('fancybox'=>'jquery.fancybox','fileupload'=>array('jquery.iframe-transport','vendor/jquery.ui.widget','jquery.fileupload','jquery.fileupload-process','jquery.fileupload-ui')));
//$pages->addPage('trasferimento_index','Trasferimenti',-1,'team');
$pages->addPage('transferts_index','Trasferimenti',0,'altro',2);
$pages->addPage('lineups','Formazione',0,'team',2,array('components/jquery-ui/ui'=>array('core','widget','mouse','draggable','droppable'),'custom'=>'createFormazione'));
$pages->addPage('lineups_show','Formazione',0,'team',FALSE,array('ui'=>array('core','widget','mouse','draggable','droppable'),'custom'=>'createFormazione'));
$pages->addPage('lineups_basic','Formazione',0,'team');

$pages->addPage('area_amministrativa','Area admin',1,'area_amministrativa',7);
$pages->addPage('sendMail','Invio mail formazione',-1,'area_amministrativa');
$pages->addPage('clean','Pulisci filesystem',-1,'area_amministrativa');
$pages->addPage('doTransfert','Lancia trasferimenti',-1,'area_amministrativa');
$pages->addPage('backup','Backup',-1,'area_amministrativa');
$pages->addPage('minify','Minify',1,'area_amministrativa');
$pages->addPage('less2css','Less2css',1,'area_amministrativa');
$pages->addPage('updateOrariGiornata','Aggiorna orari giornata corrente',-1,'altro');
$pages->addPage('weeklyScript','Calcolo punteggi',-1,'area_amministrativa');
$pages->addPage('updateGiocatori','Aggiorna lista giocatori',-1,'area_amministrativa');
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
