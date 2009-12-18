<?php
	require_once('../../config/config.inc.php');
	require_once('../../' . INCDIR . 'utente.db.inc.php');
	require_once('../../' . INCDIR . 'db.inc.php');

	$dbObj = new db;
	$utenteObj = new utente();
	echo json_encode($utenteObj->getElencoSquadreByLegaOptions($_GET['idLega']));
?>
