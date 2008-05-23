<?php 
require (INCDIR.'punteggi.inc.php');

$punteggiObj = new punteggi();

$contenttpl->assign('classifica',$punteggiObj->getClassifica());
?>
