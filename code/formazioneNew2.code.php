<?php
require_once(INCDIR.'giocatore.inc.php');

$giocatoreObj = new giocatore();

$giocatori = $giocatoreObj->getGiocatoriByIdSquadra($_SESSION['idsquadra']);
$contenttpl->assign('giocatori',$giocatori);
?>
