<?php 
require_once(INCDBDIR . "utente.db.inc.php");

if(($utente = Utente::getById($_SESSION['idUtente'])) === FALSE)
	Request::send404();

if($utente->validate()) {
	$utente->setAbilitaMail($request->get('abilitaMail') == 'on');
	$utente->setPassword(md5($request->get('passwordnew')));
	/*$lega->setNome($request->get('nome'));
	$lega->setPremi($request->get('premi'));
	$lega->setCapitano($request->get('capitano'));
	$lega->setNumTrasferimenti($request->get('numTrasferimenti'));
	$lega->setNumSelezioni($request->get('numSelezioni'));
	$lega->setMinFormazione($request->get('minFormazione'));
	$lega->setPunteggioFormazioneDimenticata($request->get('punteggioFormazioneDimenticata'));
	$lega->setJolly($request->get('jolly'));*/
	if($utente->save())
		$message->success("Operazione effettuata correttamente");
	else
		$message->error("Errore nell'esecuzione");
}
 	
$contentTpl->assign('utente',$utente)
?>
