<?php
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'formazione.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(INCDBDIR . 'lega.db.inc.php');
require_once(VIEWDIR . 'GiocatoreStatistiche.view.db.inc.php');
require_once(INCDIR . 'mail.inc.php');

$transportObj = Mail::getDefaultTransport();
$mailerObj = Swift_Mailer::newInstance($transportObj);

$logger->start("MAIL FORMAZIONE");

$today = date("Y-m-d");
$date = Giornata::getById(GIORNATA);
$firePHP->log($date);
$giorn = explode(' ',$date->dataFine->date);
$dataGiornata = $giorn[0];
$timeGiornata = $giorn[1];
$difference = Giornata::getTimeDiff($timeGiornata);

if(($today == $dataGiornata && $difference < 300) || $_SESSION['usertype'] == 'superadmin') {
	$leghe = Lega::getList();
	$mail = 0;
	foreach($leghe as $lega) {
		$squadre = Utente::getByField('idLega',$lega->id);
		$formazioni = array();
		foreach ($squadre as $key=>$squadra) {
			$formazione = Formazione::getFormazioneBySquadraAndGiornata($key,GIORNATA);

			if($formazione != FALSE) {
				$giocatori[$key] = GiocatoreStatistiche::getByField('idUtente',$key);
				$formazioni[$key] = $formazione;
			}
		}

		$mailContent = new MySavant3(array('template_path' => MAILTPLDIR));
		$mailContent->assign('squadre',$squadre);
		$mailContent->assign('formazione',$formazioni);
		$mailContent->assign('giocatori',$giocatori);
		//$mailContent->assign('cap',$cap);
		$mailContent->assign('giornata',GIORNATA);

		$object = "Formazioni giornata: " . GIORNATA ;
		//$mailContent->setFilters(array("Savant3_Filter_trimwhitespace","filter"));
		$mailMessageObj = Swift_Message::newInstance();
		$mailMessageObj->setSubject($object);
		$mailMessageObj->setFrom(array(MAILFROM=>"FantaManajer"));
		$fetchMail = $mailContent->fetch('mailFormazioni.tpl.php');

		$mailMessageObj->setBody($fetchMail,'text/html');
		foreach ($squadre as $key => $squadra) {
			if(!is_null($squadra->getEmail()) && $squadra->getAbilitaMail()) {
				$logger->info("Sending mail to: " . $squadra->getEmail());
				$mailMessageObj->setTo(array($squadra->getEmail()=>$squadra->getNome() . " " . $squadra->getCognome()));
				if(!$mailerObj->send($mailMessageObj))  {
					$mail++;
					$logger->warning("Error in sending mail to: " . $squadra->getEmail());
				} else
					$logger->info("Mail send succesfully to: " . $squadra->getEmail());
			}
		}
	}
	if($mail == 0)
		$message->success("Operazione effettuata correttamente");
	else
		$message->warning("Errori nell'invio delle mail");
} else {
	$message->warning("Non puoi effettuare l'operazione ora");
	$logger->warning("Is not time to run it");
}
$logger->end("MAIL FORMAZIONE");
$contentTpl->assign("message",$message);
?>
