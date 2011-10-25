<?php 
require_once(INCDBDIR . "lega.db.inc.php");

$contentTpl->assign('lega', $_SESSION['datiLega']);
$contentTpl->assign('default',Lega::getDefaultValue());
?>
