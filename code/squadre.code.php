<?php
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'punteggio.db.inc.php');

$punteggioObj = new punteggio();
$utenteObj = new utente();

$squadra = NULL;
if(isset($_GET['squadra']))
	$squadra = $_GET['squadra'];

$elencoSquadre = $utenteObj->getElencoSquadreByLega($_SESSION['legaView']);
foreach ($elencoSquadre as $key => $val)
	$elencoSquadre[$key]['giornateVinte'] = $punteggioObj->getGiornateVinte($val['idUtente']);

$contenttpl->assign('squadra',$squadra);
$contenttpl->assign('elencosquadre',$elencoSquadre);
$contenttpl->assign('posizioni',$punteggioObj->getPosClassifica($_SESSION['legaView']));
$contenttpl->assign('ultimaGiornata',$punteggioObj->getGiornateWithPunt());

?>
