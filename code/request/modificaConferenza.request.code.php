<?php

require_once(INCDBDIR . "articolo.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");

if (!$request->has('id') || ($request->has('id') && $request->get('id') == ""))
    $articolo = new Articolo();
elseif (($articolo = Articolo::getById($request->get('id'))) === FALSE)
    Request::send404();

try {
    if ($request->get('submit') == 'Rimuovi') {
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
} catch (FormException $fe) {
    $message->warning($fe->getMessage());
} catch (PDOException $e) {
    $message->error($e->getMessage());
}
$contentTpl->assign('articolo', $articolo);
?>
