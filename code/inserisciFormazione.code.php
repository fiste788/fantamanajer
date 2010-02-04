<?php
require_once(INCDIR . "utente.db.inc.php");
require_once(INCDIR . "formazione.db.inc.php");
require_once(INCDIR . "lega.db.inc.php");
require_once(INCDIR . "giocatore.db.inc.php");
require_once(INCDIR . "punteggio.db.inc.php");
require_once(INCDIR . "voto.db.inc.php");
require_once(INCDIR . 'mail.inc.php');

$legaObj = new lega();
$utenteObj = new utente();
$formazioneObj = new formazione();
$giocatoreObj = new giocatore();
$punteggioObj = new punteggio();
$votoObj = new voto();
$mailObj = new mail();
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
	$squadre = $utenteObj->getElencoSquadreByLega($filterLega);
	$operationTpl->assign('elencosquadre',$squadre);
}

$formImp = $formazioneObj->getFormazioneExistByGiornata(GIORNATA,$filterLega);

foreach($ruoliKey as $key => $val)
	$giocatori[$val] =	$giocatoreObj->getGiocatoriByIdSquadraAndRuolo($filterSquadra,$val);
$contentTpl->assign('giocatori',$giocatori);
FB::log($_POST);
	if(isset($_POST) && !empty($_POST) && isset($_POST['button']))
	{
		FB::log("APSSO");
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
				$ruoloGioc = $giocatoreObj->getRuoloByIdGioc($idGioc);
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
			if(!$formImp)
				$id = $formazioneObj->caricaFormazione($formazione,$capitano,$filterGiornata,$filterSquadra,$filterMod);
			else
				$id = $formazioneObj->updateFormazione($formazione,$capitano,$filterGiornata,$filterSquadra,$filterMod);
			if($votoObj->checkVotiExist($filterGiornata))
				{
					$punteggioObj->calcolaPunti($filterGiornata,$filterSquadra,$filterLega);
					$squadraDett = $utenteObj->getSquadraById($filterSquadra);
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
				header("Location: ".$contentTpl->linksObj->getLink('areaAmministrativa'));
			$message->success('Formazione caricata correttamente');
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
	
$elencoLeghe = $legaObj->getLeghe();
$contentTpl->assign('elencoleghe',$elencoLeghe);
$operationTpl->assign('elencoleghe',$elencoLeghe);
$contentTpl->assign('lega',$filterLega);
$operationTpl->assign('lega',$filterLega);
$contentTpl->assign('mod',$filterMod);
$operationTpl->assign('mod',$filterMod);
$contentTpl->assign('modulo',explode('-',$filterMod));
$contentTpl->assign('giornata',$filterGiornata);
$operationTpl->assign('giornata',$filterGiornata);
$contentTpl->assign('formazioniImpostate',$formImp);
$contentTpl->assign('squadra',$filterSquadra);
$operationTpl->assign('squadra',$filterSquadra);
$contentTpl->assign('ruo',$ruo);
$contentTpl->assign('ruoliKey',$ruoliKey);
$contentTpl->assign('elencocap',$elencocap);
?>
