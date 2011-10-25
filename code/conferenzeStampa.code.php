<?php 
require_once(INCDBDIR . "articolo.db.inc.php");
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDIR . "emoticon.inc.php");

$articoli = Articolo::getArticoliByGiornataAndLega($request->get('giornata'),$_SESSION['legaView']);
if($articoli != FALSE)
	foreach ($articoli as $key => $val)
		$articoli[$key]->text = Emoticon::replaceEmoticon($val->text,EMOTICONSURL);

$giornateWithArticoli = Articolo::getGiornateArticoliExist($_SESSION['legaView']);
$firePHP->fb($giornateWithArticoli);
$keys = $giornateWithArticoli;
$current = array_search($request->get('giornata'),$keys);
$firePHP->fb($current);
if(isset($keys[($idPrec = $current - 1)]))
{
	$quickLinks->prec->href = Links::getLink('conferenzeStampa',array('giornata'=>$keys[$idPrec]));
	$quickLinks->prec->title = "Giornata " . $keys[$idPrec];
}
else
	$quickLinks->prec = FALSE;
if(isset($keys[($idSucc = $current + 1)]))
{
	$quickLinks->succ->href = Links::getLink('conferenzeStampa',array('giornata'=>$keys[$idSucc]));
	$quickLinks->succ->title = "Giornata " . $keys[$idSucc];
}
else
	$quickLinks->succ = FALSE;

$contentTpl->assign('articoli',$articoli);
$contentTpl->assign('squadre',Utente::getByField('idLega',$_SESSION['legaView']));
$operationTpl->assign('giornateWithArticoli',$giornateWithArticoli);
$layoutTpl->assign('quickLinks',$quickLinks);
?>
