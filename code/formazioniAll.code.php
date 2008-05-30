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

$formazioneObj = new formazione();
$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($squadra,$giorn);
$formImp = $formazioneObj->getFormazioneExistByGiornata($giorn);

$contenttpl->assign('formazioniImpostate',$formImp);
$cap=array();
$contenttpl->assign('giocatori',$formazioneObj->getGiocatoriByIdSquadra($squadra));
$contenttpl->assign('formazione',$formazione);
if($formazione != FALSE)
{
	if(strpos($formazione['Elenco'],'!') !== FALSE)
	{
  	$pieces=explode("!",$formazione['Elenco']);

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
	}
else
	$titolari = explode(';' , $formazione['Elenco']);

$giocatoreObj = new giocatore();
$contenttpl->assign('modulo',$formazione['Modulo']);
$contenttpl->assign('mod',explode('-',$formazione['Modulo']));
$contenttpl->assign('formazione',$formazione['Elenco']);
$contenttpl->assign('titolari',$titolari_ar);
$contenttpl->assign('panchinari',$panchinari_ar);
$contenttpl->assign('cap',$cap);

}
?>
