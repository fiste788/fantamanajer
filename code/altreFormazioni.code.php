<?php 
require_once(INCDIR."utente.inc.php");
require_once(INCDIR."formazione.inc.php");
require_once(INCDIR."giocatore.inc.php");

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
$cap = array();
$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($squadra,$giorn);
$formImp = $formazioneObj->getFormazioneExistByGiornata($giorn);

if($formazione != FALSE)
{
    $panchinariAr = $formazione['elenco'];
    $titolariAr = array_splice($panchinariAr,0,11);
    foreach($formazione['cap'] as $key => $val)
   	{
   	    $pos = array_search($val,$titolariAr);
   		if($pos == 0)
   		    $chiave = "Por-" . $pos . "-cap";
   		else
   		   $chiave = "Dif-" . ($pos-1) . "-cap";
   		$cap[$chiave] = $key;
    }
	$contenttpl->assign('titolari',$giocatoreObj->getGiocatoriByArray($titolariAr));
	if(!empty($panchinariAr))
		$contenttpl->assign('panchinari',$giocatoreObj->getGiocatoriByArray($panchinariAr));
	else
		$contenttpl->assign('panchinari',FALSE);
}
	
$contenttpl->assign('formazioniImpostate',$formImp);
$contenttpl->assign('formazione',$formazione);
$contenttpl->assign('modulo',$formazione['modulo']);
$contenttpl->assign('mod',explode('-',$formazione['modulo']));
$contenttpl->assign('formazione',$formazione['elenco']);
$contenttpl->assign('cap',$cap);
?>