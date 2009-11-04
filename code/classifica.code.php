<?php 
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'utente.db.inc.php');

$punteggioObj = new punteggio();
$utenteObj = new utente();

$giornata = GIORNATA;
if(isset($_POST['giornata']))
	$giornata = $_POST['giornata'];

$classificaDett = $punteggioObj->getAllPunteggiByGiornata($giornata,$_SESSION['legaView']);
$squadre = $utenteObj->getElencoSquadreByLega($_SESSION['legaView']);

foreach($classificaDett as $key => $val)
	$classificaDett[$key] = array_reverse($classificaDett[$key],TRUE); 
	
$giornate = $punteggioObj->getGiornateWithPunt();
$contenttpl->assign('giornate',$giornate);
$contenttpl->assign('classificaDett',$classificaDett);
$contenttpl->assign('penalità',$punteggioObj->getPenalitàByLega($_SESSION['legaView']));
$contenttpl->assign('squadre',$squadre);

$operationtpl->assign('getGiornata',$giornata);
$operationtpl->assign('giornate',$giornate);
?>
