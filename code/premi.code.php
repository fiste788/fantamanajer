<?php 
require_once(INCDIR.'punteggio.db.inc.php');

$punteggioObj = new punteggio();

$contenttpl->assign('classifica',$punteggioObj->getClassificaByGiornata($_SESSION['idLega'],GIORNATA));
?>
