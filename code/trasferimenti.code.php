<?php 
require_once(INCDBDIR . 'trasferimento.db.inc.php');
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(INCDBDIR . 'selezione.db.inc.php');
require_once(INCDBDIR . 'punteggio.db.inc.php');
require_once(INCDBDIR . 'evento.db.inc.php');
require_once(INCDIR . 'mail.inc.php');

$trasferimenti = Trasferimento::getByField('idUtente',$request->get('id'));
foreach($trasferimenti as $key=>$val) {
	$val->getGiocatoreOld();
	$val->getGiocatoreNew();
}
$playerFree = Giocatore::getFreePlayer(NULL,$_SESSION['legaView']);

$trasferiti = Giocatore::getGiocatoriTrasferiti($_SESSION['idUtente']);
//$selezione = Selezione::getSelezioneByIdSquadra($_SESSION['idUtente']);


//$contentTpl->assign('giocSquadra',Giocatore::getByField('idUtente',$request->get('id')));
$contentTpl->assign('freePlayer',$playerFree);
$contentTpl->assign('trasferimenti',$trasferimenti);
$operationTpl->assign('elencoSquadre',Utente::getByField('idLega',$_SESSION['legaView']));
?>
