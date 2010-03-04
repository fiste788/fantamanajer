<?php
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'punteggio.db.inc.php');

$elencoSquadre = Utente::getElencoSquadreByLega($_SESSION['legaView']);

$contentTpl->assign('elencosquadre',$elencoSquadre);
$contentTpl->assign('posizioni',Punteggio::getPosClassifica($_SESSION['legaView']));
$contentTpl->assign('ultimaGiornata',Punteggio::getGiornateWithPunt());

?>
