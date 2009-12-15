<?php
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'formazione.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'lega.db.inc.php');
require_once(INCDIR . 'mail.db.inc.php');

$utenteObj = new utente();
$formazioneObj = new formazione();
$giocatoreObj = new giocatore();
$legaObj = new lega();
$mailObj = new mail();

$today = date("Y-m-d");
$date = $giornataObj->getDataByGiornata($giornata);
$giorn = explode(' ',$date[2]);
$dataGiornata = $giorn[0];

if(($today == $dataGiornata && date("H") > 17) || $_SESSION['usertype'] == 'superadmin')
{
	$leghe = $legaObj->getLeghe();
	$mail = 0;
	foreach($leghe as $lega)
	{
		$mailContent = new Savant3();
		$squadre = $utenteObj->getElencoSquadreByLega($lega['idLega']);
		$mailContent->assign('squadre',$squadre);
		$titolariName = array();
		$panchinariName = array();
		$capitani = array();
		foreach ($squadre as $key => $val)
		{
			$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($val['idUtente'],$giornata);
			if($formazione != FALSE)
			{
				$titolari = array_slice($formazione['elenco'],0,11);
				$panchinari = array_slice($formazione['elenco'],11,18);
				$cap[$key] = $formazione['cap'];
				$titolariName[$key] = $giocatoreObj->getGiocatoriByArray($titolari);
				if(count($panchinari) > 0)
					$panchinariName[$key] = $giocatoreObj->getGiocatoriByArray($panchinari);
				else
					$panchinariName[$key] = FALSE;
			}
			else
				$titolariName[$key] = $panchinariName[$key] = $cap[$key] = FALSE;
		}
		foreach ($squadre as $key => $val)
		{
			if(isset($val['mail']) && $val['abilitaMail'] == 1)
			{
				$mailContent->assign('titolari',$titolariName);
				$mailContent->assign('panchinari',$panchinariName);
				$mailContent->assign('cap',$cap);

				//$mailContent->display(MAILTPLDIR.'mailFormazioni.tpl.php');
				//MANDO LA MAIL
				$object = "Formazioni giornata: ". $giornata ;
				if($mailObj->sendEmail($val['nomeProp'] . " " . $val['cognome'] . "<" . $val['mail']. ">",$mailContent->fetch(MAILTPLDIR.'mailFormazioni.tpl.php'),$object))
					$mail++;
			}
		}
	}
	if($mail == 0)
		$message->success("Operazione effettuata correttamente");
	else
		$message->warning("Errori nell'invio delle mail");
}
else
	$message->error("Non puoi effettuare l'operazione ora");
?>
