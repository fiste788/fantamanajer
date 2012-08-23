<?php 
require_once(INCDBDIR . "articolo.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");

if(!$request->has('id') || ($request->has('id') && $request->get('id') == ""))
	$articolo = new Articolo();
elseif(($articolo = Articolo::getById($request->get('id'))) === FALSE)
	Request::send404();

if($request->get('submit') == 'Rimuovi'){
	if($articolo->delete()) {
		$message->success('Cancellazione effettuata con successo');
		$_SESSION['message'] = $message;
		Request::goToUrl('conferenzeStampa');
	} else
		$message->error("Errore nella cancellazione della conferenza");
} else {
    if($articolo->validate()) {
		$articolo->setIdUtente($_SESSION['idUtente']);
		$articolo->setIdGiornata(GIORNATA);
		$articolo->setIdLega(1);
		$articolo->setDataCreazione('now');

		if($articolo->save())
			$message->success("Inserimento completato con successo");
		else
            $message->error("Errore generico nell'inserimento");
		$_SESSION['message'] = $message;
		//Request::goToUrl('conferenzeStampa');
	}
}
$contentTpl->assign('articolo',$articolo);
?>
