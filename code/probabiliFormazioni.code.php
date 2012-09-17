<?php
require_once(INCDBDIR . 'club.db.inc.php');

$clubs = Club::getList();
$newClub = array();
foreach ($clubs as $key => $club)
    $newClub[strtolower($club->nome)] = $club->id;

$contentTpl->assign('elencoClub', $newClub);
/*$html = FileSystem::contenutoCurl($url);
phpQuery::newDocument($html);

$formazioni = pq(".formazioni");
*/
?>
