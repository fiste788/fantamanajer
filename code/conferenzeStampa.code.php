<?php 
require_once(INCDIR . "articolo.db.inc.php");
require_once(INCDIR . "utente.db.inc.php");
require_once(INCDIR . "emoticon.inc.php");

$emoticonObj = new emoticon();
$articoloObj = new articolo();
$utenteObj = new utente();

$filterGiornata = GIORNATA;
if (!empty($_GET['giornata']))
	$filterGiornata = $_GET['giornata'];
if (!empty($_POST['giornata']))
	$filterGiornata = $_POST['giornata'];

$articoloObj->setidgiornata($filterGiornata);
$articoloObj->setidlega($_SESSION['legaView']);

$articolo = $articoloObj->select($articoloObj,'=','*');
if($articolo != FALSE)
	foreach ($articolo as $key => $val)
		$articolo[$key]['text'] = $emoticonObj->replaceEmoticon($val['text'],IMGSURL.'emoticons/');

$giornateWithArticoli = $articoloObj->getGiornateArticoliExist($_SESSION['legaView']);
if($giornateWithArticoli != FALSE)
{
	rsort($giornateWithArticoli);
	if(!in_array(GIORNATA,$giornateWithArticoli))
		array_unshift($giornateWithArticoli,GIORNATA);
	$key = array_search($filterGiornata,$giornateWithArticoli);
}
else
{
	$giornateWithArticoli = FALSE;
	$key = GIORNATA;
}

if(isset($giornateWithArticoli[$key + 1]))
{
	$idPrec = $giornateWithArticoli[$key + 1];
	$quickLinks['prec']['href'] = $contenttpl->linksObj->getLink('conferenzeStampa',array('giornata'=>$idPrec));
	$quickLinks['prec']['title'] = "Giornata " . $idPrec;
}
else
{
	$idPrec = FALSE;
	$quickLinks['prec'] = FALSE;
}
if(isset($giornateWithArticoli[$key -1]))
{
	$idSucc = $giornateWithArticoli[$key - 1];
	$quickLinks['succ']['href'] = $contenttpl->linksObj->getLink('conferenzeStampa',array('giornata'=>$idSucc));
	$quickLinks['succ']['title'] = "Giornata " . $idSucc;
}
else
{
	$idSucc = FALSE;
	$quickLinks['succ'] = FALSE;
}

$contenttpl->assign('articoli',$articolo);
$contenttpl->assign('squadre',$utenteObj->getElencoSquadreByLega($_SESSION['legaView']));
$operationtpl->assign('idGiornata',$filterGiornata);
$operationtpl->assign('giornateWithArticoli',$giornateWithArticoli);
$layouttpl->assign('quickLinks',$quickLinks);
?>
