<?php
require_once(INCDIR."squadra.inc.php");
require_once(INCDIR."formazione.inc.php");
require_once(INCDIR."eventi.inc.php");

$squadraObj = new squadra();
$eventiObj = new eventi();
$formazioneObj = new formazione();

$squadra = NULL;
if(isset($_POST['squadra']))
	$squadra = $_POST['squadra'];
$contenttpl->assign('squadra',$squadra);


$val = $squadraObj->getElencoSquadre();
$contenttpl->assign('elencosquadre',$val);
	
if(TIMEOUT == FALSE)

	header("Location:index.php?p=formazioniAll");

$formImp = $formazioneObj->getFormazioneExistByGiornata(GIORNATA);

if(isset($formImp[$_SESSION['idsquadra']]) && (TIMEOUT))
	unset($formImp[$_SESSION['idsquadra']]);
$contenttpl->assign('formazioniImpostate',$formImp);

$missing=0;
$cap="";
if(TIMEOUT)
{
	$issetform = $formazioneObj->getFormazioneBySquadraAndGiornata($_SESSION['idsquadra'],GIORNATA);	
 	$contenttpl->assign('giocatori',$formazioneObj->getGiocatoriByIdSquadra($_SESSION['idsquadra']));
	//SETTO A NULL IL VALORE DEL MODULO NELLA SESSIONE
	if( !isset($_SESSION ['modulo']))
		$_SESSION['modulo'] = NULL;
	$contenttpl->assign('err',0); //ERR=0 COME SE NULL ERR=1  C'È VALORE ERR=2 NON C'È ERRORE 3 VALORE MANCANTE
	
	//RITORNO IN UN ARRAY I VALORI DEL MODULO AL PRIMO POSTO I POR AL SECONDO I DIF E AL TERZO I CC E AL QUARTO GLI ATT 
	if( isset($_POST['mod']) && !empty($_POST['mod']) )
		$_SESSION ['modulo'] = $_POST ['mod'];
	else
		$contenttpl->assign('value',NULL);
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
		if ($err == 2 && !isset($_POST['username']))	//VUOL DIRE CHE NON CI SONO VALORI DOPPI
		{
			unset($_POST);
			$contenttpl->assign('err',2);
			if(!$issetform)
				$id = $formazioneObj->carica_formazione($formazione,$capitano,GIORNATA);
			else
				$id = $formazioneObj->updateFormazione($formazione,$capitano,GIORNATA);
			$eventiObj->addEvento('3',$_SESSION['idsquadra'],$id);
		}
	  	else
			$contenttpl->assign('err',1);
		if ($missing > 0)
	  		$contenttpl->assign('err',3);	
	}
	$issetform = $formazioneObj->getFormazioneBySquadraAndGiornata($_SESSION['idsquadra'],GIORNATA);	
  if($issetform)
	{
		if( !isset($_POST['mod']) && empty($_POST['mod']) )
			$_SESSION['modulo']=$issetform['Modulo'];

		$panchinari_ar=$issetform['Elenco'];
   		$titolari_ar=array_splice($panchinari_ar,0,11);
   		foreach($issetform['Cap'] as $key=>$val)
   		{
   		   $pos=array_search($val,$titolari_ar);
   		   if($pos==0)
   		       $chiave="Por-".$pos."-cap";
   		   else
   		       $chiave="Dif-".($pos-1)."-cap";
   		   $cap[$chiave]=$key;
      }

		$contenttpl->assign('titolari',$titolari_ar);
		$contenttpl->assign('panchinari',$panchinari_ar);
        $contenttpl->assign('cap',$cap);
	}
		$contenttpl->assign('issetForm',$issetform);
	if($_SESSION['modulo'] != NULL)
	{
		$mod = explode('-',$_SESSION ['modulo']);
		$contenttpl->assign('value',$_SESSION ['modulo']);
		$contenttpl->assign('modulo',$mod);
	}
	else
		$contenttpl->assign('modulo',NULL);
}
?>
