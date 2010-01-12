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

$squadra = NULL;
$giornata = NULL;
$lega = NULL;
$mod = NULL;
if(isset($_POST['lega']) && !empty($_POST['lega']))
	$lega = $_POST['lega'];
if(isset($_POST['squad']) && !empty($_POST['squad']))
	$squadra = $_POST['squad'];
if(isset($_POST['mod']) && !empty($_POST['mod']))
	$mod = $_POST['mod'];
if(isset($_POST['giorn']) && !empty($_POST['giorn']))
	$giornata = $_POST['giorn'];
if($_SESSION['usertype'] == 'admin')
	$lega = $_SESSION['idLega'];

$contentTpl->assign('elencoleghe',$legaObj->getLeghe());
$contentTpl->assign('lega',$lega);
$contentTpl->assign('mod',$mod);
$contentTpl->assign('modulo',explode('-',$mod));
$contentTpl->assign('giornata',$giornata);
if($lega != NULL)
{
	$squadre = $utenteObj->getElencoSquadreByLega($lega);
	$contentTpl->assign('elencosquadre',$squadre);
	if(!isset($squadre[$squadra]))
		$squadra = NULL;
	$contentTpl->assign('squadra',$squadra);
}

$formImp = $formazioneObj->getFormazioneExistByGiornata($giornata);
$missing = 0;
$cap = "";
if(!isset($formImp[$squadra]))
{	
	$contentTpl->assign('formImp',FALSE);
	if($squadra != NULL)
	{
		$giocatori = $giocatoreObj->getGiocatoriBySquadraAndGiornata($squadra,$giornata);
		$contentTpl->assign('giocatori',$giocatori);
		$contentTpl->assign('err',0); //ERR=0 COME SE NULL ERR=1  C'È VALORE ERR=2 NON C'È ERRORE 3 VALORE MANCANTE
		
		//CONTROLLO SE LA FORMAZIONE È GIA SETTATA E IN QUEL CASO LO PASSO ALLA TPL PER VISUALIZZARLO NELLE SELECT
			
		/* CONTROLLI SULL'INPUT: 
		I VALORI NON DEVONO ESSERE DOPPI 
		IL CAPITANO FACOLTATIVO
		*/
		if( isset($_POST) && !empty($_POST) && isset($_POST['button']))
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
				$id = $formazioneObj->caricaFormazione($formazione,$capitano,$giornata,$squadra,$mod);
				if($votiObj->checkVotiExist($giornata))
				{
					$punteggioObj->calcolaPunti($giornata,$squadra,$lega);
					$squadraDett = $utenteObj->getSquadraById($squadra);
					$mailContent->assign('giornata',$giornata);
					$mailContent->assign('squadra',$squadraDett->nome);
					$mailContent->assign('somma',$punteggiObj->getPunteggi($squadra,$giornata));
					$mailContent->assign('formazione',$giocatoreObj->getVotiGiocatoriByGiornataAndSquadra($giornata,$squadra));
					
				   	$object = "Giornata: ". $giornata . " - Punteggio: " . $punteggiObj->getPunteggi($squadra,$giornata);
				   	//$mailContent->display(TPLDIR.'mail.tpl.php');
				  	$mailObj->sendEmail($squadraDett['nomeProp'] . " " . $squadraDett['cognome'] . "<" . $squadraDett['mail']. ">",$mailContent->fetch(TPLDIR.'mail.tpl.php'),$object);
					$message->success('Formazione caricata correttamente e punteggio calcolato');
				}
				else
					$message->success('Formazione caricata correttamente');
				$_SESSION['message'] = $message;
				header("Location: ".$contentTpl->linksObj->getLink('areaAmministrativa'));
			}
		  	else
				$message->error('Hai inserito dei valori multipli');
			if ($missing > 0)
				$message->error('Valori mancanti');
		}
	}
}
else
{
	$contentTpl->assign('formImp',TRUE);
	$message->warning('La formazione per questa squadra è già impostata');
}
?>
