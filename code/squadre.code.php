<?php
require_once(INCDIR.'utente.inc.php');
require_once(INCDIR.'punteggi.inc.php');

$punteggiObj = new punteggi();
$utenteObj = new utente();

$squadra = NULL;
if(isset($_GET['squadra']))
	$squadra = $_GET['squadra'];

$elencoSquadre = $utenteObj->getElencoSquadreByLega($_SESSION['legaView']);
foreach ($elencoSquadre as $key => $val)
	$elencoSquadre[$key]['giornateVinte'] = $punteggiObj->getGiornateVinte($val['idUtente']);

$contenttpl->assign('squadra',$squadra);
$contenttpl->assign('elencosquadre',$elencoSquadre);
$contenttpl->assign('posizioni',$punteggiObj->getPosClassifica($_SESSION['legaView']));
$contenttpl->assign('ultimaGiornata',$punteggiObj->getGiornateWithPunt());

?>
