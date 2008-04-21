<?php 
require(INCDIR."articolo.inc.php");
require(INCDIR."squadra.inc.php");

$getGiornata = $giornata;
if (!empty($_GET['giorn']))
	$getGiornata = $_GET['giorn'];
$contenttpl->assign('getGiornata',$getGiornata);

$articoloObj = new articolo();
$articoloObj->setidgiornata($getGiornata);
$contenttpl->assign('articoli',$articoloObj->select($articoloObj,'=','*',0,8,NULL));

$squadraObj = new squadra();
$contenttpl->assign('squadre',$squadraObj->getElencoSquadre());
$giornateWithArticoli = $articoloObj->getGiornateArticoliExist();
rsort($giornateWithArticoli);
if(!in_array($giornata,$giornateWithArticoli))
	array_unshift($giornateWithArticoli,$giornata);

$contenttpl->assign('giornateWithArticoli',$giornateWithArticoli);
$key = array_search($getGiornata,$giornateWithArticoli);
if($key > 0)
{
	if(isset($giornateWithArticoli[$key+1]))
		$contenttpl->assign('giornprec',$giornateWithArticoli[$key+1]);
	else
		$contenttpl->assign('giornprec',FALSE);
	$contenttpl->assign('giornsucc',$giornateWithArticoli[$key-1]);
}
elseif($key == 0 || $giornata == $getGiornata)
{
	$contenttpl->assign('giornprec',$giornateWithArticoli[$key+1]);
	$contenttpl->assign('giornsucc',FALSE);
}
elseif(!$key)
{
	$contenttpl->assign('giornprec',FALSE);
	$contenttpl->assign('giornsucc',FALSE);
}

$contenttpl->assign('articoloExist',1);
if(isset($_SESSION['idsquadra']))
{
	$articoloObj->setidgiornata($giornata);
	$articoloObj->setidsquadra($_SESSION['idsquadra']);
	$articoloExist = $articoloObj->select($articoloObj,'=','*',NULL,NULL,NULL);
	if(!empty($articoloExist))
		$contenttpl->assign('articoloExist',0);
}
?>
