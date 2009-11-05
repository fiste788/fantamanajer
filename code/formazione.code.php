<?php
require_once(INCDIR . "utente.db.inc.php");
require_once(INCDIR . "formazione.db.inc.php");
require_once(INCDIR . "evento.db.inc.php");
require_once(INCDIR . "giocatore.db.inc.php");

$utenteObj = new utente();
$formazioneObj = new formazione();
$eventoObj = new evento();
$giocatoreObj = new giocatore();

$squadra = NULL;
if(isset($_POST['squadra']))
	$squadra = $_POST['squadra'];
$contenttpl->assign('squadra',$squadra);

$val = $utenteObj->getElencoSquadre();
$contenttpl->assign('elencosquadre',$val);
	
if(PARTITEINCORSO == TRUE)
	header("Location: ".$contenttpl->linksObj->getLink('altreFormazioni'));

$formImp = $formazioneObj->getFormazioneExistByGiornata(GIORNATA,$_SESSION['legaView']);
if(isset($formImp[$_SESSION['idSquadra']]) && (TIMEOUT))
	unset($formImp[$_SESSION['idSquadra']]);
$contenttpl->assign('formazioniImpostate',$formImp);

$missing = 0;
$frega = 0;
$moduloAr = array('P'=>0,'D'=>0,'C'=>0,'A'=>0);
$ruo = array('P','D','C','A');
$elencoCap = array('C','VC','VVC');
$contenttpl->assign('ruo',$ruo);
$contenttpl->assign('elencoCap',$elencoCap);
if(!PARTITEINCORSO)
{
	$issetform = $formazioneObj->getFormazioneBySquadraAndGiornata($_SESSION['idSquadra'],GIORNATA);	
	$giocatori = $giocatoreObj->getGiocatoriByIdSquadra($_SESSION['idSquadra']);
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
		
		foreach($_POST['gioc'] as $key=>$val)
		{
			if(empty($val))
			{
				$missing ++;
				$err ++;
			}
			$moduloAr[$giocatori[$val]['ruolo']] = $moduloAr[$giocatori[$val]['ruolo']] + 1; 
			if( !in_array($val,$formazione))
				$formazione[] = $val;
			else
				$err++;
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
		echo $err;
		//echo "<pre>".print_r($formazione,1)."</pre>";
		//echo "<pre>".print_r($capitano,1)."</pre>";
		if ($err == 0)	//VUOL DIRE CHE NON CI SONO VALORI DOPPI
		{
			unset($_POST);
			if(!$issetform)
			{
				$id = $formazioneObj->caricaFormazione($formazione,$capitano,GIORNATA,$_SESSION['idSquadra'],implode('-',$moduloAr));
				$eventoObj->addEvento('3',$_SESSION['idSquadra'],$_SESSION['idLega'],$id);
			}
			else
				$id = $formazioneObj->updateFormazione($formazione,$capitano,GIORNATA,$_SESSION['idSquadra'],implode('-',$moduloAr));
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
		$modulo = $issetform['modulo'];
		$panchinariAr = $issetform['elenco'];
		$titolariAr = array_splice($panchinariAr,0,11);
		$i = 0;
		if(!empty($_POST))
		{
			foreach($_POST['gioc'] as $key=>$val)
			{
				$titolariAr[$i] = $val;
				$i++;
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
	if(isset($modulo))
		$contenttpl->assign('modulo',explode('-',$modulo));
	else
		$contenttpl->assign('modulo',NULL);
}
?>
