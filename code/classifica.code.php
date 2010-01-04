<?php 
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'utente.db.inc.php');

$punteggioObj = new punteggio();
$utenteObj = new utente();
$filterGiornata = GIORNATA;
if(isset($_POST['giornata']))
	$filterGiornata = $_POST['giornata'];

$classificaDett = $punteggioObj->getAllPunteggiByGiornata($filterGiornata,$_SESSION['legaView']);
$squadre = $utenteObj->getElencoSquadreByLega($_SESSION['legaView']);

foreach($classificaDett as $key => $val)
	$classificaDett[$key] = array_reverse($classificaDett[$key],TRUE); 
	
$giornate = $punteggioObj->getGiornateWithPunt();
$contentTpl->assign('giornate',$giornate);
$contentTpl->assign('classificaDett',$classificaDett);
$contentTpl->assign('penalità',$punteggioObj->getPenalitàByLega($_SESSION['legaView']));
$contentTpl->assign('squadre',$squadre);
$contentTpl->assign('posizioni',$punteggioObj->getPosClassificaGiornata($_SESSION['legaView']));

$operationTpl->assign('getGiornata',$filterGiornata);
$operationTpl->assign('giornate',$giornate);
?>
