<?php 
require_once(INCDIR.'punteggi.inc.php');
require_once(INCDIR.'utente.inc.php');

$punteggiObj = new punteggi();
$utenteObj = new utente();

$giornata = GIORNATA;
if(isset($_POST['giorn']))
	$giornata = $_POST['giorn'];

$classificaDett = $punteggiObj->getAllPunteggiByGiornata($giornata,$_SESSION['legaView']);
$squadre = $utenteObj->getElencoSquadreByLega($_SESSION['legaView']);

foreach($classificaDett as $key => $val)
	$classificaDett[$key] = array_reverse($classificaDett[$key],TRUE); 
	
$giornate = $punteggiObj->getGiornateWithPunt();
$contenttpl->assign('giornate',$giornate);
$contenttpl->assign('classificaDett',$classificaDett);
$contenttpl->assign('penalità',$punteggiObj->getPenalitàByLega($_SESSION['legaView']));
$contenttpl->assign('squadre',$squadre);

$operationtpl->assign('getGiornata',$giornata);
$operationtpl->assign('giornate',$giornate);
?>
