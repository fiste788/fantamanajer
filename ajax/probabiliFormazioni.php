<?php
	require_once('../config/config.inc.php');
	require_once(INCDIR . 'db.inc.php');
	require_once(INCDIR . 'fileSystem.inc.php');

    $url = "http://www.gazzetta.it/Calcio/prob_form/";
    echo utf8_encode(FileSystem::contenutoCurl($url));
?>
