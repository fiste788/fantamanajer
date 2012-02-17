<?php
	require_once('../config/config.inc.php');
	require_once(INCDIR . 'db.inc.php');
	require_once(INCDIR . 'request.inc.php');
	require_once(INCDBDIR . 'utente.db.inc.php');
	require_once(INCDIR . 'FirePHPCore/FirePHP.class.php');
	
	$firePHP = FirePHP::getInstance(TRUE);
	$request = new Request();
	$dbObj = new db;
	echo json_encode(Utente::getByField('idLega',$request->get('idLega')));
?>
