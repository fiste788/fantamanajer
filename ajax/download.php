<?php
	require_once('../config/config.inc.php');
	require_once('../' . INCDIR . 'db.inc.php');
	require_once('../' . INCDIR . 'dbTable.inc.php');
	require_once('../' . INCDIR . 'fileSystem.inc.php');
	require_once('../' . INCDIR . 'FirePHPCore/FirePHP.class.php');
	
	$firePHP = FirePHP::getInstance(TRUE);
		
	if($_GET['type'] == 'csv')
		$filesVoti = FileSystem::getFileIntoFolder(str_replace('/ajax','',VOTICSVDIR));
	else
		$filesVoti = FileSystem::getFileIntoFolder(str_replace('/ajax','',VOTIXMLDIR));
	sort($filesVoti); 
	echo json_encode($filesVoti);
?>
