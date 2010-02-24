<?php
	require_once('../config/config.inc.php');
	require_once('../' . INCDIR . 'db.inc.php');
	require_once('../' . INCDIR . 'dbTable.inc.php');
	require_once('../' . INCDIR . 'fileSystem.inc.php');
	
	$fileSystemObj = new fileSystem();
	
	if($_GET['type'] == 'csv')
		$filesVoti = $fileSystemObj->getFileIntoFolder(str_replace('/ajax','',VOTICSVDIR));
	else
		$filesVoti = $fileSystemObj->getFileIntoFolder(str_replace('/ajax','',VOTIXMLDIR));
	sort($filesVoti); 
	echo json_encode($filesVoti);
?>
