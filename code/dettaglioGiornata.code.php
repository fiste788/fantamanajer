<?php 
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'formazione.db.inc.php');
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');

$utenteObj = new utente();
$formazioneObj = new formazione();
$punteggioObj = new punteggio();
$giocatoreObj = new giocatore();

$squadra = NULL;
$giornata = NULL;
if(isset($_GET['squadra']))
	$squadra = $_GET['squadra'];
if(isset($_GET['giornata']))
	$giornata = $_GET['giornata'];
if(isset($_POST['squadra']))
	$squadra = $_POST['squadra'];
if(isset($_POST['giornata']))
	$giornata = $_POST['giornata'];
	
$contenttpl->assign('getsquadra',$squadra);
$contenttpl->assign('getgiornata',$giornata);
$giornate = $punteggioObj->getGiornateWithPunt();
	
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

$contenttpl->assign('squadradett',$utenteObj->getSquadraById($squadra));
$contenttpl->assign('squadre',$utenteObj->getElencoSquadreByLega($_SESSION['legaView']));

$penalità = $punteggioObj->getPenalitàBySquadraAndGiornata($squadra,$giornata);
if($penalità != FALSE)
	$contenttpl->assign('penalità',$penalità);
if($squadra != NULL && $giornata != NULL && $squadra > 0 && $giornata > 0 && $giornata <= $giornate)
{	
	if($formazioneObj->getFormazioneBySquadraAndGiornata($squadra,$giornata) != FALSE)
	{
		$contenttpl->assign('somma',$punteggioObj->getPunteggi($squadra,$giornata));
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

$operationtpl->assign('penalità',$penalità);
$operationtpl->assign('giornprec',$giornprec);
$operationtpl->assign('giornsucc',$giornsucc);
$operationtpl->assign('giornate',$giornate);
$operationtpl->assign('getsquadra',$squadra);
$operationtpl->assign('getgiornata',$giornata);
?>