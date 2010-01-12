<?php
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'punteggio.db.inc.php');

$punteggioObj = new punteggio();
$utenteObj = new utente();

$elencoSquadre = $utenteObj->getElencoSquadreByLega($_SESSION['legaView']);

$contentTpl->assign('elencosquadre',$elencoSquadre);
$contentTpl->assign('posizioni',$punteggioObj->getPosClassifica($_SESSION['legaView']));
$contentTpl->assign('ultimaGiornata',$punteggioObj->getGiornateWithPunt());

?>
