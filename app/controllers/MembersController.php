<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;

class MembersController extends ApplicationController {

    public function show() {
        if (($member = Models\View\MemberStats::getById($this->request->getParam('id'))) == FALSE) {
            $this->send404();
        }
        $this->title = $member->player;
        $member->ratings = $member->getRatings($this->currentSeason);
        $pathPhoto = PLAYERSDIR . $member->code_gazzetta . '.jpg';
        $pathClub = CLUBSURL . $member->club_id . '.png';
        if (!file_exists($pathPhoto)) {
            $pathPhoto = IMGSURL . 'no-photo.png';
        } else {
            $pathPhoto = PLAYERSURL . $member->code_gazzetta . '.jpg';
        }
/*
        if ($_SESSION['logged'] == TRUE) {
            if (!empty($giocatore->idUtente)) {  // carico giocatori della squadra
                $elencoGiocatori = Models\View\GiocatoreStatistiche::getByField('idUtente', $giocatore->idUtente);
                $dettaglioSquadra = Models\Utente::getById($giocatore->idUtente);
                $this->templates['content']->assign('idUtente', $giocatore->idUtente);
                $this->templates['content']->assign('label', $dettaglioSquadra->nomeSquadra);
                $this->templates['operation']->assign('label', $dettaglioSquadra->nomeSquadra);
            } else {   // carico giocatori liberi
                $ruolo = $giocatore->ruolo;
                $elencoGiocatori = Models\Giocatore::getFreePlayer($ruolo, $_SESSION['datiLega']->id);
                $this->templates['operation']->assign('label', $this->ruoli[$ruolo]->plurale . " liberi");
                $this->templates['content']->assign('label', $this->ruoli[$ruolo]->plurale . " liberi");
            }
        } else {   // carico giocatori del club
            $club = $giocatore->nomeClub;
            $elencoGiocatori = Models\Giocatore::getByField('idClub', $giocatore->idClub);
            $this->templates['operation']->assign('label', $club);
            $this->templates['content']->assign('label', $club);
        }
*/

        //$this->quickLinks->set('id', $elencoGiocatori, "");
        $this->templates['content']->assign('member', $member);
        $this->templates['content']->assign('pathPhoto', $pathPhoto);
        $this->templates['content']->assign('pathClub', $pathClub);
        //$this->templates['operation']->assign('elencoGiocatori', $elencoGiocatori);

    }

    function free() {
        $defaultRole = $this->request->getParam('role', 1);
        $defaultMatch = $this->request->getParam('match', (floor(($this->currentMatchday->number - 1) / 2) + 1));
        $defaultEnough = $this->request->getParam('enough', 6);

        $role = Models\Role::getById($defaultRole);
        $freePlayers = Models\View\MemberStats::getFree($role, $this->currentChampionship);

        $this->templates['content']->assign('freePlayers',$freePlayers);
        $this->templates['content']->assign('defaultMatch',$defaultMatch);
        $this->templates['content']->assign('defaultEnough',$defaultEnough);
        $this->templates['content']->assign('role',$role);
        $this->templates['content']->assign('roles',Models\Role::getList());
        $this->templates['content']->assign('validFilter',is_numeric($defaultEnough) && is_numeric($defaultMatch));
        $this->templates['operation']->assign('role',$role);
        $this->templates['operation']->assign('defaultEnough',$defaultEnough);
        $this->templates['operation']->assign('defaultMatch',$defaultMatch);
    }
}

 