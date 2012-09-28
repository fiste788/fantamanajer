<?php 
require_once(INCDIR . 'fileSystem.inc.php');

if($request->get('type') == 'csv')
	$filesVoti = FileSystem::getFileIntoFolder(str_replace('/ajax','',VOTICSVDIR));
else
	$filesVoti = FileSystem::getFileIntoFolder(str_replace('/ajax','',VOTIXMLDIR));
sort($filesVoti);
	
$contentTpl->assign('filesVoti',$filesVoti);
?>
