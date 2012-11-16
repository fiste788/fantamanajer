<?php
require_once(INCDBDIR . 'trasferimento.db.inc.php');
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(INCDBDIR . 'lega.db.inc.php');
require_once(VIEWDIR . 'GiocatoreStatistiche.view.db.inc.php');

$filterSquadra = (Request::getInstance()->has('squadra')) ? Request::getInstance()->get('squadra') : NULL;
$filterLega = (Request::getInstance()->has('lega')) ? Request::getInstance()->get('lega') : NULL;
if($_SESSION['usertype'] == 'admin')
	$filterLega = $_SESSION['idLega'];

	
$ruoli = array('P'=>'Portiere','D'=>'Difensori','C'=>'Centrocampisti','A'=>'Attaccanti');

if(!is_null($filterSquadra)) {
	$trasferimenti = Trasferimento::getByField('idUtente',$filterSquadra);
	$numTrasferimenti = count($trasferimenti);

	$contentTpl->assign('trasferimenti',$trasferimenti);
	$contentTpl->assign('numTrasferimenti',$numTrasferimenti);
	$contentTpl->assign('giocatoriSquadra',GiocatoreStatistiche::getByField('idUtente',$filterSquadra));
	$contentTpl->assign('freePlayer',Giocatore::getGiocatoriNotSquadra($filterSquadra,$filterLega));
}
$utenti = array();
if($filterLega != NULL)
	$utenti = Utente::getByField('idLega',$filterLega);

$operationTpl->assign('elencoSquadre',$utenti);
$contentTpl->assign('elencoSquadre',$utenti);

$contentTpl->assign('ruoli',$ruoli);
$contentTpl->assign('squadra',$filterSquadra);
$contentTpl->assign('lega',$filterLega);
$operationTpl->assign('squadra',$filterSquadra);
$operationTpl->assign('elencoLeghe',Lega::getList());
$operationTpl->assign('lega',$filterLega);
?>
