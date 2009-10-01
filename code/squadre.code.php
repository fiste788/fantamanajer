<?php
require_once(INCDIR.'utente.inc.php');
require_once(INCDIR.'punteggi.inc.php');

$punteggiObj = new punteggi();
$utenteObj = new utente();

$squadra = NULL;
if(isset($_GET['squadra']))
	$squadra = $_GET['squadra'];
$contenttpl->assign('squadra',$squadra);

$classifica = $punteggiObj->getClassifica($_SESSION['legaView']);
$contenttpl->assign('classifica',$classifica);
$contenttpl->assign('posizioni',$punteggiObj->getPosClassifica($_SESSION['legaView']));
$elencoSquadre = $utenteObj->getElencoSquadreByLega($_SESSION['legaView']);
$contenttpl->assign('elencosquadre',$elencoSquadre);

?>
