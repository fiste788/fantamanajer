<?php 
require_once(INCDBDIR . "utente.db.inc.php");

if(($utente = Utente::getById($_SESSION['idUtente'])) === FALSE)
	Request::send404();

$contentTpl->assign('utente',$utente);
?>
