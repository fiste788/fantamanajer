<?php
require_once(INCDIR . "utente.db.inc.php");
require_once(INCDIR . "formazione.db.inc.php");
require_once(INCDIR . "lega.db.inc.php");
require_once(INCDIR . "giocatore.db.inc.php");
require_once(INCDIR . "punteggio.db.inc.php");
require_once(INCDIR . "voto.db.inc.php");
require_once(INCDIR . 'mail.inc.php');

$mailContent = new Savant3();

$filterSquadra = NULL;
$filterGiornata = NULL;
$filterLega = NULL;
$filterMod = NULL;
if(isset($_POST['lega']) && !empty($_POST['lega']))
	$filterLega = $_POST['lega'];
if(isset($_POST['squadra']) && !empty($_POST['squadra']))
	$filterSquadra = $_POST['squadra'];
if(isset($_POST['mod']) && !empty($_POST['mod']))
	$filterMod = $_POST['mod'];
if(isset($_POST['giornata']) && !empty($_POST['giornata']))
	$filterGiornata = $_POST['giornata'];
if($_SESSION['usertype'] == 'admin')
	$filterLega = $_SESSION['idLega'];

$missing = 0;
$frega = 0;
$ruoliKey = array('P','D','C','A');
$ruo = array('P'=>'Portiere','D'=>'Difensori','C'=>'Centrocampisti','A'=>'Attaccanti');
$elencocap = array('C','VC','VVC');

if($filterLega != NULL)
{
	$squadre = Utente::getElencoSquadreByLega($filterLega);
	$operationTpl->assign('elencosquadre',$squadre);
}

$formImp = Formazione::getFormazioneExistByGiornata($filterGiornata,$filterLega);

$giocatori = Giocatore::getGiocatoriBySquadraAndGiornata($filterSquadra,$filterGiornata);
$contentTpl->assign('giocatori',$giocatori);

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
	if($err == 0)
	{
		unset($_POST);
		if(!$formImp)
			$id = Formazione::caricaFormazione($formazione,$capitano,$filterGiornata,$filterSquadra,$filterMod);
		else
			$id = Formazione::updateFormazione($formazione,$capitano,$filterGiornata,$filterSquadra,$filterMod);
		if(Voto::checkVotiExist($filterGiornata))
		{
			Punteggio::unsetPenalità($filterSquadra,$filterGiornata);
			Punteggio::unsetPunteggio($filterSquadra,$filterGiornata);
			Punteggio::calcolaPunti($filterGiornata,$filterSquadra,$filterLega);
			$squadraDett = Utente::getSquadraById($filterSquadra);
			/*$mailContent->assign('giornata',$filterGiornata);
			$mailContent->assign('squadra',$squadraDett->nome);
			$mailContent->assign('somma',$punteggiObj->getPunteggi($squadra,$giornata));
			$mailContent->assign('formazione',$giocatoreObj->getVotiGiocatoriByGiornataAndSquadra($giornata,$squadra));
			
			$object = "Giornata: ". $giornata . " - Punteggio: " . $punteggiObj->getPunteggi($squadra,$giornata);
			//$mailContent->display(TPLDIR.'mail.tpl.php');
			$mailObj->sendEmail($squadraDett['nomeProp'] . " " . $squadraDett['cognome'] . "<" . $squadraDett['mail']. ">",$mailContent->fetch(TPLDIR.'mail.tpl.php'),$object);*/
			$message->success('Formazione caricata correttamente e punteggio calcolato');
		}
		else
			$message->success('Formazione caricata correttamente');
		$_SESSION['message'] = $message;
		header("Location: " . Links::getLink('areaAmministrativa'));
	}
	else
		$message->error('Hai inserito dei valori multipli');
	if ($missing > 0)
		$message->error('Valori mancanti');
	if ($frega > 0)
		$message->error('Stai cercando di fregarmi?');
}
$titolariAr = array();
$panchinariAr = array();
$capitano = array();
$i = 0;
if(!empty($_POST) && isset($_POST['button']) && $_POST['button'] == 'Invia')
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
	
if($filterMod != NULL)
	$modulo = explode('-',$filterMod);
else
	$modulo = NULL;
	
$elencoLeghe = Lega::getLeghe();
$contentTpl->assign('elencoleghe',$elencoLeghe);

$contentTpl->assign('lega',$filterLega);
$contentTpl->assign('mod',$filterMod);
$contentTpl->assign('modulo',$modulo);
$contentTpl->assign('giornata',$filterGiornata);
$contentTpl->assign('formazioniImpostate',$formImp);
$contentTpl->assign('squadra',$filterSquadra);
$contentTpl->assign('ruo',$ruo);
$contentTpl->assign('ruoliKey',$ruoliKey);
$contentTpl->assign('elencocap',$elencocap);
$operationTpl->assign('elencoleghe',$elencoLeghe);
$operationTpl->assign('lega',$filterLega);
$operationTpl->assign('mod',$filterMod);
$operationTpl->assign('giornata',$filterGiornata);
$operationTpl->assign('squadra',$filterSquadra);
?>
