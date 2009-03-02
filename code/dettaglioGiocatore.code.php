<?php 
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'utente.inc.php');

$giocatoreObj = new giocatore();
$utenteObj = new utente();

$id = $_GET['id'];
$dettaglio = $giocatoreObj->getGiocatoreByIdWithStats($id);
$pathfoto = 'foto/' . $dettaglio[0]['idGioc'] . '.jpg';

if(!file_exists(IMGDIR . $pathfoto))
	$pathfoto = 'foto/nophoto.jpg';

$contenttpl->assign('squadra',$utenteObj->getSquadraById($dettaglio[0]['idUtente']));
$contenttpl->assign('dettaglioGioc',$dettaglio);
$contenttpl->assign('pathfoto',IMGSURL . $pathfoto);
?>
