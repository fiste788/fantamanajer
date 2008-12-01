S<?php 
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'utente.inc.php');

$giocatoreObj = new giocatore();
$utenteObj = new utente();

$id = $_GET['id'];

$dettaglio = $giocatoreObj->getGiocatoreByIdWithStats($id);

$contenttpl->assign('squadra',$utenteObj->getSquadraById($dettaglio[0]['idUtente']));
$contenttpl->assign('dettaglioGioc',$dettaglio);
?>
