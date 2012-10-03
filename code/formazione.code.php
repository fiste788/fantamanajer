<?php
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDBDIR . "formazione.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(VIEWDIR . 'GiocatoreStatistiche.view.db.inc.php');
require_once(INCDBDIR . "punteggio.db.inc.php");

$filterUtente = Request::getInstance()->has('utente') ? Request::getInstance()->get('utente') : $_SESSION['idUtente'];
$filterGiornata = Request::getInstance()->has('giornata') ? Request::getInstance()->get('giornata') : GIORNATA;

$formazione = Formazione::getFormazioneBySquadraAndGiornata($filterUtente,$filterGiornata);
$formazioniPresenti = Formazione::getFormazioneByGiornataAndLega($filterGiornata,$_SESSION['legaView']);

$formazione = Formazione::getLastFormazione($filterUtente, $filterGiornata);
if($formazione->getIdGiornata() != $filterGiornata)
    $formazione->setJolly (FALSE);

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
$modulo = ($formazione != FALSE) ? explode('-',$formazione->modulo) : NULL;
if($formazione != FALSE)
	$contentTpl->assign('formazione',$formazione);

foreach ($formazione->giocatori as $key => $schieramento)
    if($key < 11)
        $titolari[] = $schieramento->idGiocatore;
    else
        $panchinari[] = $schieramento->idGiocatore;
$contentTpl->assign('titolari', $titolari);
$contentTpl->assign('panchinari', $panchinari);
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
