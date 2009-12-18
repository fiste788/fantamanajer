<?php 
require_once(INCDIR . "utente.db.inc.php");
require_once(INCDIR . "formazione.db.inc.php");
require_once(INCDIR . "giocatore.db.inc.php");

$utenteObj = new utente();
$formazioneObj = new formazione();
$giocatoreObj = new giocatore();

$squadra = $_SESSION['idSquadra'];
$giorn = GIORNATA;
if(isset($_GET['squadra']))
	$squadra = $_GET['squadra'];
if(isset($_GET['giorn']))
  $giorn = $_GET['giorn'];
if(isset($_POST['squadra']))
	$squadra = $_POST['squadra'];
if(isset($_POST['giorn']))
	$giorn = $_POST['giorn'];

$contentTpl->assign('squadra',$squadra);
$contentTpl->assign('getGiornata',$giorn);

$val = $utenteObj->getElencoSquadre();
$contentTpl->assign('elencosquadre',$val);
$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($squadra,$giorn);
$formImp = $formazioneObj->getFormazioneExistByGiornata($giorn,$_SESSION['legaView']);

if($formazione != FALSE)
{
	$panchinariAr = $formazione->elenco;
	$titolariAr = array_splice($panchinariAr,0,11);
	$contentTpl->assign('titolari',$giocatoreObj->getGiocatoriByArray($titolariAr));
	if(!empty($panchinariAr))
		$contentTpl->assign('panchinari',$giocatoreObj->getGiocatoriByArray($panchinariAr));
	else
		$contentTpl->assign('panchinari',FALSE);
}
$contentTpl->assign('formazioniImpostate',$formImp);
$contentTpl->assign('modulo',$formazione->modulo);
$contentTpl->assign('mod',explode('-',$formazione->modulo));
$contentTpl->assign('formazione',$formazione->elenco);
$contentTpl->assign('cap',$formazione->cap);
?>
