<?php 
require_once(INCDBDIR . "articolo.db.inc.php");
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDIR . "emoticon.inc.php");

$filterGiornata = GIORNATA;
if (!empty($_GET['giornata']))
	$filterGiornata = $_GET['giornata'];
if (!empty($_POST['giornata']))
	$filterGiornata = $_POST['giornata'];

$articoli = Articolo::getArticoliByGiornataAndLega($filterGiornata,$_SESSION['legaView']);
if($articoli != FALSE)
	foreach ($articoli as $key => $val)
		$articoli[$key]->text = Emoticon::replaceEmoticon($val->text,EMOTICONSURL);

$giornateWithArticoli = Articolo::getGiornateArticoliExist($_SESSION['legaView']);
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
	$quickLinks->prec->href = Links::getLink('conferenzeStampa',array('giornata'=>$idPrec));
	$quickLinks->prec->title = "Giornata " . $idPrec;
}
else
{
	$idPrec = FALSE;
	$quickLinks->prec = FALSE;
}
if(isset($giornateWithArticoli[$key - 1]))
{
	$idSucc = $giornateWithArticoli[$key - 1];
	$quickLinks->succ->href = Links::getLink('conferenzeStampa',array('giornata'=>$idSucc));
	$quickLinks->succ->title = "Giornata " . $idSucc;
}
else
{
	$idSucc = FALSE;
	$quickLinks->succ = FALSE;
}

$contentTpl->assign('articoli',$articoli);
$contentTpl->assign('squadre',Utente::getByField('idLega',$_SESSION['legaView']));
$operationTpl->assign('idGiornata',$filterGiornata);
$operationTpl->assign('giornateWithArticoli',$giornateWithArticoli);
$layoutTpl->assign('quickLinks',$quickLinks);
?>
