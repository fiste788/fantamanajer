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
	$contenttpl->assign('penalità',$penalità);
if($filterSquadra != NULL && $filterGiornata != NULL && $filterSquadra > 0 && $giornata > 0 && $filterGiornata <= $giornate)
{	
	if($formazioneObj->getFormazioneBySquadraAndGiornata($filterSquadra,$filterGiornata) != FALSE)
	{
		$contenttpl->assign('somma',$punteggioObj->getPunteggi($filterSquadra,$filterGiornata));
		$contenttpl->assign('formazione',$giocatoreObj->getVotiGiocatoriByGiornataAndSquadra($filterGiornata,$filterSquadra));		
	}
	else
	{
		$contenttpl->assign('formazione',FALSE);
		$contenttpl->assign('somma',0);
	}
}
else
	$contenttpl->assign('formazione',NULL);

$quickLinks = array();
if(isset($filterGiornata) && $filterGiornata -1 >= 0)
{
	$idPrec = $filterGiornata -1;
	$quickLinks['prec']['href'] = $contenttpl->linksObj->getLink('dettaglioGiornata',array('giornata'=>$idPrec,'squadra'=>$filterSquadra));
	$quickLinks['prec']['title'] = "Giornata " . $idPrec;
}	
else
{
	$idPrec = false;
	$quickLinks['prec'] = false;
}
if(isset($filterGiornata) && $filterGiornata + 1 <= $giornate)
{
	$idSucc = $filterGiornata + 1;
	$quickLinks['succ']['href'] = $contenttpl->linksObj->getLink('dettaglioGiornata',array('giornata'=>$idSucc,'squadra'=>$filterSquadra));
	$quickLinks['succ']['title'] = "Giornata " . $idSucc;
}	
else
{
	$idSucc = false;
	$quickLinks['succ'] = false;
}
	
$contenttpl->assign('idSquadra',$filterSquadra);
$contenttpl->assign('idGiornata',$filterGiornata);
$contenttpl->assign('squadraDett',$utenteObj->getSquadraById($filterSquadra));
$operationtpl->assign('squadre',$utenteObj->getElencoSquadreByLega($_SESSION['legaView']));
$operationtpl->assign('penalità',$penalità);
$operationtpl->assign('giornPrec',$idPrec);
$operationtpl->assign('giornSucc',$idSucc);
$operationtpl->assign('giornate',$giornate);
$operationtpl->assign('idSquadra',$filterSquadra);
$operationtpl->assign('idGiornata',$filterGiornata);
$layouttpl->assign('quickLinks',$quickLinks);
?>