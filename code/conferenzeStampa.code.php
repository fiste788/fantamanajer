<?php 
require_once(INCDIR."articolo.inc.php");
require_once(INCDIR."utente.inc.php");
require_once(INCDIR."emoticon.inc.php");

$emoticonObj = new emoticon();
$articoloObj = new articolo();
$utenteObj = new utente();

$getSquadra = 0;
if (!empty($_GET['squadra']))
	$getSquadra = $_GET['squadra'];
if (!empty($_POST['squadra']))
	$getSquadra = $_POST['squadra'];
$contenttpl->assign('getSquadra',$getSquadra);

if ($getSquadra == 0)
	$getGiornata = GIORNATA;
else
	$getGiornata = 0;
if (!empty($_GET['giornata']))
	$getGiornata = $_GET['giornata'];
if (!empty($_POST['giornata']))
	$getGiornata = $_POST['giornata'];

if($getGiornata == 0)
	$articoloObj->setidsquadra($getSquadra);
elseif($getSquadra == 0)
	$articoloObj->setidgiornata($getGiornata);
else
{
	$articoloObj->setidsquadra($getSquadra);
	$articoloObj->setidgiornata($getGiornata);
}
$articoloObj->setidlega($_SESSION['legaView']);

$articolo = $articoloObj->select($articoloObj,'=','*');
if($articolo != FALSE)
	foreach ($articolo as $key => $val)
		$articolo[$key]['text'] = $emoticonObj->replaceEmoticon($val['text'],IMGSURL.'emoticons/');
$contenttpl->assign('articoli',$articolo);


$contenttpl->assign('squadre',$utenteObj->getElencoSquadreByLega($_SESSION['legaView']));
$giornateWithArticoli = $articoloObj->getGiornateArticoliExist($_SESSION['legaView']);

if($giornateWithArticoli != FALSE)
{
	rsort($giornateWithArticoli);
	if(!in_array(GIORNATA,$giornateWithArticoli))
		array_unshift($giornateWithArticoli,GIORNATA);
	$key = array_search($getGiornata,$giornateWithArticoli);
}
else
	$giornateWithArticoli = $key = FALSE;

$operationtpl->assign('giornateWithArticoli',$giornateWithArticoli);

if($key > 0)
{
	if(isset($giornateWithArticoli[$key+1]))
		$operationtpl->assign('giornprec',$giornateWithArticoli[$key+1]);
	else
		$operationtpl->assign('giornprec',FALSE);
	$operationtpl->assign('giornsucc',$giornateWithArticoli[$key-1]);
}
elseif(($key == 0 || GIORNATA == $getGiornata) && count($giornateWithArticoli) != 1)
{
	$operationtpl->assign('giornprec',$giornateWithArticoli[$key+1]);
	$operationtpl->assign('giornsucc',FALSE);
}
elseif(!$key)
{
	$operationtpl->assign('giornprec',FALSE);
	$operationtpl->assign('giornsucc',FALSE);
}
$operationtpl->assign('articoloExist',1);
$operationtpl->assign('getGiornata',$getGiornata);
if(isset($_SESSION['idSquadra']))
{
	$articoloObj->setidgiornata($getGiornata);
	$articoloObj->setidsquadra($_SESSION['idSquadra']);
	$articoloObj->setidlega($_SESSION['idLega']);
	$articoloExist = $articoloObj->select($articoloObj,'=','*');
	if(!empty($articoloExist))
		$contenttpl->assign('articoloExist',0);
}
?>
