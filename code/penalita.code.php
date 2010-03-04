<?php
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'lega.db.inc.php');
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'mail.inc.php');

$mailContent = new Savant3();

$filterGiornata = GIORNATA;
$filterSquadra = NULL;
$filterLega = NULL;

if(isset($_POST['giornata']))
	$filterGiornata = $_POST['giornata'];
if(isset($_POST['squadra']))
	$filterSquadra = $_POST['squadra'];
if(isset($_POST['lega']))
	$filterLega = $_POST['lega'];
if($_SESSION['roles'] == '1')
	$filterLega = $_SESSION['idLega'];

if(isset($_POST['punti']) && isset($_POST['motivo']) && !empty($_POST['punti']) && !empty($_POST['motivo']) && isset($_POST['submit']) && $_POST['submit'] == 'OK')
{
	if(is_numeric($_POST['punti']))
	{	
		$squadraDett = Utente::getSquadraById($filterSquadra);
		Punteggio::setPenalità(abs($_POST['punti']),addslashes(stripslashes($_POST['motivo'])),$filterGiornata,$filterSquadra,$filterLega);
		if($squadraDett->abilitaMail == 1)
		{
			$mailContent->assign('punti',$_POST['punti']);
			$mailContent->assign('motivo',$_POST['motivo']);
			$mailContent->assign('lega',Lega::getLegaById($filterLega));
			$mailContent->assign('giornata',$filterGiornata);
			$mailContent->assign('autore',$squadraDett);
			$object = "Penalità!";
			//$mailContent->display(MAILTPLDIR.'mailPenalita.tpl.php');
			Mail::sendEmail($squadraDett->mail,$mailContent->fetch(MAILTPLDIR . 'mailPenalita.tpl.php'),$object);
		}
		$message->success("Penalità aggiunta correttamente");
	}
	else
		$message->error("Il punteggio deve essere numerico e non 0");
}
elseif(isset($_POST['submit']) && $_POST['submit'] == 'Cancella')
{
	Punteggio::unsetPenalità($filterSquadra,$filterGiornata);
	$message->success("Penalità cancellata correttamente");	
}
if($filterLega != NULL)
{
	$elencoSquadre = Utente::getElencoSquadreByLega($filterLega);
	$contentTpl->assign('elencoSquadre',$elencoSquadre);
	$contentTpl->assign('squadra',$filterSquadra);
	$contentTpl->assign('lega',$filterLega);
	$operationTpl->assign('elencoSquadre',$elencoSquadre);
	$operationTpl->assign('squadra',$filterSquadra);
	$operationTpl->assign('lega',$filterLega);
	if($elencoSquadre != FALSE)
	{
		$classificaDett = Punteggio::getAllPunteggiByGiornata($filterGiornata,$filterLega);
		foreach($classificaDett as $key => $val)
			$classificaDett[$key] = array_reverse($classificaDett[$key],TRUE); 
		$contentTpl->assign('penalità',Punteggio::getPenalitàByLega($filterLega));
		$contentTpl->assign('classificaDett',$classificaDett);
		$contentTpl->assign('squadre',$elencoSquadre);
		if(isset($squadra))
			$contentTpl->assign('penalitàSquadra',Punteggio::getPenalitàBySquadraAndGiornata($filterSquadra,$filterGiornata));
	}
}
$contentTpl->assign('giornata',$filterGiornata);
if(isset($filterSquadra))
	$contentTpl->assign('penalitàSquadra',Punteggio::getPenalitàBySquadraAndGiornata($filterSquadra,$filterGiornata));
$operationTpl->assign('elencoLeghe',Lega::getLeghe());
$operationTpl->assign('lega',$filterLega);
$operationTpl->assign('giornata',$filterGiornata);
$operationTpl->assign('giornate',Punteggio::getGiornateWithPunt());
?>
