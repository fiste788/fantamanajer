<?php
require_once(INCDIR.'squadra.inc.php');
require_once(INCDIR.'formazione.inc.php');
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'mail.inc.php');

$squadraObj = new squadra();
$formazioneObj = new formazione();
$giocatoreObj = new giocatore();
$mailObj = new mail();

$today = date("Y-m-d");
$date = $giornataObj->getDataByGiornata($giornata);
$giorn = explode(' ',$date[2]);
$dataGiornata = $giorn[0];

if(isset($_GET['user']) && trim($_GET['user']) == 'admin' && isset($_GET['pass']) && trim($_GET['pass']) == md5('omordotuanuoraoarounautodromo'))
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
				$titolari = array_slice($formazione['Elenco'],0,11);
				$panchinari = array_slice($formazione['Elenco'],11,18);
				array_shift($panchinari);
				$cap[] = $formazione['Cap'];
				$titolariName[] = $giocatoreObj->getGiocatoriByArray($titolari);
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
				echo "<pre>".print_r($val,1)."</pre>";
				//$mailContent->display(TPLDIR.'mailFormazioni.tpl.php');
				//MANDO LA MAIL
				$object = "Formazioni giornata: ". $giornata ;
			  	$mailObj->sendEmail($val[2] . " " . $val[3] . "<" . $val[4]. ">",$mailContent->fetch(TPLDIR.'mailFormazioni.tpl.php'),$object);
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
