<?php
require_once(INCDBDIR . "articolo.db.inc.php");
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDIR . "emoticon.inc.php");

$filterGiornata = ($request->has('giornata')) ? $request->get('giornata') : GIORNATA;
$articoli = $currentLega->getArticoliByGiornata($filterGiornata);
if($articoli != FALSE)
	foreach ($articoli as $articolo)
		$articolo->setTesto(Emoticon::replaceEmoticon($articolo->testo,EMOTICONSURL));

$giornateWithArticoli = array_unique(array_merge(array(GIORNATA),Articolo::getGiornateArticoliExist($_SESSION['legaView'])));
rsort($giornateWithArticoli);
$giornateWithArticoli = array_combine($giornateWithArticoli, $giornateWithArticoli);
$quickLinks->set('giornata',$giornateWithArticoli,'Giornata ');
$contentTpl->assign('articoli',$articoli);
$operationTpl->assign('giornata',$filterGiornata);
//$contentTpl->assign('squadre',Utente::getByField('idLega',$_SESSION['legaView']));
$operationTpl->assign('giornateWithArticoli',$giornateWithArticoli);
?>
