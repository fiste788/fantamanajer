<?php
require_once(INCDBDIR . "articolo.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");

if (!Request::getInstance()->has('id') || (Request::getInstance()->has('id') && Request::getInstance()->get('id') == ""))
    $articolo = new Articolo();
elseif (($articolo = Articolo::getById(Request::getInstance()->get('id'))) === FALSE)
    Request::send404();

if (Request::getInstance()->get('submit') == 'Rimuovi') {
    $articolo->delete();
    $message->success('Cancellazione effettuata con successo');
} else {
    $articolo->setIdUtente($_SESSION['idUtente']);
    $articolo->setIdGiornata(GIORNATA);
    $articolo->setIdLega(1);
    $articolo->setDataCreazione('now');
    $articolo->save();
    $message->success("Inserimento completato con successo");
}
$_SESSION['message'] = $message;
Request::goToUrl('conferenzeStampa');

?>
