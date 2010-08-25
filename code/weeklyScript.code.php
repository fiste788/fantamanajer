<?php 
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'formazione.db.inc.php');
require_once(INCDIR . 'voto.db.inc.php');
require_once(INCDIR . 'lega.db.inc.php');
require_once(INCDIR . 'decrypt.inc.php');
require_once(INCDIR . 'backup.inc.php');
require_once(INCDIR . 'fileSystem.inc.php');
require_once(INCDIR . 'swiftMailer/swift_required.php');

$transportObj = Swift_MailTransport::newInstance();
$mailerObj = Swift_Mailer::newInstance($transportObj);

//$giornata = GIORNATA - 1;
$giornata = 0;
$logger->start("WEEKLY SCRIPT");
//CONTROLLO SE È IL SECONDO GIORNO DOPO LA FINE DELLE PARTITE QUINDI ESEGUO LO SCRIPT
/*if( ((Giornata::checkDay(date("Y-m-d")) != FALSE) && date("H") >= 17 && Punteggio::checkPunteggi($giornata)) || $_SESSION['roles'] == '2')
{*/
	$backup = fileSystem::contenutoCurl(FULLURLAUTH . Links::getLink('backup'));
	if(!empty($backup))
	{
		$logger->info("Starting decript file day " . $giornata);
		$path = Decrypt::decryptCdfile($giornata);
		//RECUPERO I VOTI DAL SITO DELLA GAZZETTA E LI INSERISCO NEL DB
		if($path != FALSE || Voto::checkVotiExist($giornata))
		{
			if($path != FALSE)
			{
				$logger->info("File with point created succefully");
				$logger->info("Updating table players");
				Giocatore::updateTabGiocatore($path,$giornata);
			}
			else
				$logger->info("Points already exists in database");
			if($giornata > 0) {
				if(!Voto::checkVotiExist($giornata))
				{
					$logger->info("Importing points");
					Voto::importVoti($path,$giornata);
				}
				$leghe = Lega::getLeghe();
				$mail = 0;
				foreach($leghe as $lega)
				{
					$squadre = Utente::getElencoSquadreByLega($lega->idLega);
					$logger->info("Calculating points for league " . $lega->idLega);
					$dbObj->startTransaction();
					$sum = array();
					foreach($squadre as $key =>$val)
					{
						$logger->info("Elaborating team " . $val->idUtente);
						$squadra = $val->idUtente;
						//CALCOLO I PUNTI SE C'È LA FORMAZIONE
						if(Formazione::getFormazioneBySquadraAndGiornata($squadra,$giornata) != FALSE)
							Punteggio::calcolaPunti($giornata,$squadra,$lega->idLega);
						elseif(Lega::punteggioFormazioneDimenticata != 0)
						{
							$i = 1;
							$formazione = Formazione::getFormazioneBySquadraAndGiornata($squadra,$giornata - $i);
							while($formazione == FALSE && $i < $giornata)
							{
								$formazione = Formazione::getFormazioneBySquadraAndGiornata($squadra,$giornata - $i);
								$i ++;
							}
							Formazione::caricaFormazione(array_values($formazione->elenco),$formazione->cap,$giornata,$squadra,$formazione->modulo);
							Punteggio::calcolaPunti($giornata,$squadra,$lega->idLega,$lega->punteggioFormazioneDimenticata);
						}
						else
							Punteggio::setPunteggiToZeroByGiornata($squadra,$lega->idLega,$giornata);
					}
					$dbObj->commit();
				
					//ESTRAGGO LA CLASSIFICA E QUELLA DELLA GIORNATA PRECEDENTE
					$classifica = Punteggio::getAllPunteggiByGiornata($giornata,$lega->idLega);
					foreach($classifica as $key => $val)
						$sum[$key] = array_sum($classifica[$key]);
					foreach ($squadre as $key => $val)
					{
						if(!empty($val->mail) && $val->abilitaMail == 1)
						{
							$mailContent = new Savant3();
							//$mailContent->assign('linksObj',Links);
							$mailContent->assign('classifica',$sum);
							$mailContent->assign('squadre',$squadre);
							$mailContent->assign('giornata',$giornata);
							$penalità = Punteggio::getPenalitàBySquadraAndGiornata($val->idUtente,$giornata);
							if($penalità != FALSE)
								$mailContent->assign('penalità',$penalità);
							$mailContent->assign('utente',$val);
							$mailContent->assign('somma',Punteggio::getPunteggi($val->idUtente,$giornata));
							$mailContent->assign('formazione',Giocatore::getVotiGiocatoriByGiornataAndSquadra($giornata,$val->idUtente));
							
							$logger->info("Sendig mail to: " . $val->mail);
							//MANDO LA MAIL
							$object = "Giornata: " . $giornata . " - Punteggio: " . Punteggio::getPunteggi($val->idUtente,$giornata);						
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
		$message->warning("Non riesco a creare il backup");
		$logger->warning("Error while creating backup");
	}
/*}
else
{
	$message->warning("Non puoi effettuare l'operazione ora");
	$logger->warning("Is not time to run it");
}*/
$logger->end("WEEKLY SCRIPT");
$contentTpl->assign('message',$message);
?>
