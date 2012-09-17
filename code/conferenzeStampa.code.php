<?php
require_once(INCDBDIR . "articolo.db.inc.php");
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDIR . "emoticon.inc.php");

$filterGiornata = ($request->has('giornata')) ? $request->get('giornata') : GIORNATA;
$articoli = Articolo::getArticoliByGiornataAndLega($filterGiornata,$_SESSION['legaView']);
if($articoli != FALSE)
	foreach ($articoli as $key => $val)
		$articoli[$key]->text = Emoticon::replaceEmoticon($val->testo,EMOTICONSURL);

$giornateWithArticoli = array_unique(array_merge(array(GIORNATA),Articolo::getGiornateArticoliExist($_SESSION['legaView'])));
$quickLinks->set('giornata',$giornateWithArticoli,'Giornata ');
$contentTpl->assign('articoli',$articoli);
$operationTpl->assign('giornata',$filterGiornata);
//$contentTpl->assign('squadre',Utente::getByField('idLega',$_SESSION['legaView']));
$operationTpl->assign('giornateWithArticoli',$giornateWithArticoli);
?>
