<?php 
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDIR . "form/utente.form.inc.php");

if(($utente = Utente::getById($_SESSION['idUtente'])) == FALSE)
	Request::send404();

//$utente->form = new UtenteForm();
//$utente = new UtenteForm($utente);
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
