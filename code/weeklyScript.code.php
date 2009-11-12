<?php 
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'formazione.db.inc.php');
require_once(INCDIR . 'voto.db.inc.php');
require_once(INCDIR . 'lega.db.inc.php');
require_once(INCDIR . 'mail.inc.php');
require_once(INCDIR . 'db.inc.php');
require_once(INCDIR . 'decrypt.inc.php');

$utenteObj = new utente();
$punteggioObj = new punteggio();
$giocatoreObj = new giocatore();
$formazioneObj = new formazione();
$votoObj = new voto();
$legaObj = new lega();
$mailObj = new mail();
$dbObj = new db();
$decryptObj= new decrypt();

$giornata = GIORNATA - 1;
//CONTROLLO SE È IL SECONDO GIORNO DOPO LA FINE DELLE PARTITE QUINDI ESEGUO LO SCRIPT
if( (($giornataObj->checkDay(date("Y-m-d")) != FALSE) && date("H") >= 17 && $punteggiObj->checkPunteggi($giornata)) || $_SESSION['roles'] == '2')
{
	$path = $decryptObj->decryptCdfile($giornata);
	//RECUPERO I VOTI DAL SITO DELLA GAZZETTA E LI INSERISCO NEL DB
	if($path != FALSE)
	{
		$giocatoreObj->updateTabGiocatore($path,$giornata);
		if(!$votoObj->checkVotiExist($giornata))
			$votoObj->importVoti($path,$giornata);
		$leghe = $legaObj->getLeghe();
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
					$punteggioObj->calcolaPunti($giornata,$squadra,$lega['idLega']);
				else
					$punteggioObj->setPunteggiToZeroByGiornata($squadra,$lega['idLega'],$giornata);
			}
			$dbObj->commit();
		
			//ESTRAGGO LA CLASSIFICA E QUELLA DELLA GIORNATA PRECEDENTE
			$classifica = $punteggioObj->getAllPunteggiByGiornata($giornata,$lega['idLega']);
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
					$penalità = $punteggioObj->getPenalitàBySquadraAndGiornata($val['idUtente'],$giornata);
					if($penalità != FALSE)
						$mailContent->assign('penalità',$penalità);
					$mailContent->assign('squadra',$val['nome']);
					$mailContent->assign('somma',$punteggioObj->getPunteggi($val['idUtente'],$giornata));
					$mailContent->assign('formazione',$giocatoreObj->getVotiGiocatoriByGiornataAndSquadra($giornata,$val['idUtente']));
					
					//MANDO LA MAIL
					$object = "Giornata: ". $giornata . " - Punteggio: " . $punteggioObj->getPunteggi($val['idUtente'],$giornata);
					//$mailContent->display(MAILTPLDIR.'mail.tpl.php');
					if(!$mailObj->sendEmail($val['nomeProp'] . " " . $val['cognome'] . "<" . $val['mail']. ">",$mailContent->fetch(MAILTPLDIR . 'mailWeekly.tpl.php'),$object))
						$mail++ ;
				}
			}
			unset($mailContent);
		}
		if($mail == 0)
		{
			$message['level'] = 0;
			$message['text'] = "Operazione effettuata correttamente";
		}
		else
		{
			$message['level'] = 1;
			$message['text'] = "Errori nell'invio delle mail";
		}
		//AGGIORNA LA LISTA GIOCATORI
		//$giocatoreObj->updateTabGiocatore($giornata);
	}
	else
	{
		$message['level'] = 1;
		$message['text'] = "Problema nel recupero dei voti dalla gazzetta";
	}
}
else
{
	$message['level'] = 1;
	$message['text'] = "Non puoi effettuare l'operazione ora";
}
$contenttpl->assign('message',$message);
?>
