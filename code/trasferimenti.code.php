<?php 
require_once(INCDBDIR . 'trasferimento.db.inc.php');
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(VIEWDIR . 'GiocatoreStatistiche.view.db.inc.php');
require_once(INCDBDIR . 'selezione.db.inc.php');
require_once(INCDBDIR . 'punteggio.db.inc.php');
require_once(INCDBDIR . 'evento.db.inc.php');
require_once(INCDIR . 'mail.inc.php');

$filterId = $request->has('id') ? $request->get('id') : $_SESSION['idUtente'];
$trasferimenti = Trasferimento::getByField('idUtente',$filterId);
foreach($trasferimenti as $key=>$val) {
	$val->getGiocatoreOld();
	$val->getGiocatoreNew();
}
$playerFree = Giocatore::getFreePlayer(NULL,$_SESSION['legaView']);

$trasferiti = Giocatore::getGiocatoriInattiviByIdUtente($_SESSION['idUtente']);
$selezione = Selezione::getByField('idUtente',$_SESSION['idUtente']);

if($request->has('acquista'))
	$selezione->setIdGiocatoreNew($request->get('acquista'));

$contentTpl->assign('giocatoriSquadra',GiocatoreStatistiche::getByField('idUtente',$filterId));
$contentTpl->assign('freePlayer',$playerFree);
$contentTpl->assign('filterId',$filterId);
$contentTpl->assign('trasferimenti',$trasferimenti);
$contentTpl->assign('selezione',$selezione);
$operationTpl->assign('filterId',$filterId);
$operationTpl->assign('elencoSquadre',Utente::getByField('idLega',$_SESSION['legaView']));
?>
