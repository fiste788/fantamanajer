<?php 
require_once(INCDIR.'punteggi.inc.php');

$punteggiObj = new punteggi();

$contenttpl->assign('classifica',$punteggiObj->getClassifica($_SESSION['idLega'],GIORNATA));
?>
