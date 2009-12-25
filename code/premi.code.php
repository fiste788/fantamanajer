<?php 
require_once(INCDIR.'punteggio.db.inc.php');

$punteggioObj = new punteggio();

$contentTpl->assign('classifica',$punteggioObj->getClassificaByGiornata($_SESSION['idLega'],GIORNATA));
?>
