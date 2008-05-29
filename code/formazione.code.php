<?php
/* TO-DO: 
-inserire i valori presenti nell'array formazione nel database
-estrarre i valori nella formazione se è già  stata settata
IL RESTO è FATTO
-caricare i giocatori nelle select(FATTO)
*/

$squadra = NULL;
if(isset($_POST['squadra']))
	$squadra = $_POST['squadra'];
$contenttpl->assign('squadra',$squadra);

require (INCDIR."squadra.inc.php");
require (INCDIR."formazione.inc.php");
require (INCDIR."eventi.inc.php");

$squadraObj = new squadra();
$eventiObj = new eventi();
$val = $squadraObj->getElencoSquadre();
$contenttpl->assign('elencosquadre',$val);
	
if($timeout == FALSE)
	header("Location:index.php?p=formazioniAll");

$formazioneObj = new formazione();
$formImp = $formazioneObj->getFormazioneExistByGiornata($giornata);
if(isset($formImp[$_SESSION['idsquadra']]) && ($timeout))
	unset($formImp[$_SESSION['idsquadra']]);
$contenttpl->assign('formazioniImpostate',$formImp);

$missing=0;
$count=0;
$cap="";
if($timeout)
{
	$issetform = $formazioneObj->getFormazioneBySquadraAndGiornata($_SESSION['idsquadra'],$giornata);	
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
		$capitano = array();
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
        				$formazione[$key] = $val;		
					$count++;
					if($count == 11)
					   $formazione[$key] .='!';
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
						$formazione[$key] = $val;
					}
					else
						$err++;
				}	
			}
			if(strpos($key,'cap') !== FALSE)
			{
				if($val != '')		//SE NON È SETTATO LO SALTO E NON LO INSERISCO NELL'ARRAY
				{		
					if( !in_array($val,$capitano))
						$capitano[$key] = $val;
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
			$eventiObj->addEvento('3',$_SESSION['idsquadra']);
			if(!$issetform)
				$formazioneObj->carica_formazione($formazione,$capitano,$giornata);
			else
				$formazioneObj->updateFormazione($formazione,$capitano,$giornata);
		}
	  	else
			$contenttpl->assign('err',1);
		if ($missing > 0)
	  		$contenttpl->assign('err',3);	
	}
	$issetform = $formazioneObj->getFormazioneBySquadraAndGiornata($_SESSION['idsquadra'],$giornata);	
	$contenttpl->assign('issetForm',$issetform);
  	if($issetform)
	{
		if( !isset($_POST['mod']) && empty($_POST['mod']) )
			$_SESSION['modulo']=$issetform['Modulo'];

		$elenco=$issetform['Elenco'];
		$pieces=explode("!",$elenco);
		
		$titolari=$pieces[0];
		$titolari_ar=explode(";",$titolari);
		foreach($titolari_ar as $key=>$appo)
		{
		  $pezzi=explode("-",$appo);
		  if(count($pezzi)>1)
		  {
		    $pos=$key;
		    $titolari_ar[$pos]=$pezzi[0];
		    if($pos==0)
		      $chiave="Por-".$pos."-cap";       
		    else
		      $chiave="Dif-".($pos-1)."-cap";
		    $cap[$chiave]=$pezzi[1];
		  }
		}
		$panchinari=substr($pieces[1],1);
		$panchinari_ar=explode(";",$panchinari);
		$contenttpl->assign('issetForm',$issetform);
		$contenttpl->assign('titolari',$titolari_ar);
		$contenttpl->assign('panchinari',$panchinari_ar);
		$contenttpl->assign('cap',$cap);
		//echo "$titolari<br>$panchinari<br>$modulo";
	}
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
