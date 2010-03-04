<?php 
require_once(INCDIR.'punteggio.db.inc.php');

$contentTpl->assign('classifica',Punteggio::getClassificaByGiornata($_SESSION['idLega'],GIORNATA));
?>
