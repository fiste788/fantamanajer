<?php 
require_once(INCDIR.'utente.inc.php');
require_once(INCDIR.'formazione.inc.php');
require_once(INCDIR.'punteggi.inc.php');
require_once(INCDIR.'giocatore.inc.php');

$punteggiObj = new punteggi();
$utenteObj = new utente();
$formazioneObj = new formazione();
$giocatoreObj = new giocatore();

$squadra = NULL;
$giornata = NULL;
if(isset($_GET['squad']))
	$squadra = $_GET['squad'];
if(isset($_GET['giorn']))
	$giornata = $_GET['giorn'];
if(isset($_POST['squad']))
	$squadra = $_POST['squad'];
if(isset($_POST['giorn']))
	$giornata = $_POST['giorn'];
	
$contenttpl->assign('getsquadra',$squadra);
$contenttpl->assign('getgiornata',$giornata);
$giornate = $punteggiObj->getGiornateWithPunt();
	
if(isset($giornata) && $giornata -1 >= 0)
	$giornprec = $giornata -1;	
else
	$giornprec = FALSE;
if(isset($giornata) && $giornata + 1 <= $giornate)
	$giornsucc = $giornata + 1;	
else
	$giornsucc = FALSE;

if($squadra == NULL)
	$giornprec=$giornsucc=FALSE;

$contenttpl->assign('giornprec',$giornprec);
$contenttpl->assign('giornsucc',$giornsucc);

$contenttpl->assign('squadradett',$utenteObj->getSquadraById($squadra));
$contenttpl->assign('squadre',$utenteObj->getElencoSquadreByLega($_SESSION['legaView']));


$contenttpl->assign('giornate',$giornate);
$penalità = $punteggiObj->getPenalitàBySquadraAndGiornata($squadra,$giornata);
if($penalità != FALSE)
	$contenttpl->assign('penalità',$penalità);
if($squadra != NULL && $giornata != NULL && $squadra > 0 && $giornata > 0 && $giornata <= $giornate)
{	
	if($formazioneObj->getFormazioneBySquadraAndGiornata($squadra,$giornata) != FALSE)
	{
		$contenttpl->assign('somma',$punteggiObj->getPunteggi($squadra,$giornata));
		$contenttpl->assign('formazione',$giocatoreObj->getVotiGiocatoriByGiornataAndSquadra($giornata,$squadra));		
	}
	else
	{
		$contenttpl->assign('formazione',FALSE);
		$contenttpl->assign('somma',0);
	}
}
else
	$contenttpl->assign('formazione',NULL);
?>
