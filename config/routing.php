<?php

$router = new AltoRouter();
$router->setBasePath((LOCAL) ? '/fantamanajer-new' : '');

// mapping routes
$router->map('GET', '/[dummy]?', array('controller'=>'pages','action'=>'home'), 'home');
$router->map('GET', '/login', array('controller'=>'users','action'=>'login'), 'login');
$router->map('POST', '/login', array('controller'=>'users','action'=>'login'), 'login_post');
$router->map('GET', '/logout', array('controller'=>'users','action'=>'logout'), 'logout');
$router->map('GET', '/users', array('controller'=>'users','action'=>'edit'), 'users_show');
$router->map('POST','/users', array('controller'=>'users','action'=>'update'), 'users_update');
$router->map('GET', '/about', array('controller'=>'pages','action'=>'about'), 'about');
$router->map('GET', '/teams/[i:team_id]/articles', array('controller'=>'articles','action'=>'team_index'), 'team_articles');
$router->map('GET', '/articles', array('controller'=>'articles','action'=>'index'), 'articles');
$router->map('GET', '/articles/new', array('controller'=>'articles','action'=>'build'), 'articles_new');
$router->map('POST','/articles', array('controller'=>'articles','action'=>'create'), 'articles_create');
$router->map('GET', '/articles/[i:id]/edit', array('controller'=>'articles','action'=>'edit'), 'articles_edit');
$router->map('POST','/articles/[i:id]', array('controller'=>'articles','action'=>'update'), 'articles_update');
$router->map('GET', '/articles/[i:id]/delete', array('controller'=>'articles','action'=>'delete'), 'articles_delete');
//$router->map('GET', '/squadre', 'squadra','action'=>'index', 'squadre');
$router->map('GET', '/teams[.xml|.json:format]?', array('controller'=>'teams','action'=>'index'), 'teams');
$router->map('GET', '/teams/[i:id]', array('controller'=>'teams','action'=>'show'), 'teams_show');
$router->map('GET', '/members/[i:id][*:title]?', array('controller'=>'members','action'=>'show'), 'members_show');
$router->map('GET|POST', '/members/free', array('controller'=>'members','action'=>'free'), 'members_free');
$router->map('GET', '/giocatori/update', array('controller'=>'script','action'=>'updateTabellaGiocatori'), 'giocatori_update');
$router->map('GET', '/clubs', array('controller'=>'clubs','action'=>'index'), 'clubs_index');
$router->map('GET', '/clubs/[i:id]', array('controller'=>'clubs','action'=>'show'), 'clubs_show');
$router->map('GET', '/lineups', array('controller'=>'formazione','action'=>'show'), 'lineups');
$router->map('GET', '/formazione/[i:giornata]?/[i:squadra]?', array('controller'=>'formazione','action'=>'show'), 'formazione_show');
//$router->map('GET', '/formazione', array('controller'=>'formazione','action'=>'build'), 'formazione_edit');
$router->map('POST', '/lineups', array('controller'=>'formazione','action'=>'update'), 'lineups_update');
//$router->map('GET', '/formazione/new', 'formazione','action'=>'build', 'formazione_new');
$router->map('GET', '/formazione/insert_old', array('controller'=>'formazione','action'=>'insertOld'), 'formazione_insert');
$router->map('GET', '/probabili_formazioni', array('controller'=>'club','action'=>'probabiliFormazioni'), 'probabili_formazioni');
$router->map('GET', '/probabili_formazioni[.html:format]', array('controller'=>'club','action'=>'probabiliFormazioni'), 'probabili_formazioni_ajax');
$router->map('GET|POST', '/transferts/[i:team_id]?', array('controller'=>'transferts','action'=>'index'), 'transferts_index');
$router->map('POST', '/selezione/[i:squadra]?', array('controller'=>'selezione','action'=>'update'), 'selezione_update');
//$router->map('GET', '/trasferimenti/[i:id]', 'trasferimento','action'=>'index', 'trasferimento_show');
$router->map('GET', '/ranking/[i:matchday_id]?', array('controller'=>'scores','action'=>'index'), 'ranking');
$router->map('GET', '/scores/[i:team_id]/[i:matchday_id]?', array('controller'=>'scores','action'=>'show'), 'scores_show');
$router->map('GET', '/crea_squadra', array('controller'=>'squadra','action'=>'build'), 'squadra_new');
$router->map('GET', '/trasferimento/new', array('controller'=>'trasferimento','action'=>'build'), 'trasferimento_new');
$router->map('GET', '/impostazioni', array('controller'=>'lega','action'=>'edit'), 'impostazioni');
$router->map('POST', '/impostazioni', array('controller'=>'lega','action'=>'update'), 'impostazioni_update');
$router->map('GET|POST', '/feed', array('controller'=>'evento','action'=>'index'), 'feed');
$router->map('GET', '/rss/[i:lega]?', array('controller'=>'evento','action'=>'rss'), 'rss');
$router->map('POST', '/user/upload', array('controller'=>'user','action'=>'upload'), 'upload');
$router->map('GET', '/download', array('controller'=>'page','action'=>'download'), 'download');
$router->map('POST', '/download', array('controller'=>'page','action'=>'buildDownload'), 'build_download');

$router->map('GET', '/crea_squadra', array('controller'=>'squadra','action'=>'build'), 'crea_squadra');
$router->map('POST', '/crea_squadra', array('controller'=>'squadra','action'=>'create'), 'squadra_create');
$router->map('GET', '/penalità', array('controller'=>'punteggio','action'=>'penalità'), 'penalità');
$router->map('GET', '/newsletter', array('controller'=>'trasferimento','action'=>'build'), 'newsletter');
$router->map('GET', '/lancia_script', array('controller'=>'squadra','action'=>'build'), 'lancia_script');
$router->map('GET', '/weekly_script', array('controller'=>'script','action'=>'weeklyScript'), 'weekly_script');
$router->map('GET', '/weekly_script/send_mails', array('controller'=>'script','action'=>'sendWeeklyMails'), 'weekly_script_mails');
$router->map('GET', '/formazione/send', array('controller'=>'script','action'=>'sendMails'), 'formazione_send');
$router->map('GET', '/giornata', array('controller'=>'squadra','action'=>'build'), 'giornata');
$router->map('GET', '/gestione_database', array('controller'=>'squadra','action'=>'new'), 'gestione_database');

$router->map('GET', '/trasferimenti/do', array('controller'=>'script','action'=>'doTransfert'), 'do_transfert');
$router->map('GET', '/minify', array('controller'=>'script','action'=>'minify'), 'minify');
$router->map('GET', '/fixplayer', array('controller'=>'script','action'=>'fixPlayerPhoto'), 'fix_player_photo');
$router->map('GET', '/updategiornata', array('controller'=>'script','action'=>'updateGiornata'), 'update_giornata');
$router->map('GET', '/updatecalendario', array('controller'=>'script','action'=>'updateCalendario'), 'update_calendario');


$router->map('GET', '/', array('controller'=>'punteggio','action'=>'show'), 'area_amministrativa');
$router->map('GET', '/', array('controller'=>'clubs','action'=>'index'), 'clubsA');
$router->map('GET', '/', array('controller'=>'clubs','action'=>'index'), 'altro');
