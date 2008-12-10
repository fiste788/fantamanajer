<?php
require_once(INCDIR."utente.inc.php");
require_once(INCDIR."formazione.inc.php");
require_once(INCDIR."eventi.inc.php");
require_once(INCDIR."giocatore.inc.php");

$utenteObj = new utente();
$eventiObj = new eventi();
$formazioneObj = new formazione();
$giocatoreObj = new giocatore();

$mod = NULL;
$squadra = NULL;
if(isset($_POST['squadra']))
	$squadra = $_POST['squadra'];
if(isset($_POST['mod']))
	$mod = $_POST['mod'];
$contenttpl->assign('squadra',$squadra);

$val = $utenteObj->getElencoSquadre();
$contenttpl->assign('elencosquadre',$val);
	
if(TIMEOUT == FALSE)
	header("Location: ".$contenttpl->linksObj->getLink('altreFormazioni'));

$formImp = $formazioneObj->getFormazioneExistByGiornata(GIORNATA);
if(isset($formImp[$_SESSION['idSquadra']]) && (TIMEOUT))
	unset($formImp[$_SESSION['idSquadra']]);
$contenttpl->assign('formazioniImpostate',$formImp);

$missing = 0;
$cap = "";
if(TIMEOUT)
{
	$issetform = $formazioneObj->getFormazioneBySquadraAndGiornata($_SESSION['idSquadra'],GIORNATA);	
	$ruo = array('P','D','C','A');
	foreach($ruo as $key => $val)
		$giocatori[$val] =	$giocatoreObj->getGiocatoriByIdSquadraAndRuolo($_SESSION['idSquadra'],$val);
	$contenttpl->assign('giocatori',$giocatori);
	$contenttpl->assign('err',0); //ERR=0 COME SE NULL ERR=1  C'È VALORE ERR=2 NON C'È ERRORE 3 VALORE MANCANTE

	//CONTROLLO SE LA FORMAZIONE È GIA SETTATA E IN QUEL CASO LO PASSO ALLA TPL PER VISUALIZZARLO NELLE SELECT
		
	/* CONTROLLI SULL'INPUT: 
	I VALORI NON DEVONO ESSERE DOPPI 
	IL CAPITANO FACOLTATIVO
	*/
	if(isset($_POST) && !empty($_POST) && isset($_POST['button']))
	{
		$formazione = array();
		$capitano = array("C" => NULL,"VC" => NULL,"VVC" => NULL);
		$err = 2;
		foreach($_POST as $key => $val)
		{
			if(strpos($key,'Por') !== FALSE || strpos($key,'Dif') !== FALSE || strpos($key,'Cen') !== FALSE || strpos($key,'Att') !== FALSE || strpos($key,'panch') !== FALSE)
			{
				if((strpos($key,'cap') === FALSE) && (strpos($key,'panch') === FALSE))	//CONTROLLO SE È UNA SELECT RELATIVA AL CAPITANO
				{
					if(empty($val))
					{
						$missing ++;
						$err ++;
					}
					if( !in_array($val,$formazione))
							$formazione[] = $val;		
					else
						$err++;
				}
				if(strpos($key,'panch') !== FALSE)
				{
					if($val != '')		//SE NON È SETTATO LO SALTO E NON LO INSERISCO NELL'ARRAY
					{
						if( !in_array($val,$formazione))
							$formazione[] = $val;
						else
							$err++;
					}	
				}
				if(strpos($key,'cap') !== FALSE)
				{
					if($val != '')		//SE NON È SETTATO LO SALTO E NON LO INSERISCO NELL'ARRAY
					{
						if( $capitano[$val] == NULL)
						{
							if(strpos($key,'Por') !== FALSE)
								$pos = 0;
							else
								$pos = $key{4} + 1;  
							$capitano[$val] = $formazione[$pos];
						}	
						else
							$err++;
					}		
				}
			}
		}
		//echo "<pre>".print_r($formazione,1)."</pre>";
		//echo "<pre>".print_r($capitano,1)."</pre>";
		if ($err == 2)	//VUOL DIRE CHE NON CI SONO VALORI DOPPI
		{
			unset($_POST);
			if(!$issetform)
			{
				$id = $formazioneObj->caricaFormazione($formazione,$capitano,GIORNATA,$_SESSION['idSquadra'],$mod);
				$eventiObj->addEvento('3',$_SESSION['idSquadra'],$_SESSION['idLega'],$id);
			}
			else
				$id = $formazioneObj->updateFormazione($formazione,$capitano,GIORNATA,$_SESSION['idSquadra'],$mod);
			$message[0] = 0;
			$message[1] = 'Formazione caricata correttamente';
		}
		else
		{
			$message[0] = 1;
			$message[1] = 'Hai inserito dei valori multipli';
		}
		if ($missing > 0)
		{
			$message[0] = 1;
			$message[1] = 'Valori mancanti';
		}
		$contenttpl->assign('message',$message);
	}
	$issetform = $formazioneObj->getFormazioneBySquadraAndGiornata($_SESSION['idSquadra'],GIORNATA);	
	if($issetform)
	{
		$mod = $issetform['modulo'];
		$panchinariAr = $issetform['elenco'];
		$titolariAr = array_splice($panchinariAr,0,11);
		$contenttpl->assign('titolari',$titolariAr);
		if(empty($panchinariAr))
			$contenttpl->assign('panchinari',FALSE);
		else
			$contenttpl->assign('panchinari',$panchinariAr);
		$contenttpl->assign('cap',$issetform['cap']);
	}
	$contenttpl->assign('issetForm',$issetform);
	$contenttpl->assign('mod',$mod);
	if($mod != NULL)
		$contenttpl->assign('modulo',explode('-',$mod));
	else
		$contenttpl->assign('modulo',NULL);
}
?>
