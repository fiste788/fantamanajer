<?php
require_once(INCDIR . "utente.db.inc.php");
require_once(INCDIR . "formazione.db.inc.php");
require_once(INCDIR . "evento.db.inc.php");
require_once(INCDIR . "giocatore.db.inc.php");
require_once(INCDIR . "punteggio.db.inc.php");

$filterSquadra = NULL;
if(isset($_POST['squadra']))
	$filterSquadra = $_POST['squadra'];
$contentTpl->assign('squadra',$filterSquadra);

$val = Utente::getElencoSquadre();
$contentTpl->assign('elencosquadre',$val);
	
if(PARTITEINCORSO == TRUE)
	header("Location: " . Links::getLink('altreFormazioni'));

$formImp = Formazione::getFormazioneExistByGiornata(GIORNATA,$_SESSION['legaView']);
if(isset($formImp[$_SESSION['idSquadra']]) && !PARTITEINCORSO)
	unset($formImp[$_SESSION['idSquadra']]);
$operationTpl->assign('formazioniImpostate',$formImp);

$missing = 0;
$frega = 0;
$moduloAr = array('P'=>0,'D'=>0,'C'=>0,'A'=>0);
$ruo = array('P','D','C','A');
$elencoCap = array('C','VC','VVC');
$contentTpl->assign('ruo',$ruo);
$contentTpl->assign('elencoCap',$elencoCap);
if(!PARTITEINCORSO)
{
	$formazioneOld = Formazione::getFormazioneBySquadraAndGiornata($_SESSION['idSquadra'],GIORNATA);	
	$giocatori = Giocatore::getGiocatoriByIdSquadra($_SESSION['idSquadra']);
	$contentTpl->assign('giocatori',array_values($giocatori));
	$contentTpl->assign('giocatoriId',$giocatori);
	//CONTROLLO SE LA FORMAZIONE È GIA SETTATA E IN QUEL CASO LO PASSO ALLA TPL PER VISUALIZZARLO NELLE SELECT
		
	/* CONTROLLI SULL'INPUT: 
	I VALORI NON DEVONO ESSERE DOPPI 
	IL CAPITANO FACOLTATIVO
	*/
	if(isset($_POST) && !empty($_POST) && isset($_POST['submit']))
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
			if(isset($giocatori[$val]))
				$moduloAr[$giocatori[$val]->ruolo] = $moduloAr[$giocatori[$val]->ruolo] + 1; 
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
			else
				$formazione[] = $val;
		}
		foreach($_POST['cap'] as $key=>$val)
		{
			if(!empty($val))
			{
				$ruoloGioc = Giocatore::getRuoloByIdGioc($val);
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
			else
				$capitano[$key] = $val;
		}
		$jolly = isset($_POST['jolly']);
		//echo "<pre>".print_r($formazione,1)."</pre>";
		//echo "<pre>".print_r($capitano,1)."</pre>";
		if ($err == 0)	//VUOL DIRE CHE NON CI SONO VALORI DOPPI
		{
			unset($_POST);
			if(!$formazioneOld)
			{
				$id = Formazione::caricaFormazione($formazione,$capitano,GIORNATA,$_SESSION['idSquadra'],implode('-',$moduloAr),$jolly);
				Evento::addEvento('3',$_SESSION['idSquadra'],$_SESSION['idLega'],$id);
			}
			else
				$id = Formazione::updateFormazione($formazione,$capitano,GIORNATA,$_SESSION['idSquadra'],implode('-',$moduloAr),$jolly);
			$message->success('Formazione caricata correttamente');
		}
		else
			$message->error('Hai inserito dei valori multipli');
		if ($missing > 0)
			$message->error('Valori mancanti');
		if ($frega > 0)
			$message->error('Stai cercando di fregarmi?');
	}
	$formazione = Formazione::getFormazioneBySquadraAndGiornata($_SESSION['idSquadra'],GIORNATA);
	if($formazione)
	{
		$modulo = $formazione->modulo;
		$panchinariAr = $formazione->elenco;
		$titolariAr = array_splice($panchinariAr,0,11);
		$contentTpl->assign('cap',$formazione->cap);
		$contentTpl->assign('jolly',$formazione->jolly);
	}
	if(!empty($_POST))
	{
		$i = 0;
		$j = 0;
		foreach($_POST['gioc'] as $key=>$val)
		{
			$titolariAr[$i] = $val;
			$i++;
		}
		foreach($_POST['panch'] as $key=>$val)
		{
			$panchinariAr[$j] = $val;
			$j++;
		}
		foreach($_POST['cap'] as $key=>$val)
			$capitano[$key] = $val;
	}
	if($formazione || !empty($_POST))
	{
		$contentTpl->assign('titolari',$titolariAr);
		if(empty($panchinariAr))
			$contentTpl->assign('panchinari',FALSE);
		else
			$contentTpl->assign('panchinari',$panchinariAr);
	}
	$contentTpl->assign('issetForm',$formazione);
	$contentTpl->assign('usedJolly',Formazione::usedJolly($_SESSION['idSquadra']));
	if(isset($modulo))
		$contentTpl->assign('modulo',explode('-',$modulo));
	else
		$contentTpl->assign('modulo',NULL);
}
?>
