<?php
	require_once('../config/config.inc.php');
	require_once(INCDIR . 'db.inc.php');
	require_once(INCDIR . 'request.inc.php');
	require_once(INCDBDIR . 'giocatore.db.inc.php');
	require_once(INCDIR . 'FirePHPCore/FirePHP.class.php');
	
	$firePHP = FirePHP::getInstance(TRUE);
	$dbObj = new db;
	$request = new Request();
	echo json_encode(Giocatore::getGiocatoreByIdWithStats($request->get('id')));
?>
