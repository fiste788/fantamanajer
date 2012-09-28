<?php
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'punteggio.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(VIEWDIR . 'SquadraStatistiche.view.db.inc.php');
require_once(VIEWDIR . 'GiocatoreStatistiche.view.db.inc.php');

if(($squadraDett =  SquadraStatistiche::getById($request->get('squadra'))) == FALSE)
	Request::send404();

$elencoSquadre = Utente::getByField('idLega',$squadraDett->idLega);
$quickLinks->set('squadra',$elencoSquadre,'');

$contentTpl->assign('giocatori',GiocatoreStatistiche::getByField('idUtente',$request->get('squadra')));
$contentTpl->assign('squadraDett',$squadraDett);
$operationTpl->assign('elencoSquadre',$elencoSquadre);
?>
