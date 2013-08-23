<?php

require_once(VENDORDIR . 'AltoRouter\AltoRouter.php');

$router = new AltoRouter();
$router->setBasePath('/fantamanajer-new');

// mapping routes
$router->map('GET', '', array('controller'=>'page','action'=>'home'), 'home');
$router->map('POST', '/login', array('controller'=>'utente','action'=>'login'), 'login');
$router->map('GET', '/logout', array('controller'=>'utente','action'=>'logout'), 'logout');
$router->map('GET', '/utente', array('controller'=>'utente','action'=>'edit'), 'utente_show');
$router->map('POST','/utente', array('controller'=>'utente','action'=>'update'), 'utente_update');
$router->map('GET', '/contatti', array('controller'=>'page','action'=>'contatti'), 'contatti');
$router->map('GET', '/articoli/[i:giornata]?', array('controller'=>'articolo','action'=>'index'), 'articoli');
$router->map('GET', '/articoli/new', array('controller'=>'articolo','action'=>'build'), 'articolo_new');
$router->map('POST','/articoli', array('controller'=>'articolo','action'=>'create'), 'articolo_create');
$router->map('GET', '/articoli/[i:id]/[edit:action]', array('controller'=>'articolo','action'=>'edit'), 'articolo_edit');
$router->map('POST','/articoli/[i:id]', array('controller'=>'articolo','action'=>'update'), 'articolo_update');
$router->map('GET', '/articoli/[i:id]/[delete:action]', array('controller'=>'articolo','action'=>'delete'), 'articolo_delete');
//$router->map('GET', '/squadre', 'squadra','action'=>'index', 'squadre');
$router->map('GET', '/squadre[.xml|.json:format]?', array('controller'=>'squadra','action'=>'index'), 'squadre');
$router->map('GET', '/squadre/[i:id]', array('controller'=>'squadra','action'=>'show'), 'squadra_show');
$router->map('GET', '/giocatori/[i:id][*:title]?', array('controller'=>'giocatore','action'=>'show'), 'giocatore_show');
$router->map('GET|POST', '/giocatori/liberi', array('controller'=>'giocatore','action'=>'free'), 'giocatori_liberi');
$router->map('GET', '/clubs', array('controller'=>'club','action'=>'index'), 'club_index');
$router->map('GET', '/clubs/[i:id]', array('controller'=>'club','action'=>'show'), 'club_show');
$router->map('GET', '/formazione', array('controller'=>'formazione','action'=>'build'), 'formazione');
$router->map('GET', '/formazione', array('controller'=>'formazione','action'=>'build'), 'formazione_edit');
//$router->map('GET', '/formazione/new', 'formazione','action'=>'build', 'formazione_new');
$router->map('GET', '/formazione/insert_old', array('controller'=>'formazione','action'=>'insertOld'), 'formazione_insert');
$router->map('GET', '/probabili_formazioni', array('controller'=>'page','action'=>'probabiliFormazioni'), 'probabili_formazioni');
$router->map('GET', '/trasferimenti/[i:squadra]?', array('controller'=>'trasferimento','action'=>'index'), 'trasferimento_index');
//$router->map('GET', '/trasferimenti/[i:id]', 'trasferimento','action'=>'index', 'trasferimento_show');
$router->map('GET', '/download', array('controller'=>'trasferimento','action'=>'show'), 'download');
$router->map('GET', '/classifica', array('controller'=>'punteggio','action'=>'index'), 'classifica');
$router->map('GET', '/dettaglio_giornata/[i:giornata]/[i:squadra]', array('controller'=>'punteggio','action'=>'show'), 'punteggio_show');
$router->map('GET', '/crea_squadra', array('controller'=>'squadra','action'=>'build'), 'squadra_new');
$router->map('GET', '/trasferimento/new', array('controller'=>'trasferimento','action'=>'build'), 'trasferimento_new');
$router->map('GET', '/impostazioni', array('controller'=>'lega','action'=>'edit'), 'impostazioni');
$router->map('POST', '/impostazioni', array('controller'=>'lega','action'=>'update'), 'impostazioni_update');
$router->map('GET', '/feed', array('controller'=>'evento','action'=>'feed'), 'feed');


$router->map('GET', '/penalità', array('controller'=>'punteggio','action'=>'penalità'), 'penalità');
$router->map('GET', '/newsletter', array('controller'=>'trasferimento','action'=>'build'), 'newsletter');
$router->map('GET', '/lancia_script', array('controller'=>'squadra','action'=>'build'), 'lancia_script');
$router->map('GET', '/giornata', array('controller'=>'squadra','action'=>'build'), 'giornata');
$router->map('GET', '/gestione_database', array('controller'=>'squadra','action'=>'new'), 'gestione_database');

$router->map('GET', '/', array('controller'=>'punteggio','action'=>'show'), 'area_amministrativa');
$router->map('GET', '/', array('controller'=>'clubs','action'=>'index'), 'clubsA');
$router->map('GET', '/', array('controller'=>'clubs','action'=>'index'), 'altro');
