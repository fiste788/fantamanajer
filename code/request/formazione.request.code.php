<?php 
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDBDIR . "formazione.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");
require_once(INCDBDIR . "giocatore.db.inc.php");
require_once(INCDBDIR . "punteggio.db.inc.php");

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
			$id = Formazione::caricaFormazione($formazione,$capitano,GIORNATA,$_SESSION['idUtente'],implode('-',$moduloAr),$jolly);
			Evento::addEvento('3',$_SESSION['idUtente'],$_SESSION['idLega'],$id);
		}
		else
			$id = Formazione::updateFormazione($formazione,$capitano,GIORNATA,$_SESSION['idUtente'],implode('-',$moduloAr),$jolly);
		$message->success('Formazione caricata correttamente');
	}
	else
		$message->error('Hai inserito dei valori multipli');
	if ($missing > 0)
		$message->error('Valori mancanti');
	if ($frega > 0)
		$message->error('Stai cercando di fregarmi?');
}
?>
