<?php
require_once(INCDIR . "utente.db.inc.php");
require_once(INCDIR . "formazione.db.inc.php");
require_once(INCDIR . "evento.db.inc.php");
require_once(INCDIR . "giocatore.db.inc.php");

$filterMod = NULL;
$filterSquadra = NULL;
if(isset($_POST['squadra']))
	$filterSquadra = $_POST['squadra'];
if(isset($_POST['mod']))
	$filterMod = $_POST['mod'];
	
if(PARTITEINCORSO)
	header("Location: " . Links::getLink('altreFormazioni'));

$formImp = Formazione::getFormazioneExistByGiornata(GIORNATA,$_SESSION['legaView']);
if(isset($formImp[$_SESSION['idSquadra']]) && (!PARTITEINCORSO))
	unset($formImp[$_SESSION['idSquadra']]);
$contentTpl->assign('formazioniImpostate',$formImp);

$missing = 0;
$frega = 0;
$ruoliKey = array('P','D','C','A');
$ruo = array('P'=>'Portiere','D'=>'Difensori','C'=>'Centrocampisti','A'=>'Attaccanti');
$elencocap = array('C','VC','VVC');

if(!PARTITEINCORSO)
{
	$formazione = Formazione::getFormazioneBySquadraAndGiornata($_SESSION['idSquadra'],GIORNATA);	
	foreach($ruoliKey as $key => $val)
		$giocatori[$val] =	Giocatore::getGiocatoriByIdSquadraAndRuolo($_SESSION['idSquadra'],$val);
	$contentTpl->assign('giocatori',$giocatori);

	//CONTROLLO SE LA FORMAZIONE È GIA SETTATA E IN QUEL CASO LO PASSO ALLA TPL PER VISUALIZZARLO NELLE SELECT
		
	/* CONTROLLI SULL'INPUT: 
	I VALORI NON DEVONO ESSERE DOPPI 
	IL CAPITANO FACOLTATIVO
	*/
	if(isset($_POST) && !empty($_POST) && isset($_POST['button']))
	{
		$formazione = array();
		$capitano = array();
		//$capitano = array("C" => NULL,"VC" => NULL,"VVC" => NULL);
		$err = 0;
		
		foreach($ruoliKey as $ruolo)
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
				$appo = explode('-',$key);
				$idGioc = $_POST[$appo[0]][$appo[1]];
				$ruoloGioc = Giocatore::getRuoloByIdGioc($idGioc);
				if( $ruoloGioc == 'P' || $ruoloGioc == 'D' )
				{
					if(!array_key_exists($val,$capitano))
						$capitano[$val] = $idGioc;		
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
			if(!$formazione)
			{
				$id = Formazione::caricaFormazione($formazione,$capitano,GIORNATA,$_SESSION['idSquadra'],$filterMod);
				Evento::addEvento('3',$_SESSION['idSquadra'],$_SESSION['idLega'],$id);
			}
			else
				$id = Formazione::updateFormazione($formazione,$capitano,GIORNATA,$_SESSION['idSquadra'],$filterMod);
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
		if(empty($_POST))
			$filterMod = $formazione->modulo;
		$panchinariAr = $formazione->elenco;
		$titolariAr = array_splice($panchinariAr,0,11);
		$capitano = get_object_vars($formazione->cap);
		$i = 0;
		if(!empty($_POST))
		{
			foreach($ruoliKey as $ruolo)
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
			{
				if(!empty($val))
				{
					$appo = explode('-',$key);
					$capitano[$val] = $_POST[$appo[0]][$appo[1]];
				}
			}
		}
		$contentTpl->assign('titolari',$titolariAr);
		if(empty($panchinariAr))
			$contentTpl->assign('panchinari',FALSE);
		else
			$contentTpl->assign('panchinari',$panchinariAr);
		$contentTpl->assign('cap',$capitano);
	}
	$contentTpl->assign('issetForm',$formazione);
	
}
if($filterMod != NULL)
	$modulo = explode('-',$filterMod);
else
	$modulo = NULL;
	
$contentTpl->assign('squadra',$filterSquadra);
$contentTpl->assign('mod',$filterMod);
$contentTpl->assign('modulo',$modulo);
$contentTpl->assign('ruo',$ruo);
$contentTpl->assign('ruoliKey',$ruoliKey);
$contentTpl->assign('elencocap',$elencocap);
$operationTpl->assign('squadra',$filterSquadra);
$operationTpl->assign('mod',$filterMod);
$operationTpl->assign('modulo',$modulo);
?>
