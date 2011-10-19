<?php
require_once(INCDBDIR . 'club.db.inc.php');

$contentTpl->assign('elencoClub',Club::getList());
?>
