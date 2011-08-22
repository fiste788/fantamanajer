<?php
require_once(INCDIR . 'club.db.inc.php');

$elencoClub = Club::getElencoClub();
FirePHP::getInstance()->log($elencoClub);
$contentTpl->assign('elencoclub',$elencoClub);

?>
