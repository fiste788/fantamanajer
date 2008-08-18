<?php 
require (INCDIR.'giocatore.inc.php');
require (INCDIR.'squadra.inc.php');

$giocatoreObj = new giocatore();
$squadraObj = new squadra();

$id = $_GET['id'];

$dettaglio = $giocatoreObj->getGiocatoreById($id);

$contenttpl->assign('squadra',$squadraObj->getSquadraById($dettaglio[0]['IdSquadra']));
$contenttpl->assign('dettaglioGioc',$dettaglio);
?>
 