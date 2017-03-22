<?php

namespace Fantamanajer\Controllers;

use Fantamanajer\Models as Models;

class TransfertsController extends ApplicationController {

    public function index() {
        $filterId = $this->request->getParam('team_id', $_SESSION['team']->id);
        
        $team = Models\Team::getById($filterId);
        $appo = Models\Transfert::getByField('team_id', $team->getId());
        $transferts = !is_null($appo) ? (is_array($appo) ? $appo : array($appo)) : array();
       
        foreach ($transferts as $val) {
            $val->getOldMember();
            $val->getNewMember();
        }
        
        $playersFree = Models\View\MemberStats::getFree(NULL, $this->currentChampionship);

        $transfered = Models\Member::getInactiveByTeam($team);
        $selection = Models\Selection::getByField('team_id', $team->getId());
        if (empty($selection)) {
            $selection = new Models\Selection();
        }
        if ($this->request->getParam('acquista') != NULL) {
            $selection->setNewMemberId($this->request->getParam('acquista'));
        }

        $this->noLayout = true;
        $this->templates['content']->assign('players', Models\View\MemberStats::getByTeam($team));
        $this->templates['content']->assign('freePlayers', $playersFree);
        $this->templates['content']->assign('filterId', $filterId);
        $this->templates['content']->assign('transferts', $transferts);
        $this->templates['content']->assign('selection', $selection);
        //$this->templates['operation']->assign('filterId', $filterId);
        //$this->templates['operation']->assign('elencoSquadre', Models\Utente::getByField('idLega', $_SESSION['legaView']));
    }

}

