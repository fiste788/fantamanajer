<?php 
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'squadra.inc.php');

$giocatoreObj = new giocatore();
$squadraObj = new squadra();

$id = $_GET['id'];

$dettaglio = $giocatoreObj->getGiocatoreById($id);

$contenttpl->assign('squadra',$squadraObj->getSquadraById($dettaglio[0]['IdSquadra']));
$contenttpl->assign('dettaglioGioc',$dettaglio);
?>
 
