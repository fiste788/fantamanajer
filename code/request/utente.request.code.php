<?php 
require_once(INCDBDIR . "utente.db.inc.php");

if(($utente = Utente::getById($_SESSION['idUtente'])) == FALSE)
	Request::send404();

if($utente->validate()) {
	$utente->setAbilitaMail($request->get('abilitaMail') == 'on');
    $passwordnew = $request->get('passwordnew');
    if(!empty($passwordnew))
        $utente->setPassword(md5($passwordnew));
    if($utente->save())
		$message->success("Operazione effettuata correttamente");
	else
		$message->error("Errore nell'esecuzione");
}
 	
$contentTpl->assign('utente',$utente)
?>
