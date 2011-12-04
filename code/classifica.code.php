<?php 
require_once(INCDBDIR . 'punteggio.db.inc.php');
require_once(INCDBDIR . 'utente.db.inc.php');

$filterGiornata = ($request->has('giornata')) ? $request->get('giornata') : GIORNATA;

$classificaDett = Punteggio::getAllPunteggiByGiornata($filterGiornata,$_SESSION['legaView']);
$squadre = Utente::getByField('idLega',$_SESSION['legaView']);
$firePHP->log($classificaDett);

/*foreach($classificaDett as $key => $val)
	$classificaDett[$key] = array_reverse($classificaDett[$key],TRUE); */
	
$firePHP->log($classificaDett);
$giornate = Punteggio::getGiornateWithPunt();
$contentTpl->assign('giornate',$giornate);
$contentTpl->assign('classificaDett',$classificaDett);
$contentTpl->assign('penalità',Punteggio::getPenalitàByLega($_SESSION['legaView']));
$contentTpl->assign('squadre',$squadre);
$contentTpl->assign('posizioni',Punteggio::getPosClassificaGiornata($_SESSION['legaView']));

$operationTpl->assign('getGiornata',$filterGiornata);
$operationTpl->assign('giornate',$giornate);
?>
