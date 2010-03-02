<?php
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'formazione.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'lega.db.inc.php');
require_once(INCDIR . 'mail.inc.php');
require_once(INCDIR . 'swiftMailer/swift_required.php');

$utenteObj = new utente();
$formazioneObj = new formazione();
$giocatoreObj = new giocatore();
$legaObj = new lega();
$mailObj = new mail();
$transportObj = Swift_MailTransport::newInstance();
$mailerObj = Swift_Mailer::newInstance($transportObj);

$logger->start("MAIL FORMAZIONE");

$today = date("Y-m-d");
$date = $giornataObj->getDataByGiornata(GIORNATA);
FB::log($date);
$giorn = explode(' ',$date->dataFine);
$dataGiornata = $giorn[0];

if(($today == $dataGiornata && date("H") > 17) || $_SESSION['usertype'] == 'superadmin')
{
	$leghe = $legaObj->getLeghe();
	$mail = 0;
	foreach($leghe as $lega)
	{
		$squadre = $utenteObj->getElencoSquadreByLega($lega->idLega);
		$titolariName = array();
		$panchinariName = array();
		$capitani = array();
		foreach ($squadre as $key=>$val)
		{
			$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($val->idUtente,GIORNATA);
			FB::log($formazione);
			if($formazione != FALSE)
			{
				$titolari = array_slice($formazione->elenco,0,11);
				$panchinari = array_slice($formazione->elenco,11,18);
				$cap[$key] = $formazione->cap;
				$titolariName[$key] = $giocatoreObj->getGiocatoriByArray($titolari);
				if(count($panchinari) > 0)
					$panchinariName[$key] = $giocatoreObj->getGiocatoriByArray($panchinari);
				else
					$panchinariName[$key] = FALSE;
			}
			else
				$titolariName[$key] = $panchinariName[$key] = $cap[$key] = FALSE;
		}
		$mailContent = new Savant3();
		$mailContent->assign('squadre',$squadre);
		$mailContent->assign('linksObj',$contentTpl->linksObj);
		$mailContent->assign('titolari',$titolariName);
		$mailContent->assign('panchinari',$panchinariName);
		$mailContent->assign('cap',$cap);
		$mailContent->assign('giornata',GIORNATA);
		
		$logger->info("Sending mail to: " . $val->mail);
		$object = "Formazioni giornata: " . GIORNATA ;
		$mailContent->setFilters(array("Savant3_Filter_trimwhitespace","filter"));
		$mailMessageObj = Swift_Message::newInstance();
		$mailMessageObj->setSubject($object);
		$mailMessageObj->setFrom(array("noreply@fantamanajer.it"=>"FantaManajer"));
		$fetchMail = $mailContent->fetch(MAILTPLDIR . 'mailFormazioni.tpl.php');
		$mailMessageObj->setBody($fetchMail,'text/html');
		foreach ($squadre as $key => $val)
		{
			if(isset($val->mail) && $val->abilitaMail == 1)
			{
				$mailMessageObj->setTo(array($val->mail=>$val->nomeProp . " " . $val->cognome));
				if(!$mailerObj->send($mailMessageObj)) 
				{
					$mail++;
					$logger->warning("Error in sending mail to: " . $val->mail);
				}
				else
					$logger->info("Mail send succesfully to: " . $val->mail);
			}
		}
	}
	if($mail == 0)
		$message->success("Operazione effettuata correttamente");
	else
		$message->warning("Errori nell'invio delle mail");
}
else
{
	$message->warning("Non puoi effettuare l'operazione ora");
	$logger->warning("Is not time to run it");
}
$logger->end("MAIL FORMAZIONE");
$contentTpl->assign("message",$message);
?>
