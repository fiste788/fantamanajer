<?php 
require_once(INCDIR.'punteggi.inc.php');
require_once(INCDIR.'utente.inc.php');

$punteggiObj = new punteggi();
$utenteObj = new utente();

$giornata = GIORNATA;
if(isset($_POST['giorn']))
	$giornata = $_POST['giorn'];

$classificaDett = $punteggiObj->getAllPunteggiByGiornata($giornata);
$squadre = $utenteObj->getElencoSquadre();

foreach($classificaDett as $key=>$val)
	$classificaDett[$key] = array_reverse($classificaDett[$key],TRUE); 
	
$contenttpl->assign('giornate',$punteggiObj->getGiornateWithPunt());
$contenttpl->assign('classificaDett',$classificaDett);
$contenttpl->assign('squadre',$squadre);
$contenttpl->assign('getGiornata',$giornata);
?>
