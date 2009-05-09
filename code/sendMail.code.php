<?php
require_once(INCDIR.'utente.inc.php');
require_once(INCDIR.'formazione.inc.php');
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'mail.inc.php');
require_once(INCDIR.'leghe.inc.php');

$utenteObj = new utente();
$formazioneObj = new formazione();
$giocatoreObj = new giocatore();
$mailObj = new mail();
$legheObj = new leghe();

$today = date("Y-m-d");
$date = $giornataObj->getDataByGiornata($giornata);
$giorn = explode(' ',$date[2]);
$dataGiornata = $giorn[0];

if(($today == $dataGiornata && date("H") > 17) || $_SESSION['usertype'] == 'superadmin')
{
	$leghe = $legheObj->getLeghe();
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
				//array_shift($panchinari);
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
	{
		$message[0] = 0;
		$message[1] = "Operazione effettuata correttamente";
	}
	else
	{
		$message[0] = 1;
		$message[1] = "Errori nell'invio delle mail";
	}
}
else
{
	$message[0] = 1;
	$message[1] = "Non puoi effettuare l'operazione ora";
}
$contenttpl->assign('message',$message);
?>
