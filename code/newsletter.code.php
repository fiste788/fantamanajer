<?php
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'lega.db.inc.php');
require_once(INCDIR . 'articolo.db.inc.php');
require_once(INCDIR . 'evento.db.inc.php');
require_once(INCDIR . 'mail.inc.php');
require_once(INCDIR . 'swiftMailer/swift_required.php');

$transportObj = Swift_MailTransport::newInstance();
$mailerObj = Swift_Mailer::newInstance($transportObj);
$articoloObj = new Articolo();
$mailContent = new Savant3();

$filterLega = NULL;
if(isset($_POST['lega']))
	$filterLega = $_POST['lega'];
if($_SESSION['roles'] == '1')
	$filterLega = $_SESSION['idLega'];

$elencoLeghe = Lega::getLeghe();

if(isset($_POST['button']))
{
	unset($_POST['lega']);
	foreach($_POST as $key => $val)
	{
		if(empty($val))
			$message->error("Non hai compilato tutti i campi");
	}
	if(!isset($_POST['selezione']) || !isset($_POST['type']))
		$message->error("Non hai compilato tutti i campi");
	if(!$message->show)
	{
		$mailContent->assign('object',$_POST['object']);
		$mailContent->assign('text',nl2br($_POST['text']));
		$mailContent->assign('date',date("d-m-Y"));
		$mailContent->assign('type',$_POST['type']);
		$mailContent->assign('autore',Utente::getSquadraById($_SESSION['idSquadra']));
		$mailContent->setFilters(array("Savant3_Filter_trimwhitespace","filter"));
		if($_POST['type'] == 'C')
		{
			$object = 'Comunicazione: ';
			if($filterLega == 0)
				$email = Utente::getAllEmail();
			else
				$email = Utente::getAllEmailByLega($filterLega);
		}
		else
		{
			$object = 'Newsletter: ';
			if($filterLega == 0)
				$email = Utente::getAllEmailAbilitate();
			else
				$email = Utente::getAllEmailAbilitateByLega($filterLega);
		}
		$object .= $_POST['object'];
		$bool = TRUE;
		
		$mailMessageObj = Swift_Message::newInstance();
		$mailMessageObj->setSubject($object);
		$mailMessageObj->setFrom(array("noreply@fantamanajer.it"=>"FantaManajer"));
		$fetchMail = $mailContent->fetch(MAILTPLDIR . 'mailNewsletter.tpl.php');
		$mailMessageObj->setBody($fetchMail,'text/html');
		$emailFiltered = array_intersect_key($email,array_flip($_POST['selezione']));
		$emailOk = array();
		foreach($emailFiltered as $key => $val)
			$emailOk = array_merge($emailOk,$val);
		
		foreach($emailOk as $key => $val){
			$mailMessageObj->setTo($val);
			$bool *= $mailerObj->send($mailMessageObj);
		}
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
			$message->success('Mail inviate correttamente');
		}
		else
			$message->error('Problemi nell\'invio delle mail');
	}
}
$contentTpl->assign('elencoLeghe',$elencoLeghe);
$contentTpl->assign('lega',$filterLega);
if($filterLega != NULL && $filterLega != 0)
	$contentTpl->assign('elencoSquadre',Utente::getElencoSquadreByLega($filterLega));
$operationTpl->assign('elencoLeghe',$elencoLeghe);
$operationTpl->assign('lega',$filterLega);
?>
