<?php 
require (INCDIR.'punteggi.inc.php');
require (INCDIR.'squadra.inc.php');

$punteggiObj = new punteggi();
$squadraObj = new squadra();

$giornata = GIORNATA;
if(isset($_POST['giorn']))
	$giornata = $_POST['giorn'];

$classificaDett = $punteggiObj->getAllPunteggiByGiornata($giornata);
$squadre = $squadraObj->getElencoSquadre();

foreach($classificaDett as $key=>$val)
	$classificaDett[$key] = array_reverse($classificaDett[$key],TRUE); 
	
$contenttpl->assign('giornate',$punteggiObj->getGiornateWithPunt());
$contenttpl->assign('classificaDett',$classificaDett);
$contenttpl->assign('squadre',$squadre);
$contenttpl->assign('getGiornata',$giornata);
?>
