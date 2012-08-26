<?php
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDBDIR . "formazione.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");
require_once(INCDBDIR . "giocatore.db.inc.php");

$filterUtente = $request->has('utente') ? $request->get('utente') : $_SESSION['idUtente'];
$filterGiornata = $request->has('giornata') ? $request->get('giornata') : GIORNATA;
$filterModulo = $request->has('modulo') ? $request->get('modulo') : '1-4-4-2';

$ruoliKey = array('P','D','C','A');
$ruo = array('P'=>'Portiere','D'=>'Difensori','C'=>'Centrocampisti','A'=>'Attaccanti');
$elencocap = array('C','VC','VVC');

$formazione = Formazione::getFormazioneBySquadraAndGiornata($filterUtente,$filterGiornata);
$formazioniPresenti = Formazione::getFormazioneByGiornataAndLega($filterGiornata,$_SESSION['legaView']);
$firePHP->log($formazione);
$i = 0;
while($formazione == FALSE && $i < GIORNATA) {
	$formazione = Formazione::getFormazioneBySquadraAndGiornata($filterUtente,$filterGiornata - $i);
	$i ++;
	if($formazione != FALSE)
		$formazione->jolly = FALSE;
}

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
