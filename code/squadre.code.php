<?php
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'punteggio.db.inc.php');

$squadreAppo = Utente::getByField('idLega',$_SESSION['legaView']);
$classifica = Punteggio::getClassificaByGiornata($_SESSION['legaView'],GIORNATA);
$squadre = array();
$i = 0;
if($classifica != NULL) {
	foreach($classifica as $key => $val) {
		$squadre[$key] = $squadreAppo[$key];
		$squadre[$key]->giornateVinte = $val->giornateVinte;
	}
}else
	$squadre = $squadreAppo;
$contentTpl->assign('elencoSquadre',$squadre);
$contentTpl->assign('ultimaGiornata',Punteggio::getGiornateWithPunt());
?>
