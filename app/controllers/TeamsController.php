<?php

namespace Fantamanajer\Controllers;

use Fantamanajer\Models as Models;
use FirePHP;
use Lib\FormException;

class TeamsController extends ApplicationController {

    public function index() {
        $appo_teams = Models\Team::getByField('championship_id',$_SESSION['league_view']);
        //$classifica = Models\Punteggio::getClassificaByGiornata($_SESSION['legaView'],$this->currentGiornata->getId());
        $classifica = NULL;
        $teams = array();
        if ($classifica != NULL) {
            foreach ($classifica as $key => $val) {
                $squadre[$key] = $appo_teams[$key];
                $squadre[$key]->giornateVinte = $val->giornateVinte;
            }
        } else {
            $teams = $appo_teams;
        }
        $this->templates['content']->assign('teams',$teams);
        //$this->templates['content']->assign('ultimaGiornata',Models\Punteggio::getGiornateWithPunt());
    }
    
    public function index_json() {
        $squadreAppo = Models\Utente::getByIdLegaLite($_SESSION['legaView']);
        echo json_encode($squadreAppo);
    }

    public function show() {
        if(($team = Models\View\TeamStats::getById($this->request->getParam('id'))) == FALSE) {
            $this->send404();
        }

        $teams = Models\Team::getByField('championship_id',$team->championship_id);
        $this->quickLinks->set('id', $teams, "");
        $this->title = $team->name;
        $this->templates['content']->assign('members',  Models\View\MemberStats::getByTeam($team)); //getByField('team_id',$team->getId()));
        $this->templates['content']->assign('team',$team);
        $this->templates['operation']->assign('teams',$teams);
    }
    
    public function build() {
        $filterLega = $_SESSION['idLega'];
        $this->templates['content']->assign('squadre', Models\Utente::getByIdLegaLite($filterLega));
        $this->templates['content']->assign('portieri',  Models\Giocatore::getFreePlayer('P',$filterLega));
        $this->templates['content']->assign('difensori',  Models\Giocatore::getFreePlayer('D',$filterLega));
        $this->templates['content']->assign('attaccanti',  Models\Giocatore::getFreePlayer('A',$filterLega));
        $this->templates['content']->assign('centrocampisti',  Models\Giocatore::getFreePlayer('C',$filterLega));
    }
    
    public function create() {
        try {
            FirePHP::getInstance()->log($this->request->getPostParams());
            $giocatori = $this->request->getParam('giocatore');
            $utente = $this->request->getParam('squadra');
            Models\Squadra::setSquadraGiocatoreByArray($_SESSION['idLega'], $giocatori, $utente);
            
            $this->redirectTo("crea_squadra");
        } catch(FormException $e) {
            $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
            $this->renderAction("crea_squadra");
        }

    }

}

 