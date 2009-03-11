<?php 
require_once(INCDIR.'fileSystem.inc.php');
$fileObj = new fileSystem();

$filesVoti = $fileObj->getFileIntoFolder(VOTIDIR);
sort($filesVoti);

$contenttpl->assign('filesVoti',$filesVoti);
?>
