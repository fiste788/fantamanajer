<?php
	require_once('../config/config.inc.php');
	require_once('../' . INCDIR . 'db.inc.php');
	require_once('../' . INCDIR . 'dbTable.inc.php');
	require_once('../' . INCDIR . 'utente.db.inc.php');
	require_once('../' . INCDIR . 'FirePHPCore/FirePHP.class.php');
	
	define("DEBUG",FALSE);

	$firePHP = FirePHP::getInstance(TRUE);
	$dbObj = new db;
	echo json_encode(Utente::getElencoSquadreByLegaOptions($_GET['idLega']));
?>
