<?php 
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'formazione.db.inc.php');
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');

$utenteObj = new utente();
$formazioneObj = new formazione();
$punteggioObj = new punteggio();
$giocatoreObj = new giocatore();

$filterSquadra = NULL;
$filterGiornata = NULL;
if(isset($_GET['squadra']))
	$filterSquadra = $_GET['squadra'];
if(isset($_GET['giornata']))
	$filterGiornata = $_GET['giornata'];
if(isset($_POST['squadra']))
	$filterSquadra = $_POST['squadra'];
if(isset($_POST['giornata']))
	$filterGiornata = $_POST['giornata'];

$giornate = $punteggioObj->getGiornateWithPunt();
$penalità = $punteggioObj->getPenalitàBySquadraAndGiornata($filterSquadra,$filterGiornata);

if($penalità != FALSE)
	$contentTpl->assign('penalità',$penalità);
if($filterSquadra != NULL && $filterGiornata != NULL && $filterSquadra > 0 && $giornata > 0 && $filterGiornata <= $giornate)
{
	if($formazioneObj->getFormazioneBySquadraAndGiornata($filterSquadra,$filterGiornata) != FALSE)
	{
		$formazione = $giocatoreObj->getVotiGiocatoriByGiornataAndSquadra($filterGiornata,$filterSquadra);
		$titolari = array_splice($formazione,0,11);
		$contentTpl->assign('somma',$punteggioObj->getPunteggi($filterSquadra,$filterGiornata));
		$contentTpl->assign('titolari',$titolari);
		$contentTpl->assign('panchinari',$formazione);
	}
	else
	{
		$contentTpl->assign('tirolari',FALSE);
		$contentTpl->assign('panchinari',FALSE);
		$contentTpl->assign('somma',0);
	}
}
else
	$contentTpl->assign('titolari',NULL);

if(isset($filterGiornata) && $filterGiornata -1 > 0)
{
	$idPrec = $filterGiornata -1;
	$quickLinks->prec->href = $contentTpl->linksObj->getLink('dettaglioGiornata',array('giornata'=>$idPrec,'squadra'=>$filterSquadra));
	$quickLinks->prec->title = "Giornata " . $idPrec;
}	
else
{
	$idPrec = FALSE;
	$quickLinks->prec = FALSE;
}
if(isset($filterGiornata) && $filterGiornata + 1 <= $giornate)
{
	$idSucc = $filterGiornata + 1;
	$quickLinks->succ->href = $contentTpl->linksObj->getLink('dettaglioGiornata',array('giornata'=>$idSucc,'squadra'=>$filterSquadra));
	$quickLinks->succ->title = "Giornata " . $idSucc;
}	
else
{
	$idSucc = FALSE;
	$quickLinks->succ = FALSE;
}
	
$contentTpl->assign('squadra',$filterSquadra);
$contentTpl->assign('giornata',$filterGiornata);
$contentTpl->assign('squadraDett',$utenteObj->getSquadraById($filterSquadra));
$operationTpl->assign('squadre',$utenteObj->getElencoSquadreByLega($_SESSION['legaView']));
$operationTpl->assign('penalità',$penalità);
$operationTpl->assign('giornate',$giornate);
$operationTpl->assign('squadra',$filterSquadra);
$operationTpl->assign('giornata',$filterGiornata);
$layoutTpl->assign('quickLinks',$quickLinks);
?>
