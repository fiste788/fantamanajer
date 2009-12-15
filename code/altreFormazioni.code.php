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

$contenttpl->assign('squadra',$squadra);
$contenttpl->assign('getGiornata',$giorn);

$val = $utenteObj->getElencoSquadre();
$contenttpl->assign('elencosquadre',$val);
$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($squadra,$giorn);
$formImp = $formazioneObj->getFormazioneExistByGiornata($giorn,$_SESSION['legaView']);

if($formazione != FALSE)
{
	$panchinariAr = $formazione->elenco;
	$titolariAr = array_splice($panchinariAr,0,11);
	$contenttpl->assign('titolari',$giocatoreObj->getGiocatoriByArray($titolariAr));
	if(!empty($panchinariAr))
		$contenttpl->assign('panchinari',$giocatoreObj->getGiocatoriByArray($panchinariAr));
	else
		$contenttpl->assign('panchinari',FALSE);
}
$contenttpl->assign('formazioniImpostate',$formImp);
$contenttpl->assign('modulo',$formazione->modulo);
$contenttpl->assign('mod',explode('-',$formazione->modulo));
$contenttpl->assign('formazione',$formazione->elenco);
$contenttpl->assign('cap',$formazione->cap);
?>
