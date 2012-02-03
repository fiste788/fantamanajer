<?php
require_once(INCDBDIR . 'club.db.inc.php');
require_once(INCDBDIR . 'punteggio.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(VIEWDIR . 'ClubStatistiche.view.db.inc.php');
require_once(VIEWDIR . 'GiocatoreStatistiche.view.db.inc.php');
require_once(INCDIR . 'mail.inc.php');
	
if(($clubdett = ClubStatistiche::getById($request->get('club'))) == FALSE)
	Request::send404();

$elencoClub = Club::getList();

$quickLinks->set('id',$elencoClub,"");
$giocatori = GiocatoreStatistiche::getByField('idClub',$request->get('club'));

$contentTpl->assign('giocatori',$giocatori);
$contentTpl->assign('clubDett',$clubdett);
$operationTpl->assign('elencoClub',$elencoClub);

?>
