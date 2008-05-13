<?php
require(INCDIR.'squadra.inc.php');
require(INCDIR.'formazione.inc.php');
require(INCDIR.'giocatore.inc.php');
require(INCDIR.'mail.inc.php');

$squadraObj = new squadra();
$formazioneObj = new formazione();
$giocatoreObj = new giocatore();
$mailObj = new mail();

$today = date("Y-m-d");
$date = $giornataObj->getDataByGiornata($giornata);
$giorn = explode(' ',$date[2]);
$dataGiornata = $giorn[0];

if(isset($_GET['user']) && trim($_GET['user']) == 'admin' && isset($_GET['password']) && trim($_GET['password']) == md5('omordotuanuoraoarounautodromo'))
{
	if($today == $dataGiornata && date("H") == 18)
	{
		$mailContent = new Savant2();
		$squadre = $squadraObj->getElencoSquadre();
		$mailContent->assign('squadre',$squadre);
		$titolariName = array();
		$panchinariName = array();
		$capitani = array();
		foreach ($squadre as $key => $val)
		{
			$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($val[0],$giornata);
			if($formazione != FALSE)
			{
				$formazione = explode('!',$formazione['Elenco']);
				$titolari = explode(';',$formazione[0]);
				$panchinari = explode(';',$formazione[1]);
				array_shift($panchinari);
				foreach ($titolari as $key2 => $val2)
				{
					$isCap = substr($val2,3);
					if(!empty($isCap))
						$cap[$key][substr($val2,0,3)] = substr($val2,4);
					$titolariAdjust[$key2] = substr($val2,0,3);
				}
				$titolariName[] = $giocatoreObj->getGiocatoriByArray($titolariAdjust);
				if(count($panchinari) > 0)
					$panchinariName[] = $giocatoreObj->getGiocatoriByArray($panchinari);
				else
					$panchinariName[] = FALSE;
			}
			else
			{
				$titolariName[] = $panchinariName[] = $cap[] = FALSE;
			}
		}
		foreach ($squadre as $key => $val)
		{
			if(isset($val[4]))
			{
				$mailContent->assign('titolari',$titolariName);
				$mailContent->assign('panchinari',$panchinariName);
				$mailContent->assign('cap',$cap);

				//$mailContent->display(TPLDIR.'mailFormazioni.tpl.php');
				//MANDO LA MAIL
				$object = "Formazioni giornata: ". $giornata ;
			  	$mailObj->sendEmail($val[4],$mailContent->fetch(TPLDIR.'mailFormazioni.tpl.php'),$object);
			  	$contenttpl->assign('message','Operazione effettuata correttamente');
			}
		}
	}
	else
		$contenttpl->assign('message','Non puoi effettuare l\'operazione ora');
}
else
	$contenttpl->assign('message','Non sei autorizzato a eseguire l\'operazione');
?>
