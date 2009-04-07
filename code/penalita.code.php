<?php
require_once(INCDIR.'utente.inc.php');
require_once(INCDIR.'leghe.inc.php');
require_once(INCDIR.'mail.inc.php');
require_once(INCDIR.'punteggi.inc.php');

$utenteObj = new utente();
$legheObj = new leghe();
$mailObj = new mail();
$punteggiObj = new punteggi();
$mailContent = new Savant3();

$giornata = GIORNATA;
$squadra = NULL;
$lega = NULL;
if(isset($_POST['giorn']))
	$giornata = $_POST['giorn'];
if(isset($_POST['lega']))
	$lega = $_POST['lega'];
if(isset($_POST['squad']))
	$squadra = $_POST['squad'];
if($_SESSION['usertype'] == 'admin')
	$lega = $_SESSION['idLega'];

$contenttpl->assign('elencoleghe',$legheObj->getLeghe());
$contenttpl->assign('lega',$lega);
$contenttpl->assign('giornata',$giornata);
$contenttpl->assign('giornate',$punteggiObj->getGiornateWithPunt());
if(isset($squadra))
	$contenttpl->assign('penalitàSquadra',$punteggiObj->getPenalitàBySquadraAndGiornata($squadra,$giornata));

if(isset($_POST['punti']) && isset($_POST['motivo']) && !empty($_POST['punti']) && !empty($_POST['motivo']) && isset($_POST['submit']) && $_POST['submit'] == 'OK')
{
	if(is_numeric($_POST['punti']))
	{	
		$squadraDett = $utenteObj->getSquadraById($squadra);
		$punteggiObj->setPenalità(abs($_POST['punti']),addslashes(stripslashes($_POST['motivo'])),$giornata,$squadra,$lega);
		if($squadraDett['abilitaMail'] == 1)
		{
			$mailContent->assign('punti',$_POST['punti']);
			$mailContent->assign('motivo',$_POST['motivo']);
			$mailContent->assign('lega',$legheObj->getLegaById($lega));
			$mailContent->assign('giornata',$giornata);
			$mailContent->assign('autore',$squadraDett);
			$object = "Penalità!";
			//$mailContent->display(MAILTPLDIR.'mailPenalita.tpl.php');
			$mailObj->sendEmail($squadraDett['mail'],$mailContent->fetch(MAILTPLDIR.'mailPenalita.tpl.php'),$object);
		}
		$message[0] = 0;
		$message[1] = "Penalità aggiunta correttamente";
	}
	else
	{
		$message[0] = 0;
		$message[1] = "Il punteggio deve essere numerico";
	}
	$contenttpl->assign('messaggio',$message);
}
elseif(isset($_POST['submit']) && $_POST['submit'] == 'Cancella')
{
	$punteggiObj->unsetPenalità($squadra,$giornata);
	$message[0] = 0;
	$message[1] = "Penalità cancellata correttamente";	
	$contenttpl->assign('messaggio',$message);
}
if($lega != NULL)
{
	$elencoSquadre = $utenteObj->getElencoSquadreByLega($lega);
	$contenttpl->assign('elencosquadre',$elencoSquadre);
	$contenttpl->assign('squadra',$squadra);
	if($elencoSquadre != FALSE)
	{
		$classificaDett = $punteggiObj->getAllPunteggiByGiornata($giornata,$lega);
		$squadre = $utenteObj->getElencoSquadre();
		foreach($classificaDett as $key => $val)
			$classificaDett[$key] = array_reverse($classificaDett[$key],TRUE); 
		$contenttpl->assign('penalità',$punteggiObj->getPenalitàByLega($lega));
		$contenttpl->assign('classificaDett',$classificaDett);
		$contenttpl->assign('squadre',$squadre);
		if(isset($squadra))
			$contenttpl->assign('penalitàSquadra',$punteggiObj->getPenalitàBySquadraAndGiornata($squadra,$giornata));
	}
}
?>
