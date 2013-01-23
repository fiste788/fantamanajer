<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;

class GiocatoreController extends ApplicationController {

    public function show() {
        if (($giocatore = Models\Giocatore::getGiocatoreByIdWithStats($this->route['params']['id'], $_SESSION['legaView'])) == FALSE)
            Request::send404();

        $giocatore->getFoto();
        $giocatore->voti = $giocatore->getVoti();
        $pathFoto = PLAYERSDIR . $giocatore->id . '.jpg';
        $pathClub = CLUBSURL . $giocatore->idClub . '.png';
        if (!file_exists($pathFoto))
            $pathFoto = IMGSURL . 'no-photo.png';
        else
            $pathFoto = PLAYERSURL . $giocatore->id . '.jpg';

        if ($_SESSION['logged'] == TRUE) {
            if (!empty($giocatore->idUtente)) {  // carico giocatori della squadra
                $elencoGiocatori = Models\View\GiocatoreStatistiche::getByField('idUtente', $giocatore->idUtente);
                $dettaglioSquadra = Models\Utente::getById($giocatore->idUtente);
                $this->templates['contentTpl']->assign('idUtente', $giocatore->idUtente);
                $this->templates['contentTpl']->assign('label', $dettaglioSquadra->nomeSquadra);
                $this->templates['operationTpl']->assign('label', $dettaglioSquadra->nomeSquadra);
            } else {   // carico giocatori liberi
                $ruolo = $giocatore->ruolo;
                $elencoGiocatori = Models\Giocatore::getFreePlayer($ruolo, $_SESSION['datiLega']->id);
                $this->templates['operationTpl']->assign('label', $this->ruoli[$ruolo]->plurale . " liberi");
                $this->templates['contentTpl']->assign('label', $this->ruoli[$ruolo]->plurale . " liberi");
            }
        } else {   // carico giocatori del club
            $club = $giocatore->nomeClub;
            $elencoGiocatori = Models\Giocatore::getByField('idClub', $giocatore->idClub);
            $this->templates['operationTpl']->assign('label', $club);
            $this->templates['contentTpl']->assign('label', $club);
        }


        $this->quickLinks->set('id', $elencoGiocatori, "");
        $this->templates['contentTpl']->assign('giocatore', $giocatore);
        $this->templates['contentTpl']->assign('pathFoto', $pathFoto);
        $this->templates['contentTpl']->assign('pathClub', $pathClub);
        $this->templates['operationTpl']->assign('elencoGiocatori', $elencoGiocatori);

    }

    function free() {
        $defaultRuolo = $this->request->has('ruolo') ? $this->request->get('ruolo') : 'P';
        $defaultPartite = $this->request->has('partite') ? $this->request->get('partite') : (floor(($this->currentGiornata->id - 1) / 2) + 1);
        $defaultSufficenza = $this->request->has('sufficenza') ? $this->request->get('sufficenza') : 6;

        $freeplayer = Models\Giocatore::getFreePlayer($defaultRuolo,$_SESSION['legaView']);

        $this->templates['contentTpl']->assign('freeplayer',$freeplayer);
        $this->templates['contentTpl']->assign('defaultPartite',$defaultPartite);
        $this->templates['contentTpl']->assign('defaultSufficenza',$defaultSufficenza);
        $this->templates['operationTpl']->assign('validFilter',is_numeric($defaultSufficenza) && is_numeric($defaultPartite));
        $this->templates['operationTpl']->assign('ruolo',$defaultRuolo);
        $this->templates['operationTpl']->assign('defaultSufficenza',$defaultSufficenza);
        $this->templates['operationTpl']->assign('defaultPartite',$defaultPartite);
    }
}

?>