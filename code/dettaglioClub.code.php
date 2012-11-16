<?php
require_once(INCDBDIR . 'club.db.inc.php');
require_once(INCDBDIR . 'punteggio.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(VIEWDIR . 'ClubStatistiche.view.db.inc.php');
require_once(VIEWDIR . 'GiocatoreStatistiche.view.db.inc.php');

if(($dettaglioClub = ClubStatistiche::getById(Request::getInstance()->get('club'))) == FALSE)
	Request::send404();

$elencoClub = Club::getList();

$quickLinks->set('club',$elencoClub,"");
$giocatori = GiocatoreStatistiche::getByField('idClub',Request::getInstance()->get('club'));

$contentTpl->assign('giocatori',$giocatori);
$contentTpl->assign('clubDett',$dettaglioClub);
$operationTpl->assign('elencoClub',$elencoClub);

?>
