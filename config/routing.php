<?php

require_once(VENDORDIR . 'AltoRouter\AltoRouter.php');

$router = new AltoRouter();
$router->setBasePath('/fantamanajer-new');

// mapping routes
$router->map('GET', '', 'page#home', 'home');
$router->map('POST', '/login', 'utente#login', 'login');
$router->map('GET', '/logout', 'utente#logout', 'logout');
$router->map('GET', '/contatti', 'page#contatti', 'contatti');
$router->map('GET', '/articoli/[i:giornata]?', 'articolo#index', 'articoli');
$router->map('GET', '/articoli/new', 'articolo#build', 'articolo_new');
$router->map('POST','/articoli', 'articolo#create', 'articolo_create');
$router->map('GET', '/articoli/[i:id]/[edit:action]', 'articolo#edit', 'articolo_edit');
$router->map('POST','/articoli/[i:id]', 'articolo#update', 'articolo_update');
$router->map('GET', '/articoli/[i:id]/[delete:action]', 'articolo#delete', 'articolo_delete');
//$router->map('GET', '/squadre', 'squadra#index', 'squadre');
$router->map('GET', '/squadre[.xml|.json:format]?', 'squadra#index', 'squadre');
$router->map('GET', '/squadre/[i:id]', 'squadra#show', 'squadra_show');
$router->map('GET', '/giocatori/[i:id][*:title]?', 'giocatore#show', 'giocatore_show');
$router->map('GET', '/giocatori/liberi', 'giocatore#free', 'giocatori_liberi');
$router->map('GET', '/clubs', 'club#index', 'club_index');
$router->map('GET', '/clubs/[i:id]', 'club#show', 'club_show');
$router->map('GET', '/formazione', 'formazione#edit', 'formazione_edit');
$router->map('GET', '/formazione/new', 'formazione#build', 'formazione_new');
$router->map('GET', '/formazione/insert_old', 'formazione#insertOld', 'formazione_insert');
$router->map('GET', '/probabili_formazioni', 'formazione#edit', 'probabili_formazioni');
$router->map('GET', '/trasferimenti/[i:idUtente]?', 'trasferimento#index', 'trasferimento_index');
//$router->map('GET', '/trasferimenti/[i:id]', 'trasferimento#index', 'trasferimento_show');
$router->map('GET', '/download', 'trasferimento#show', 'download');
$router->map('GET', '/classifica', 'punteggio#index', 'classifica');
$router->map('GET', '/dettaglio_giornata/[i:idGiornata]/[i:idUtente]', 'punteggio#show', 'punteggio_show');
$router->map('GET', '/crea_squadra', 'squadra#build', 'squadra_new');
$router->map('GET', '/trasferimento/new', 'trasferimento#build', 'trasferimento_new');
$router->map('GET', '/impostazioni', 'lega#edit', 'impostazioni');
$router->map('POST', '/impostazioni', 'lega#update', 'impostazioni_update');
$router->map('GET', '/feed', 'evento#feed', 'feed');


$router->map('GET', '/penalità', 'punteggio#penalità', 'penalità');
$router->map('GET', '/newsletter', 'trasferimento#build', 'newsletter');
$router->map('GET', '/lancia_script', 'squadra#build', 'lancia_script');
$router->map('GET', '/giornata', 'squadra#build', 'giornata');
$router->map('GET', '/gestione_database', 'squadra#new', 'gestione_database');

$router->map('GET', '/', 'punteggio#show', 'area_amministrativa');
$router->map('GET', '/', 'clubs#index', 'clubsA');
$router->map('GET', '/', 'clubs#index', 'altro');


?>