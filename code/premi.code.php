<?php 
require_once(INCDIR.'punteggi.inc.php');

$punteggiObj = new punteggi();

$contenttpl->assign('classifica',$punteggiObj->getClassificaByGiornata($_SESSION['idLega'],GIORNATA));
?>
