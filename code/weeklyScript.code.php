<?php 
require_once(INCDIR.'mail.inc.php');
require_once(INCDIR.'utente.inc.php');
require_once(INCDIR.'punteggi.inc.php');
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'formazione.inc.php');
require_once(INCDIR.'voti.inc.php');
require_once(INCDIR.'leghe.inc.php');
require_once(INCDIR.'db.inc.php');
require_once(INCDIR.'decrypt.inc.php');

//INIZIALIZZO TUTTO CIÒ CHE MI SERVE PER ESEGUIRE LO SCRIPT
$punteggiObj = new punteggi();
$utenteObj = new utente();
$formazioneObj = new formazione();
$mailObj = new mail();
$giocatoreObj = new giocatore();
$votiObj = new voti();
$legheObj = new leghe();
$dbObj = new db();
$decryptObj= new decrypt();

$giornata = GIORNATA - 1;
//CONTROLLO SE È IL SECONDO GIORNO DOPO LA FINE DELLE PARTITE QUINDI ESEGUO LO SCRIPT
if( (($giornataObj->checkDay(date("Y-m-d")) != FALSE) && date("H") >= 14 && $punteggiObj->checkPunteggi($giornata)) || $_SESSION['usertype'] == 'superadmin')
{
	//RECUPERO I VOTI DAL SITO DELLA GAZZETTA E LI INSERISCO NEL DB

	if($result=$decryptObj->decryptCdfile($giornata))
	{
		$giocatoreObj->updateTabGiocatore($result,$giornata);
		if(!$votiObj->checkVotiExist($giornata))
			$decryptObj->importVoti($result,$giornata);
		$leghe = $legheObj->getLeghe();
		$mail = 0;
		foreach($leghe as $lega)
		{
			$squadre = $utenteObj->getElencoSquadreByLega($lega['idLega']);
			$dbObj->startTransaction();
			foreach($squadre as $key =>$val)
			{
				$squadra = $val['idUtente'];
				//CALCOLO I PUNTI SE C'È LA FORMAZIONE
				if($formazioneObj->getFormazioneBySquadraAndGiornata($squadra,$giornata) != FALSE)
					$punteggiObj->calcolaPunti($giornata,$squadra,$lega['idLega']);
				else
					$punteggiObj->setPunteggiToZeroByGiornata($squadra,$lega['idLega'],$giornata);
			}
			$dbObj->commit();
		
			//ESTRAGGO LA CLASSIFICA E QUELLA DELLA GIORNATA PRECEDENTE
			$classifica = $punteggiObj->getAllPunteggiByGiornata($giornata,$lega['idLega']);
			$appo2 = $classifica;
			foreach($appo2 as $key => $val)
			{
				array_pop($appo2[$key]);
				$prevSum[$key] = array_sum($appo2[$key]);
			} 
			foreach($classifica as $key => $val)
				$sum[$key] = array_sum($classifica[$key]);
			arsort($prevSum);
		
			foreach($prevSum as $key => $val)
				$indexPrevSum[] = $key;
			foreach($sum as $key => $val)
				$indexSum[] = $key;
			
			foreach($indexSum as $key => $val)
			{
				if($val == $indexPrevSum[$key])
					$diff[] = 0;
				else
					$diff[] = (array_search($val,$indexPrevSum))- $key;
			}
			
			foreach ($squadre as $key => $val)
			{
				if(!empty($val['mail']) && $val['abilitaMail'] == 1)
				{
					$mailContent = new Savant3();
					$mailContent->assign('classifica',$sum);
					$mailContent->assign('differenza',$diff);
					$mailContent->assign('squadre',$squadre);
					$mailContent->assign('giornata',$giornata);
					$penalità = $punteggiObj->getPenalitàBySquadraAndGiornata($val['idUtente'],$giornata);
					if($penalità != FALSE)
						$mailContent->assign('penalità',$penalità);
					$mailContent->assign('squadra',$val['nome']);
					$mailContent->assign('somma',$punteggiObj->getPunteggi($val['idUtente'],$giornata));
					$mailContent->assign('formazione',$giocatoreObj->getVotiGiocatoriByGiornataAndSquadra($giornata,$val['idUtente']));
					
					//MANDO LA MAIL
					$object = "Giornata: ". $giornata . " - Punteggio: " . $punteggiObj->getPunteggi($val['idUtente'],$giornata);
					//$mailContent->display(MAILTPLDIR.'mail.tpl.php');
					if(!$mailObj->sendEmail($val['nomeProp'] . " " . $val['cognome'] . "<" . $val['mail']. ">",$mailContent->fetch(MAILTPLDIR.'mailWeekly.tpl.php'),$object))
						$mail++ ;
				}
				if(!empty($val['cell']) && $val['abilitaMess'] == 1)
				{
					$sms = "";
					$sms .= "Punteggio giornata " . $giornata . ": ";
					$sms .= $punteggiObj->getPunteggi($val['idUtente'],$giornata);
					$giocatori = $giocatoreObj->getVotiGiocatoriByGiornataAndSquadra($giornata,$val['idUtente']);
					foreach($giocatori as $key2 => $val2)
						$sms .= $val2['cognome'] . " " . $val2['voto'] . ",";
					$smsFlag = 0;
					
					if(!$mailObj->sendEmailToVodafone($val['cell'],$sms))
						$smsFlag++ ;
				}
			}
			unset($mailContent);
		}
		if($mail == 0)
		{
			$message[0] = 0;
			$message[1] = "Operazione effettuata correttamente";
		}
		else
		{
			$message[0] = 1;
			$message[1] = "Errori nell'invio delle mail";
		}
		//AGGIORNA LA LISTA GIOCATORI
		//$giocatoreObj->updateTabGiocatore($giornata);
	}
	else
	{
		$message[0] = 1;
		$message[1] = "Problema nel recupero dei voti dalla gazzetta";
	}
}
else
{
	$message[0] = 1;
	$message[1] = "Non puoi effettuare l'operazione ora";
}
$contenttpl->assign('message',$message);
?>
