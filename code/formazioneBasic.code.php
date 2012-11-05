<?php
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDBDIR . "formazione.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");
require_once(INCDBDIR . "giocatore.db.inc.php");

$filterUtente = Request::getInstance()->has('utente') ? Request::getInstance()->get('utente') : $_SESSION['idUtente'];
$filterGiornata = Request::getInstance()->has('giornata') ? Request::getInstance()->get('giornata') : GIORNATA;
$filterModulo = Request::getInstance()->has('modulo') ? Request::getInstance()->get('modulo') : NULL;

$ruoliKey = array('P','D','C','A');
$ruo = array('P'=>'Portiere','D'=>'Difensori','C'=>'Centrocampisti','A'=>'Attaccanti');
$elencocap = array('C','VC','VVC');

$formazioniPresenti = Formazione::getFormazioneByGiornataAndLega($filterGiornata,$_SESSION['legaView']);
$formazione = Formazione::getLastFormazione($filterUtente,$filterGiornata);
if($formazione != FALSE) {
	if(is_null($filterModulo))
		$filterModulo = $formazione->modulo;
	if($formazione->idGiornata != GIORNATA)
		$formazione->jolly = FALSE;
} 
if(is_null($filterModulo))
	$filterModulo = '1-4-4-2';
foreach($ruoliKey as $key => $val)
	$giocatori[$val] =	Giocatore::getGiocatoriByIdSquadraAndRuolo($_SESSION['idUtente'],$val);
		
$contentTpl->assign('formazione',$formazione);
$contentTpl->assign('giocatori',$giocatori);
$contentTpl->assign('squadra',$filterUtente);
$contentTpl->assign('mod',$filterModulo);
$contentTpl->assign('modulo',explode('-',$filterModulo));
$contentTpl->assign('ruo',$ruo);
$contentTpl->assign('ruoliKey',$ruoliKey);
$contentTpl->assign('elencocap',$elencocap);
$operationTpl->assign('squadra',$filterUtente);
$operationTpl->assign('mod',$filterModulo);
$operationTpl->assign('modulo',explode($filterModulo,"-"));
?>
