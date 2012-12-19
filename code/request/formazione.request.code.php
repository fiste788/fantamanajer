<?php

require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDBDIR . "formazione.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");
require_once(INCDBDIR . "giocatore.db.inc.php");
require_once(INCDBDIR . "punteggio.db.inc.php");

$filterUtente = Request::getInstance()->has('utente') ? Request::getInstance()->get('utente') : $_SESSION['idUtente'];
$filterGiornata = Request::getInstance()->has('giornata') ? Request::getInstance()->get('giornata') : GIORNATA;

if ($filterGiornata == GIORNATA && $filterUtente == $_SESSION['idUtente']) {
    $formazione = Formazione::getFormazioneBySquadraAndGiornata($filterUtente, $filterGiornata);
    if (!$formazione)
        $formazione = new Formazione();
    $titolari = Request::getInstance()->getRawData('post', 'titolari');
    $panchinari = Request::getInstance()->getRawData('post', 'panchinari');
    $formazione->setIdGiornata(GIORNATA);
    $formazione->setIdUtente($_SESSION['idUtente']);
    if (Request::getInstance()->get('C') != 0)
        $formazione->setIdCapitano(Request::getInstance()->get('C'));
    if (Request::getInstance()->get('VC') != 0)
        $formazione->setIdVCapitano(Request::getInstance()->get('VC'));
    if (Request::getInstance()->get('VVC') != 0)
        $formazione->setIdVVCapitano(Request::getInstance()->get('VVC'));
    $formazione->save(array('titolari' => $titolari, 'panchinari' => $panchinari));
    $message->success('Formazione caricata correttamente');
}
$contentTpl->assign('titolari', $titolari);
$contentTpl->assign('panchinari', $panchinari);
?>
