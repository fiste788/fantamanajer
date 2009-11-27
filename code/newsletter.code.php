<?php
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'lega.db.inc.php');
require_once(INCDIR . 'articolo.db.inc.php');
require_once(INCDIR . 'evento.db.inc.php');
require_once(INCDIR . 'mail.inc.php');
	
$utenteObj = new utente();
$legaObj = new lega();
$articoloObj = new articolo();
$eventoObj = new evento();
$mailObj = new mail();
$mailContent = new Savant3();

$filterLega = NULL;
if(isset($_POST['lega']))
	$filterLega = $_POST['lega'];
if($_SESSION['roles'] == '1')
	$filterLega = $_SESSION['idLega'];

$elencoLeghe = $legaObj->getLeghe();

if(isset($_POST['button']))
{
	$error = FALSE;
	unset($_POST['lega']);
	foreach($_POST as $key => $val)
	{
		if(empty($val))
		{
			$error = TRUE;
			$message['level'] = 1;
			$message['text'] = "Non hai compilato tutti i campi";
		}
	}
	if(!isset($_POST['selezione']) || !isset($_POST['type']))
	{
		$error = TRUE;
		$message['level'] = 1;
		$message['text'] = "Non hai compilato tutti i campi";
	}
	if(!$error)
	{
		$mailContent->assign('object',$_POST['object']);
		$mailContent->assign('text',nl2br($_POST['text']));
		$mailContent->assign('date',date("d-m-Y"));
		$mailContent->assign('type',$_POST['type']);
		$mailContent->assign('autore',$utenteObj->getSquadraById($_SESSION['idSquadra']));
		
		if($_POST['type'] == 'C')
		{
			$object = 'Comunicazione: ';
			if($filterLega == 0)
				$email = $utenteObj->getAllEmail();
			else
				$email = $utenteObj->getAllEmailByLega($filterLega);
		}
		else
		{
			$object = 'Newsletter: ';
			if($filterLega == 0)
				$email = $utenteObj->getAllEmailAbilitate();
			else
				$email = $utenteObj->getAllEmailAbilitateByLega($filterLega);
		}
		$object .= $_POST['object'];
		$bool = TRUE;
		if($filterLega == 0)
			foreach($_POST['selezione'] as $key => $val)
				$bool *= $mailObj->sendEmail(implode(",",$email[$val]),$mailContent->fetch(MAILTPLDIR . 'mailNewsletter.tpl.php'),$object);
		else
				$bool *= $mailObj->sendEmail(implode(",",array_intersect_key($email,array_flip($_POST['selezione']))),$mailContent->fetch(MAILTPLDIR . 'mailNewsletter.tpl.php'),$object);
		if($bool)
		{
			if(isset($_POST['conferenza']))
			{
				$articoloObj->settitle(addslashes(stripslashes($_POST['object'])));
				$articoloObj->settext(addslashes(stripslashes($_POST['text'])));
				$articoloObj->setinsertdate(date("Y-m-d H:i:s"));
				$articoloObj->setidgiornata(GIORNATA);
				$articoloObj->setidsquadra($_SESSION['idSquadra']);
				$articoloObj->setidlega($_SESSION['idLega']);
				$idArticolo = $articoloObj->add($articoloObj);
				$eventiObj->addEvento('1',$_SESSION['idSquadra'],$_SESSION['idLega'],$idArticolo);
			}
			$message['level'] = 0;
			$message['text'] = 'Mail inviate correttamente';
		}
		else
		{
			$message['level'] = 1;
			$message['text'] = 'Problemi nell\'invio delle mail';
		}
	}
	$layouttpl->assign('message',$message);
}
$contenttpl->assign('elencoLeghe',$elencoLeghe);
$contenttpl->assign('lega',$filterLega);
if($filterLega != NULL && $filterLega != 0)
	$contenttpl->assign('elencoSquadre',$utenteObj->getElencoSquadreByLega($filterLega));
$operationtpl->assign('elencoLeghe',$elencoLeghe);
$operationtpl->assign('lega',$filterLega);
?>
