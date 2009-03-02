<?php
require_once(INCDIR.'utente.inc.php');
require_once(INCDIR.'leghe.inc.php');
require_once(INCDIR.'mail.inc.php');
require_once(INCDIR.'articolo.inc.php');
require_once(INCDIR.'eventi.inc.php');
	
$utenteObj = new utente();
$legheObj = new leghe();
$mailObj = new mail();
$articoloObj = new articolo();
$eventiObj = new eventi();
$mailContent = new Savant2();

$lega = NULL;
if(isset($_POST['lega']))
	$lega = $_POST['lega'];
if($_SESSION['usertype'] == 'admin')
	$lega = $_SESSION['idLega'];

$contenttpl->assign('elencoleghe',$legheObj->getLeghe());
$contenttpl->assign('lega',$lega);

if($lega != NULL && $lega != 0)
	$contenttpl->assign('elencosquadre',$utenteObj->getElencoSquadreByLega($lega));

if(isset($_POST['button']))
{
	unset($_POST['lega']);
	foreach($_POST as $key => $val)
	{
		if(empty($val))
		{
			$message[0] = 1;
			$message[1] = "Non hai compilato tutti i campi";
		}
	}
	if(!isset($_POST['selezione']) || !isset($_POST['type']))
	{
		$message[0] = 1;
		$message[1] = "Non hai compilato tutti i campi";
	}
	if(!isset($message))
	{
		$mailContent->assign('object',$_POST['object']);
		$mailContent->assign('text',nl2br($_POST['text']));
		$mailContent->assign('date',date("d-m-Y"));
		$mailContent->assign('type',$_POST['type']);
		$mailContent->assign('autore',$utenteObj->getSquadraById($_SESSION['idSquadra']));
		if($_POST['type'] == 'C')
		{
			$object = 'Comunicazione: ';
			if($lega == 0)
				$email = $utenteObj->getAllEmail();
			else
				$email = $utenteObj->getAllEmailByLega($lega);
		}
		else
		{
			$object = 'Newsletter: ';
			if($lega == 0)
				$email = $utenteObj->getAllEmailAbilitate();
			else
				$email = $utenteObj->getAllEmailAbilitateByLega($lega);
		}
		$emailOk = array();
		foreach($_POST['selezione'] as $key => $val)
		{
			if(is_array($email[$val]))
				$emailOk = array_merge($email[$val],$emailOk);
			else
				$emailOk[] = $email[$val];
		}
		$object .= $_POST['object'];
		//$mailContent->display(MAILTPLDIR.'mailNewsletter.tpl.php');	
		if($mailObj->sendEmail(implode(",",$emailOk),$mailContent->fetch(MAILTPLDIR . 'mailNewsletter.tpl.php'),$object))
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
			$message[0] = 0;
			$message[1] = 'Mail inviate correttamente';
		}
		else
		{
			$message[0] = 1;
			$message[1] = 'Problemi nell\'invio della mail';
		}
	}
	$contenttpl->assign('message',$message);
}
?>
