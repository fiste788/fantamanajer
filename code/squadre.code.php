<?php
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'punteggio.db.inc.php');

$squadre = Utente::getByField('idLega',$_SESSION['legaView']);
$classifica = Punteggio::getClassificaByGiornata($_SESSION['legaView'],GIORNATA);
$pos = array();
$i = 0;
foreach($classifica as $key => $val) {
	$pos[$val->idUtente] = $i++;
	$squadre[$val->idUtente]->giornateVinte = $val->giornateVinte;
}
$contentTpl->assign('elencoSquadre',$squadre);
$contentTpl->assign('posizioni',$pos);
$contentTpl->assign('ultimaGiornata',Punteggio::getGiornateWithPunt());
?>
