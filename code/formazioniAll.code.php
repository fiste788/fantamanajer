<?php 
require (INCDIR."squadra.inc.php");
require (INCDIR."formazione.inc.php");
require (INCDIR."giocatore.inc.php");

$squadra = $_SESSION['idsquadra'];
$giorn = GIORNATA;
if(isset($_GET['squadra']))
	$squadra = $_GET['squadra'];
if(isset($_GET['giorn']))
	$giorn = $_GET['giorn'];
$contenttpl->assign('squadra',$squadra);
$contenttpl->assign('getGiornata',$giorn);

$squadraObj = new squadra();
$val = $squadraObj->getElencoSquadre();
$contenttpl->assign('elencosquadre',$val);
$cap=array();
$formazioneObj = new formazione();
$giocatoreObj = new giocatore();
$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($squadra,$giorn);
$formImp = $formazioneObj->getFormazioneExistByGiornata($giorn);

if($formazione != FALSE)
{
    $panchinari_ar=$formazione['Elenco'];
    $titolari_ar=array_splice($panchinari_ar,0,11);
    foreach($formazione['Cap'] as $key=>$val)
   	{
   	    $pos=array_search($val,$titolari_ar);
   		if($pos==0)
   		    $chiave="Por-".$pos."-cap";
   		else
   		   $chiave="Dif-".($pos-1)."-cap";
   		$cap[$chiave]=$key;
    }
    echo "<pre>".print_r($cap,1)."</pre>";
	$contenttpl->assign('titolari',$giocatoreObj->getGiocatoriByArray($titolari_ar));
	$contenttpl->assign('panchinari',$giocatoreObj->getGiocatoriByArray($panchinari_ar));
}
	
$contenttpl->assign('formazioniImpostate',$formImp);

$contenttpl->assign('formazione',$formazione);
$contenttpl->assign('modulo',$formazione['Modulo']);
$contenttpl->assign('mod',explode('-',$formazione['Modulo']));
$contenttpl->assign('formazione',$formazione['Elenco']);

$contenttpl->assign('cap',$cap);


?>
