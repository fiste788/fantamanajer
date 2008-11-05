<?php 
require_once(INCDIR.'utente.inc.php');
require_once(INCDIR.'formazione.inc.php');
require_once(INCDIR.'punteggi.inc.php');

$punteggiObj = new punteggi();
$utenteObj = new utente();
$formazioneObj = new formazione();

$squadra = NULL;
$giornata = NULL;
if(isset($_GET['squad']))
	$squadra = $_GET['squad'];
if(isset($_GET['giorn']))
	$giornata = $_GET['giorn'];
	
$contenttpl->assign('getsquadra',$squadra);
$contenttpl->assign('getgiornata',$giornata);
$giornate = $punteggiObj->getGiornateWithPunt();
	
if(isset($_GET['giorn']) && $_GET['giorn']-1 >=0)
	$giornprec = $_GET['giorn']-1;	
else
	$giornprec = FALSE;
if(isset($_GET['giorn']) && $_GET['giorn']+1 <= $giornate)
	$giornsucc = $_GET['giorn']+1;	
else
	$giornsucc = FALSE;

if($squadra == NULL)
	$giornprec=$giornsucc=FALSE;

$contenttpl->assign('giornprec',$giornprec);
$contenttpl->assign('giornsucc',$giornsucc);

$contenttpl->assign('squadradett',$utenteObj->getSquadraById($squadra));
$contenttpl->assign('squadre',$utenteObj->getElencoSquadreByLega($_SESSION['idLega']));


$contenttpl->assign('punteggi',$punteggiObj->getAllPunteggi());

require(INCDIR.'giocatore.inc.php');
$giocatoreObj = new giocatore();

if($squadra != NULL && $giornata != NULL && $squadra > 0 && $squadra < 9 && $giornata > 0 && $giornata <= $giornate)
{	
	if($formazioneObj->getFormazioneBySquadraAndGiornata($squadra,$giornata) != FALSE)
	{
		//$result = $punteggiObj->calcolaPunti($giornata,$squadra,FALSE);
		$contenttpl->assign('somma',$punteggiObj->getPunteggi($squadra,$giornata));
		$contenttpl->assign('formazione',$giocatoreObj->getVotiGiocatoriByGiornataSquadra($giornata,$squadra));
		//echo "<pre>".print_r($giocatoreObj->getVotiGiocatoryById($giornata,$squadra),1)."</pre>";
		
	}
	else
	{
		$contenttpl->assign('formazione',2);
		$contenttpl->assign('somma',0);
	}
}
else
	$contenttpl->assign('formazione',FALSE);
?>
