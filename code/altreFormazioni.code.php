<?php 
require_once(INCDIR . "utente.db.inc.php");
require_once(INCDIR . "formazione.db.inc.php");
require_once(INCDIR . "giocatore.db.inc.php");

$utenteObj = new utente();
$formazioneObj = new formazione();
$giocatoreObj = new giocatore();

$filterSquadra = $_SESSION['idSquadra'];
$filterGiornata = GIORNATA;
if(isset($_GET['squadra']))
	$filterSquadra = $_GET['squadra'];
if(isset($_GET['giornata']))
  $filterDiorn = $_GET['giornata'];
if(isset($_POST['squadra']))
	$filterSquadra = $_POST['squadra'];
if(isset($_POST['giornata']))
	$filterGiornata = $_POST['giornata'];


$moduloAr = array('P'=>0,'D'=>0,'C'=>0,'A'=>0);
$ruo = array('P','D','C','A');
$elencoCap = array('C','VC','VVC');
$contentTpl->assign('ruo',$ruo);
$contentTpl->assign('elencoCap',$elencoCap);

$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($filterSquadra,$filterGiornata);
$formImp = $formazioneObj->getFormazioneExistByGiornata($filterGiornata,$_SESSION['legaView']);

if($formazione != FALSE)
{
	$giocatori = $giocatoreObj->getGiocatoriByArray($formazione->elenco);
	foreach($giocatori as $key=>$val)
		$giocatoriNew[$val->idGioc] = $val;
	$contentTpl->assign('giocatoriId',$giocatoriNew);
	$panchinariAr = $formazione->elenco;
	$titolariAr = array_splice($panchinariAr,0,11);
	$contentTpl->assign('titolari',$titolariAr);
	$contentTpl->assign('panchinari',$panchinariAr);
	$contentTpl->assign('modulo',explode('-',$formazione->modulo));
	$contentTpl->assign('formazione',$formazione->elenco);
	$contentTpl->assign('cap',$formazione->cap);
}
$contentTpl->assign('squadra',$filterSquadra);
$contentTpl->assign('giornata',$filterGiornata);
$operationTpl->assign('formazioniImpostate',$formImp);
$operationTpl->assign('squadra',$filterSquadra);
$operationTpl->assign('giornata',$filterGiornata);
?>
