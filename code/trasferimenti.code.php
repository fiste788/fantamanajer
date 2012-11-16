<?php 
require_once(INCDBDIR . 'trasferimento.db.inc.php');
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(VIEWDIR . 'GiocatoreStatistiche.view.db.inc.php');
require_once(INCDBDIR . 'selezione.db.inc.php');
require_once(INCDBDIR . 'punteggio.db.inc.php');
require_once(INCDBDIR . 'evento.db.inc.php');
require_once(INCDIR . 'mail.inc.php');

$filterId = Request::getInstance()->has('id') ? Request::getInstance()->get('id') : $_SESSION['idUtente'];
$trasferimentiAppo = Trasferimento::getByField('idUtente',$filterId);

if(!is_array($trasferimentiAppo) && !is_null($trasferimentiAppo))
	$trasferimenti[] = $trasferimentiAppo;
else
	$trasferimenti = $trasferimentiAppo;


if($trasferimenti != FALSE) {
	foreach($trasferimenti as $key=>$val) {
		$val->getGiocatoreOld();
		$val->getGiocatoreNew();
	}
}
$playerFree = Giocatore::getFreePlayer(NULL,$_SESSION['legaView']);

$trasferiti = Giocatore::getGiocatoriInattiviByIdUtente($_SESSION['idUtente']);
$selezione = Selezione::getByField('idUtente',$_SESSION['idUtente']);
if(empty($selezione))
	$selezione = new Selezione();
if(Request::getInstance()->has('acquista'))
	$selezione->setIdGiocatoreNew(Request::getInstance()->get('acquista'));

$contentTpl->assign('giocatoriSquadra',GiocatoreStatistiche::getByField('idUtente',$filterId));
$contentTpl->assign('freePlayer',$playerFree);
$contentTpl->assign('filterId',$filterId);
$contentTpl->assign('trasferimenti',$trasferimenti);
$contentTpl->assign('selezione',$selezione);
$operationTpl->assign('filterId',$filterId);
$operationTpl->assign('elencoSquadre',Utente::getByField('idLega',$_SESSION['legaView']));
?>
