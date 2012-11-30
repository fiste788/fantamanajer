<?php
	require_once('../config/config.inc.php');
	require_once(INCDBDIR . 'db.inc.php');
	require_once(INCDIR . 'request.inc.php');
	require_once(INCDBDIR . 'giocatore.db.inc.php');
	require_once(INCDIR . 'FirePHPCore/FirePHP.class.php');
	
	$firePHP = FirePHP::getInstance(TRUE);
	echo json_encode(Giocatore::getGiocatoreByIdWithStats(Request::getInstance()->get('id')));
?>
