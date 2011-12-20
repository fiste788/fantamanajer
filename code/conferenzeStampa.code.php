<?php 
require_once(INCDBDIR . "articolo.db.inc.php");
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDIR . "emoticon.inc.php");

$articoli = Articolo::getArticoliByGiornataAndLega($request->get('giornata'),$_SESSION['legaView']);
if($articoli != FALSE)
	foreach ($articoli as $key => $val)
		$articoli[$key]->text = Emoticon::replaceEmoticon($val->testo,EMOTICONSURL);

$giornateWithArticoli = Articolo::getGiornateArticoliExist($_SESSION['legaView']);
$quickLinks->set('giornata',$giornateWithArticoli,'Giornata ');
$firePHP->log($articoli);
$contentTpl->assign('articoli',$articoli);
//$contentTpl->assign('squadre',Utente::getByField('idLega',$_SESSION['legaView']));
$operationTpl->assign('giornateWithArticoli',$giornateWithArticoli);
?>
