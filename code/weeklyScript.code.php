<?php 
require_once(INCDIR.'mail.inc.php');
require_once(INCDIR.'utente.inc.php');
require_once(INCDIR.'punteggi.inc.php');
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'formazione.inc.php');
require_once(INCDIR.'voti.inc.php');
require_once(INCDIR.'leghe.inc.php');

//INIZIALIZZO TUTTO CIÒ CHE MI SERVE PER ESEGUIRE LO SCRIPT
$punteggiObj = new punteggi();
$utenteObj = new utente();
$formazioneObj = new formazione();
$mailObj = new mail();
$giocatoreObj = new giocatore();
$votiObj = new voti();
$legheObj = new leghe();

$giornata = $giornataObj->getIdGiornataByDate(date("Y-m-d"))-1;
if((isset($_GET['user']) && trim($_GET['user']) == 'admin' && isset($_GET['pass']) && trim($_GET['pass']) == md5('omordotuanuoraoarounautodromo')) || $_SESSION['usertype'] == 'superadmin')
{
	//CONTROLLO SE È IL SECONDO GIORNO DOPO LA FINE DELLE PARTITE QUINDI ESEGUO LO SCRIPT
	if( (($giornataObj->checkDay(date("Y-m-d")) != FALSE) && date("H") >= 14 && $punteggiObj->checkPunteggi($giornata)) || $_SESSION['usertype'] == 'supersadmin')
	{
		//RECUPERO I VOTI DAL SITO DELLA GAZZETTA E LI INSERISCO NEL DB
		if($votiObj->recuperaVoti($giornata))
		{
			$leghe = $legheObj->getLeghe();
			foreach($leghe as $lega)
			{
				$mailContent = new Savant2();
				$result = array();
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
				$mailContent->assign('classifica',$sum);
				$mailContent->assign('differenza',$diff);
				$mailContent->assign('squadre',$appo);
				$mailContent->assign('giornata',$giornata);
				foreach ($squadre as $key => $val)
				{
					if(!empty($val['mail']))
					{
						$mailContent->assign('squadra',$val['nomeProp']);
						$mailContent->assign('somma',$punteggiObj->getPunteggi($val['idUtente'],$giornata));
						$mailContent->assign('formazione',$giocatoreObj->getVotiGiocatoryByGiornataSquadra($giornata,$val['idUtente']););
						$mail = 0;
						
						//MANDO LA MAIL
						$object = "Giornata: ". $giornata . " - Punteggio: " . $punteggiObj->getPunteggi($val['idUtente'],$giornata);
						//$mailContent->display(TPLDIR.'mail.tpl.php');
						if(!$mailObj->sendEmail($val['nomeProp'] . " " . $val['cognome'] . "<" . $val['mail']. ">",$mailContent->fetch(MAILTPLDIR.'mailWeekly.tpl.php'),$object))
							$mail++ ;
					}
				}
				if($mail == 0)
					$contenttpl->assign('message','Operazione effettuata correttamente');
				else
					$contenttpl->assign('message','Si sono verificati degli errori nell\'invio delle mail');
				unset($mailContent);
			}
			//AGGIORNA LA LISTA GIOCATORI
			$giocatoreObj->updateTabGiocatore($giornata);
		}
	}
	else
		$contenttpl->assign('message','Non puoi effettuare l\'operazione ora');
}
else
	$contenttpl->assign('message','Non sei autorizzato a eseguire l\'operazione');
?>
