<?php
	require_once('../config/config.inc.php');
	require_once(INCDIR . 'db.inc.php');
	require_once(INCDIR . 'request.inc.php');
	require_once(INCDIR . 'fileSystem.inc.php');
	require_once(INCDIR . 'FirePHPCore/FirePHP.class.php');

	$firePHP = FirePHP::getInstance(TRUE);
	$request = new Request();

	if($request->get('type') == 'csv')
		$filesVoti = FileSystem::getFileIntoFolder(str_replace('/ajax','',VOTICSVDIR));
	else
		$filesVoti = FileSystem::getFileIntoFolder(str_replace('/ajax','',VOTIXMLDIR));
	sort($filesVoti);
	echo json_encode($filesVoti);
?>
