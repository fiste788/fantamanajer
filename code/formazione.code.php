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

$formImp = $formazioneObj->getFormazioneExistByGiornata(GIORNATA,$_SESSION['legaView']);
if(isset($formImp[$_SESSION['idSquadra']]) && (TIMEOUT))
	unset($formImp[$_SESSION['idSquadra']]);
$contenttpl->assign('formazioniImpostate',$formImp);

$missing = 0;
$frega = 0;
$ruo = array('P','D','C','A');
$elencocap = array('C','VC','VVC');
$contenttpl->assign('ruo',$ruo);
$contenttpl->assign('elencocap',$elencocap);
if(TIMEOUT)
{
	$issetform = $formazioneObj->getFormazioneBySquadraAndGiornata($_SESSION['idSquadra'],GIORNATA);	
	foreach($ruo as $key => $val)
		$giocatori[$val] =	$giocatoreObj->getGiocatoriByIdSquadraAndRuolo($_SESSION['idSquadra'],$val);
	$contenttpl->assign('giocatori',$giocatori);

	//CONTROLLO SE LA FORMAZIONE È GIA SETTATA E IN QUEL CASO LO PASSO ALLA TPL PER VISUALIZZARLO NELLE SELECT
		
	/* CONTROLLI SULL'INPUT: 
	I VALORI NON DEVONO ESSERE DOPPI 
	IL CAPITANO FACOLTATIVO
	*/
	if(isset($_POST) && !empty($_POST) && isset($_POST['button']))
	{
		$formazione = array();
		$capitano = array("C" => NULL,"VC" => NULL,"VVC" => NULL);
		$err = 0;
		
		foreach($ruo as $ruolo)
		{
			foreach($_POST[$ruolo] as $key=>$val)
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
		}
		foreach($_POST['panch'] as $key=>$val)
		{
			if(!empty($val))
			{
				if( !in_array($val,$formazione))
					$formazione[] = $val;		
				else
					$err++;
			}	
		}
		foreach($_POST['cap'] as $key=>$val)
		{
			if(!empty($val))
			{
				$ruoloGioc = $giocatoreObj->getRuoloByIdGioc($val);
				if( $ruoloGioc == 'P' || $ruoloGioc == 'D' )
				{
					if( !in_array($val,$capitano))
						$capitano[$key] = $val;		
					else
						$err++;
				}
				else
				{
					$frega++;
					$err++;
				}
			}	
		}
		//echo "<pre>".print_r($formazione,1)."</pre>";
		//echo "<pre>".print_r($capitano,1)."</pre>";
		if ($err == 0)	//VUOL DIRE CHE NON CI SONO VALORI DOPPI
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
		if ($frega > 0)
		{
			$message[0] = 1;
			$message[1] = 'Stai cercando di fregarmi?';
		}
		$contenttpl->assign('message',$message);
	}
	$issetform = $formazioneObj->getFormazioneBySquadraAndGiornata($_SESSION['idSquadra'],GIORNATA);	
	if($issetform)
	{
		if(empty($_POST))
			$mod = $issetform['modulo'];
		$panchinariAr = $issetform['elenco'];
		$titolariAr = array_splice($panchinariAr,0,11);
		$i = 0;
		if(!empty($_POST))
		{
			foreach($ruo as $ruolo)
			{
				foreach($_POST[$ruolo] as $key=>$val)
				{
					$titolariAr[$i] = $val;
					$i++;
				}
			}
			foreach($_POST['panch'] as $key=>$val)
			{
				$panchinariAr[$i] = $val;
				$i++;
			}
			foreach($_POST['cap'] as $key=>$val)
				$capitano[$key] = $val;
		}
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
