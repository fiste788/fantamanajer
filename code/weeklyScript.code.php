<?php 
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'formazione.db.inc.php');
require_once(INCDIR . 'voto.db.inc.php');
require_once(INCDIR . 'lega.db.inc.php');
require_once(INCDIR . 'mail.inc.php');
require_once(INCDIR . 'decrypt.inc.php');
require_once(INCDIR . 'backup.inc.php');
require_once(INCDIR . 'fileSystem.inc.php');
require_once(INCDIR . 'swiftMailer/swift_required.php');

$utenteObj = new utente();
$punteggioObj = new punteggio();
$giocatoreObj = new giocatore();
$formazioneObj = new formazione();
$votoObj = new voto();
$legaObj = new lega();
//$transportObj = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
$transportObj = Swift_MailTransport::newInstance();
$mailerObj = Swift_Mailer::newInstance($transportObj);
$decryptObj= new decrypt();
$fileSystemObj = new fileSystem();

$backup = $fileSystemObj->contenutoCurl(str_replace("http://","http://administrator:banana@",FULLURL . $contentTpl->linksObj->getLink('backup')));
if(!empty($backup))
{
	$logger->start("WEEKLY SCRIPT");
	
	$giornata = GIORNATA - 1;
	//CONTROLLO SE È IL SECONDO GIORNO DOPO LA FINE DELLE PARTITE QUINDI ESEGUO LO SCRIPT
	if( (($giornataObj->checkDay(date("Y-m-d")) != FALSE) && date("H") >= 17 && $punteggioObj->checkPunteggi($giornata)) || $_SESSION['roles'] == '2')
	{
		$logger->info("Starting decript file day " . $giornata);
		$path = $decryptObj->decryptCdfile($giornata);
		//RECUPERO I VOTI DAL SITO DELLA GAZZETTA E LI INSERISCO NEL DB
		if($path != FALSE || $votoObj->checkVotiExist($giornata))
		{
			if($path != FALSE)
			{
				$logger->info("File with point created succefully");
				$logger->info("Updating table players");
				$giocatoreObj->updateTabGiocatore($path,$giornata);
			}
			else
				$logger->info("Points already exists in database");
			if(!$votoObj->checkVotiExist($giornata))
			{
				$logger->info("Importing points");
				$votoObj->importVoti($path,$giornata);
			}
			$leghe = $legaObj->getLeghe();
			$mail = 0;
			foreach($leghe as $lega)
			{
				$squadre = $utenteObj->getElencoSquadreByLega($lega->idLega);
				$logger->info("Calculating points for league " . $lega->idLega);
				$dbObj->startTransaction();
				$sum = array();
				foreach($squadre as $key =>$val)
				{
					$logger->info("Elaborating team " . $val->idUtente);
					$squadra = $val->idUtente;
					//CALCOLO I PUNTI SE C'È LA FORMAZIONE
					if($formazioneObj->getFormazioneBySquadraAndGiornata($squadra,$giornata) != FALSE)
						$punteggioObj->calcolaPunti($giornata,$squadra,$lega->idLega);
					elseif($lega->punteggioFormazioneDimenticata != 0)
					{
						$i = 1;
						$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($squadra,$giornata - $i);
						while($formazione == FALSE && $i < $giornata)
						{
							$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($squadra,$giornata - $i);
							$i ++;
						}
						$formazioneObj->caricaFormazione(array_values($formazione->elenco),$formazione->cap,$giornata,$squadra,$formazione->modulo);
						$punteggioObj->calcolaPunti($giornata,$squadra,$lega->idLega,$lega->punteggioFormazioneDimenticata);
					}
					else
						$punteggioObj->setPunteggiToZeroByGiornata($squadra,$lega->idLega,$giornata);
				}
				$dbObj->commit();
			
				//ESTRAGGO LA CLASSIFICA E QUELLA DELLA GIORNATA PRECEDENTE
				$classifica = $punteggioObj->getAllPunteggiByGiornata($giornata,$lega->idLega);
				foreach($classifica as $key => $val)
					$sum[$key] = array_sum($classifica[$key]);
				foreach ($squadre as $key => $val)
				{
					if(!empty($val->mail) && $val->abilitaMail == 1)
					{
						$mailContent = new Savant3();
						$mailContent->assign('linksObj',$contentTpl->linksObj);
						$mailContent->assign('classifica',$sum);
						$mailContent->assign('squadre',$squadre);
						$mailContent->assign('giornata',$giornata);
						$penalità = $punteggioObj->getPenalitàBySquadraAndGiornata($val->idUtente,$giornata);
						if($penalità != FALSE)
							$mailContent->assign('penalità',$penalità);
						$mailContent->assign('utente',$val);
						$mailContent->assign('somma',$punteggioObj->getPunteggi($val->idUtente,$giornata));
						$mailContent->assign('formazione',$giocatoreObj->getVotiGiocatoriByGiornataAndSquadra($giornata,$val->idUtente));
						
						$logger->info("Sendig mail to: " . $val->mail);
						//MANDO LA MAIL
						$object = "Giornata: ". $giornata . " - Punteggio: " . $punteggioObj->getPunteggi($val->idUtente,$giornata);						
						$mailContent->setFilters(array("Savant3_Filter_trimwhitespace","filter"));
						$mailMessageObj = Swift_Message::newInstance();
						$mailMessageObj->setSubject($object);
						$mailMessageObj->setFrom(array("noreply@fantamanajer.it"=>"FantaManajer"));
						$mailMessageObj->setTo(array($val->mail=>$val->nomeProp . " " . $val->cognome));
						$fetchMail = $mailContent->fetch(MAILTPLDIR . 'mailWeekly.tpl.php');
						$mailMessageObj->setBody($fetchMail,'text/html');
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
				$message->error("Errori nell'invio delle mail");
		}
		else
		{
			$message->error("Problema nel recupero dei voti dalla gazzetta");
			$logger->error("I can't retrieve data from gazzetta");
		}
	}
	else
	{
		$message->warning("Non puoi effettuare l'operazione ora");
		$logger->warning("Is not time to run it");
	}
}
else
{
	$message->warning("Non riesco a creare il backup");
	$logger->warning("Error while creating backup");
}
$logger->end("WEEKLY SCRIPT");
$contentTpl->assign('message',$message);
?>
