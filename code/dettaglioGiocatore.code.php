<?php

require_once(VIEWDIR . 'GiocatoreStatistiche.view.db.inc.php');
require_once(INCDBDIR . 'club.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'voto.db.inc.php');

//$dettaglio = GiocatoreStatistiche::getById($filterId);
if (($giocatore = Giocatore::getGiocatoreByIdWithStats(Request::getInstance()->get('id'), $_SESSION['legaView'])) == FALSE)
    Request::send404();

$giocatore->voti = $giocatore->getVoti();
$firePHP->log($giocatore);
$pathFoto = PLAYERSDIR . $giocatore->id . '.jpg';
$pathClub = CLUBSURL . $giocatore->idClub . '.png';
if (!file_exists($pathFoto))
    $pathFoto = IMGSURL . 'no-photo.png';
else
    $pathFoto = PLAYERSURL . $giocatore->id . '.jpg';

if ($_SESSION['logged'] == TRUE) {
    if (!empty($giocatore->idUtente)) {  // carico giocatori della squadra
        $squadra = $giocatore->idUtente;
        $elencoGiocatori = GiocatoreStatistiche::getByField('idUtente', $squadra);
        $contentTpl->assign('idUtente', $squadra);
        $dettaglioSquadra = Utente::getById($squadra);
        $operationTpl->assign('label', $dettaglioSquadra->nomeSquadra);
        $contentTpl->assign('label', $dettaglioSquadra->nomeSquadra);
    } else {   // carico giocatori liberi
        $ruolo = $giocatore->ruolo;
        $elencoGiocatori = Giocatore::getFreePlayer($ruolo, $_SESSION['datiLega']->id);
        $operationTpl->assign('label', $ruoli[$ruolo]->plurale . " liberi");
        $contentTpl->assign('label', $ruoli[$ruolo]->plurale . " liberi");
    }
} else {   // carico giocatori del club
    $club = $giocatore->nomeClub;
    $elencoGiocatori = Giocatore::getByField('idClub', $giocatore->idClub);
    $operationTpl->assign('label', $club);
    $contentTpl->assign('label', $club);
}

$quickLinks->set('giocatore', $elencoGiocatori, "");
$contentTpl->assign('giocatore', $giocatore);
$contentTpl->assign('pathFoto', $pathFoto);
$contentTpl->assign('pathClub', $pathClub);
$operationTpl->assign('elencoGiocatori', $elencoGiocatori);
?>