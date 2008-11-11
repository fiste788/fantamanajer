<?php 
require_once(INCDIR."articolo.inc.php");
require_once(INCDIR."utente.inc.php");
require_once(INCDIR."emoticon.inc.php");

$emoticonObj = new emoticon();
$articoloObj = new articolo();
$utenteObj = new utente();

$getGiornata = GIORNATA;
if (!empty($_GET['giorn']))
	$getGiornata = $_GET['giorn'];
if (!empty($_POST['giorn']))
	$getGiornata = $_POST['giorn'];
$contenttpl->assign('getGiornata',$getGiornata);

$articoloObj->setidgiornata($getGiornata);

$articolo = $articoloObj->select($articoloObj,'=','*');
if($articolo != FALSE)
	foreach ($articolo as $key => $val)
		$articolo[$key]['text'] = $emoticonObj->replaceEmoticon($val['text'],IMGSURL.'emoticons/');
$contenttpl->assign('articoli',$articolo);


$contenttpl->assign('squadre',$utenteObj->getElencoSquadre());
$giornateWithArticoli = $articoloObj->getGiornateArticoliExist();
if($giornateWithArticoli != FALSE)
{
	rsort($giornateWithArticoli);
	if(!in_array($giornata,$giornateWithArticoli))
		array_unshift($giornateWithArticoli,$giornata);
	$key = array_search($getGiornata,$giornateWithArticoli);
}
else
	$giornateWithArticoli = $key = FALSE;

$contenttpl->assign('giornateWithArticoli',$giornateWithArticoli);
if($key > 0)
{
	if(isset($giornateWithArticoli[$key+1]))
		$contenttpl->assign('giornprec',$giornateWithArticoli[$key+1]);
	else
		$contenttpl->assign('giornprec',FALSE);
	$contenttpl->assign('giornsucc',$giornateWithArticoli[$key-1]);
}
elseif(($key == 0 || $giornata == $getGiornata) && count($giornateWithArticoli) != 1)
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
	$articoloObj->setidgiornata($getGiornata);
	$articoloObj->setidsquadra($_SESSION['idSquadra']);
	$articoloExist = $articoloObj->select($articoloObj,'=','*');
	if(!empty($articoloExist))
		$contenttpl->assign('articoloExist',0);
}
?>
