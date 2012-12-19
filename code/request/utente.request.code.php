<?php
require_once(INCDBDIR . "utente.db.inc.php");

if(($utente = Utente::getById($_SESSION['idUtente'])) == FALSE)
	Request::send404();

$password = $utente->getPassword() == "" ? $utente->getOriginalValues("password") : md5($utente->getPassword());
$utente->setPassword($password);
    
if ($utente->save())
    $message->success("Operazione effettuata correttamente");
else
    $message->error("Errore nell'esecuzione");

$contentTpl->assign('utente',$utente)
?>
