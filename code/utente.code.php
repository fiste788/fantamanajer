<?php 
require_once(INCDBDIR . "utente.db.inc.php");
/*require_once(INCDBDIR . "giocatore.db.inc.php");
Giocatore::getFoto();
die();*/
if(($utente = Utente::getById($_SESSION['idUtente'])) === FALSE)
	Request::send404();

$contentTpl->assign('utente',$utente);
?>
