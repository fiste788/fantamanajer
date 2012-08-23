<?php
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDBDIR . "formazione.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(VIEWDIR . 'GiocatoreStatistiche.view.db.inc.php');
require_once(INCDBDIR . "punteggio.db.inc.php");

$filterUtente = $request->has('utente') ? $request->get('utente') : $_SESSION['idUtente'];
$filterGiornata = $request->has('giornata') ? $request->get('giornata') : GIORNATA;

$formazione = Formazione::getFormazioneBySquadraAndGiornata($filterUtente,$filterGiornata);
$formazioniPresenti = Formazione::getFormazioneByGiornataAndLega($filterGiornata,$_SESSION['legaView']);

$i = 0;
while($formazione == FALSE && $i < GIORNATA) {
	$formazione = Formazione::getFormazioneBySquadraAndGiornata($filterUtente,$filterGiornata - $i);
	$i ++;
}
$formazione->jolly = FALSE;

if(GIORNATA != $filterGiornata) {
	$ids = array();
	foreach($formazione->giocatori as $key=>$giocatore)
		$ids[] = $giocatore->idGiocatore;
	$giocatori = GiocatoreStatistiche::getByIds($ids);
} else
	$giocatori = GiocatoreStatistiche::getByField('idUtente',$filterUtente);

for($i = 1; $i <= GIORNATA; $i++)
	$giornate[$i] = $i;
$quickLinks->set('giornata',$giornate,'Giornata ');
$modulo = explode('-',$formazione->modulo);
$contentTpl->assign('formazione',$formazione);
$contentTpl->assign('giocatori',$giocatori);
$contentTpl->assign('modulo',$modulo);
$contentTpl->assign('usedJolly',Formazione::usedJolly($filterUtente));
$contentTpl->assign('squadra',$filterUtente);
$contentTpl->assign('giornata',$filterGiornata);
$operationTpl->assign('squadre',Utente::getByField('idLega',$_SESSION['legaView']));
$operationTpl->assign('giornata',$filterGiornata);
$operationTpl->assign('squadra',$filterUtente);
$operationTpl->assign('formazioniPresenti',$formazioniPresenti);
?>
