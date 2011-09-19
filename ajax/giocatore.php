<?php
	require_once('../config/config.inc.php');
	require_once(INCDIR . 'db.inc.php');
	require_once(INCDIR . 'dbTable.inc.php');
	require_once(INCDIR . 'giocatore.db.inc.php');
	require_once(INCDIR . 'FirePHPCore/FirePHP.class.php');
	
	$firePHP = FirePHP::getInstance(TRUE);
	$dbObj = new db;
	echo json_encode(Giocatore::getGiocatoreByIdWithStats($_GET['idGioc']));
?>
