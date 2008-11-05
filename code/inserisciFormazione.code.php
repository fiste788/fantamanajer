<?php
require_once(INCDIR."utente.inc.php");
require_once(INCDIR."formazione.inc.php");
require_once(INCDIR."leghe.inc.php");
require_once(INCDIR."giocatore.inc.php");

$legheObj = new leghe();
$utenteObj = new utente();
$formazioneObj = new formazione();
$giocatoreObj = new giocatore();

$squadra = NULL;
$giornata = NULL;
$lega = NULL;
$modulo = NULL;
if(isset($_POST['lega']))
	$lega = $_POST['lega'];
if(isset($_POST['squad']))
	$squadra = $_POST['squad'];
if(isset($_POST['mod']))
	$mod = $_POST['mod'];
if($_SESSION['usertype'] == 'admin')
	$lega = $_SESSION['idLega'];

$contenttpl->assign('elencoleghe',$legheObj->getLeghe());
$contenttpl->assign('lega',$lega);
$contenttpl->assign('modulo',$mod);
if($lega != NULL)
{
	$contenttpl->assign('elencosquadre',$utenteObj->getElencoSquadreByLega($lega));
	$contenttpl->assign('squadra',$squadra);
}

$formImp = $formazioneObj->getFormazioneExistByGiornata(1);
$missing=0;
$cap="";
if(!isset($formImp[$squadra]))
{	
	if($mod != NULL)
	{
	 	$contenttpl->assign('giocatori',$giocatoreObj->getGiocatoriByIdSquadra($squadra));
		$contenttpl->assign('err',0); //ERR=0 COME SE NULL ERR=1  C'È VALORE ERR=2 NON C'È ERRORE 3 VALORE MANCANTE
		
		//CONTROLLO SE LA FORMAZIONE È GIA SETTATA E IN QUEL CASO LO PASSO ALLA TPL PER VISUALIZZARLO NELLE SELECT
			
		/* CONTROLLI SULL'INPUT: 
		I VALORI NON DEVONO ESSERE DOPPI 
		IL CAPITANO FACOLTATIVO
		*/
		if( isset($_POST) && !empty($_POST) && !isset($_POST['mod']))
		{
			$formazione = array();
			$capitano = array("C" => NULL,"VC" => NULL,"VVC" => NULL);
			$err=2;
			foreach($_POST as $key => $val)
			{
				if((strpos($key,'cap') === FALSE) && (strpos($key,'panch') === FALSE))	//CONTROLLO SE È UNA SELECT RELATIVA AL CAPITANO
				{	
	       			 if(empty($val))
					{
						$missing ++;
						$err ++;
					}
					if( !in_array($val,$formazione))	
					{
	        				$formazione[] = $val;		
					}
					else
					{
						$err++;
					}
				}
				if(strpos($key,'panch') !== FALSE)
				{
	      			  if($val != '')		//SE NON È SETTATO LO SALTO E NON LO INSERISCO NELL'ARRAY
					{	
						if( !in_array($val,$formazione))
						{
							$formazione[] = $val;
						}
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
	                           $pos=0;
	                        else
	                        {
	                            $pos=$key{4}+1;  
							}
	                        $capitano[$val] = $formazione[$pos];
	                        
						}	
						else
							$err++;
					}		
				}
			}
			//echo "<pre>".print_r($formazione,1)."</pre>";
			//echo "<pre>".print_r($capitano,1)."</pre>";
			if ($err == 2)	//VUOL DIRE CHE NON CI SONO VALORI DOPPI
			{
				unset($_POST);
				$id = $formazioneObj->carica_formazione($formazione,$capitano,GIORNATA-1);
				$message[0] = 0;
				$message[1] = 'Formazione caricata correttamente';
				header("Location: ".$this->linksObj->getLink('areaAmministrativa'));
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
		}
	}
}
else
{
	$contenttpl->assign('formImp',TRUE);
	$message[0] = 1;
	$message[1] = 'La formazione per questa squadra è già impostata';
	$_SESSION['message'] = $message;
}
?>
