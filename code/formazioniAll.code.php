<?php 
require (INCDIR."squadra.inc.php");
require (INCDIR."formazione.inc.php");
require (INCDIR."giocatore.inc.php");

$squadra = $_SESSION['idsquadra'];
if(isset($_POST['squadra']))
	$squadra = $_POST['squadra'];
$contenttpl->assign('squadra',$squadra);

$squadraObj = new squadra();
$val = $squadraObj->getElencoSquadre();
$contenttpl->assign('elencosquadre',$val);

$formazioneObj = new formazione();
$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($squadra,$giornata);
$formImp = $formazioneObj->getFormazioneExistByGiornata($giornata);
if(isset($formImp[$_SESSION['idsquadra']]) && ($timeout))
	unset($formImp[$_SESSION['idsquadra']]);
$contenttpl->assign('formazioniImpostate',$formImp);

$contenttpl->assign('giocatori',$formazioneObj->getGiocatoriByIdSquadra($squadra));
$contenttpl->assign('formazione',$formazione);
if($formazione != FALSE)
{
if(strpos($formazione['Elenco'],'!') !== FALSE)
{
$giocatori = explode('!',$formazione['Elenco']);
$titolari = explode(';' , $giocatori[0]);
$panchinari= substr($giocatori[1],1);
$panchinari = explode(';' , $panchinari);

foreach($titolari as $appo)
    {
      $pezzi=explode("-",$appo);
      if(count($pezzi)>1)
      {
        $pos=key($titolari);
        $titolari[$pos]=$pezzi[0];
        if($pos==0)
          $chiave="Por-".$pos."-cap";       
        else
          $chiave="Dif-".($pos-1)."-cap";
        $cap[$chiave]=$pezzi[1];
      }
      next($titolari);
    }
}
else
$titolari = explode(';' , $formazione['Elenco']);

$giocatoreObj = new giocatore();
$contenttpl->assign('modulo',$formazione['Modulo']);
$contenttpl->assign('mod',explode('-',$formazione['Modulo']));
$contenttpl->assign('formazione',$formazione['Elenco']);
$contenttpl->assign('titolari',$titolari);
$contenttpl->assign('panchinari',$panchinari);
$contenttpl->assign('cap',$cap);
}
?>
