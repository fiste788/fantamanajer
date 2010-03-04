<?php 
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'utente.db.inc.php');

$filterGiornata = GIORNATA;
if(isset($_POST['giornata']))
	$filterGiornata = $_POST['giornata'];

$classificaDett = Punteggio::getAllPunteggiByGiornata($filterGiornata,$_SESSION['legaView']);
$squadre = Utente::getElencoSquadreByLega($_SESSION['legaView']);

foreach($classificaDett as $key => $val)
	$classificaDett[$key] = array_reverse($classificaDett[$key],TRUE); 
	
$giornate = Punteggio::getGiornateWithPunt();
$contentTpl->assign('giornate',$giornate);
$contentTpl->assign('classificaDett',$classificaDett);
$contentTpl->assign('penalità',Punteggio::getPenalitàByLega($_SESSION['legaView']));
$contentTpl->assign('squadre',$squadre);
$contentTpl->assign('posizioni',Punteggio::getPosClassificaGiornata($_SESSION['legaView']));

$operationTpl->assign('getGiornata',$filterGiornata);
$operationTpl->assign('giornate',$giornate);
?>
