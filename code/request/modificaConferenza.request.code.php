<?php 
require_once(INCDBDIR . "articolo.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");

if(!$request->has('id') || ($request->has('id') && $request->get('id') == ""))
	$articolo = new Articolo();
elseif(($articolo = Articolo::getById($request->get('id'))) === FALSE)
	Request::send404();

if($request->get('submit') == 'Rimuovi'){
	if($articolo->delete()) {
		Evento::deleteEventoByIdExternalAndTipo($request->get('id'),Evento::CONFERENZASTAMPA);
		$message->success('Cancellazione effettuata con successo');
		$_SESSION['message'] = $message;
		Request::goToUrl('conferenzeStampa');
	} else
		$message->error("Errore nella cancellazione della conferenza");
} else {
    if($articolo->validate()) {
		$articolo->setIdUtente(3);
		$articolo->setIdGiornata(GIORNATA);
		$articolo->setIdLega(1);

		if(($id = $articolo->save()) != FALSE) {
		    if(is_null($articolo->getId())) {
			    $evento = new Evento();
			    $evento->setTipo(Evento::CONFERENZASTAMPA);
			    $evento->setData($articolo->getInsertDate());
			    $evento->setIdUtente($articolo->getIdUtente());
			    $evento->setIdLega($articolo->getIdLega());
			    $evento->setIdExternal($id);
				$evento->save();
			}
			$message->success("Inserimento completato con successo");
		} else
            $message->error("Errore generico nell'inserimento");
		$_SESSION['message'] = $message;
		Request::goToUrl('conferenzeStampa');
	}
}
$contentTpl->assign('articolo',$articolo);
?>
