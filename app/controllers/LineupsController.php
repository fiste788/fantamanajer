<?php

namespace Fantamanajer\Controllers;

use Fantamanajer\Models\Lineup;
use Fantamanajer\Models\Matchday;
use Fantamanajer\Models\Member;
use Fantamanajer\Models\Team;
use Fantamanajer\Models\View\MemberStats;
use FirePHP;
use Lib\FormException;

class LineupsController extends ApplicationController {

    public function show() {
        $filterTeam = $this->request->getParam('team_id', $_SESSION['team']->id);
        $filterMatchday = $this->request->getParam('matchday_id', $this->currentMatchday->getId());
        
        $team = Team::getById($filterTeam);
        $matchday = Matchday::getById($filterMatchday);
        
        $lineup = Lineup::getLast($filterTeam, $filterMatchday);
        $this->_showLineup($team,$matchday,$lineup);
    }

    public function build() {
        $filterTeam = $this->request->getParam('team_id', $_SESSION['team']->id);
        $filterMatchday = $this->request->getParam('matchday_id', $this->currentMatchday->getId());
        
        $a = $this->request->getPostParams();
        if(empty($a)) {
            $lineup = Lineup::getLast($filterTeam, $filterMatchday);
        } else {
            $lineup = new Lineup();
            $lineup->setTeamId($_SESSION['team']->id);
            $lineup->setMatchdayId($this->currentMatchday->id);
            $lineup->setModule($lineup->calcModule($this->request->getParam('regular')));
            $lineup->players = $lineup->getSchieramenti(array_merge($this->request->getParam('regular'), $this->request->getParam('notRegular')));
        }
        
        $this->_showLineup($filterTeam,$filterMatchday,$lineup);
    }
    
    protected function _showLineup(Team $team, Matchday $matchday, Lineup $lineup = NULL) {
        $module = explode('-','1-4-4-2');
        if($matchday->id == $this->currentMatchday->id) {
            $players = MemberStats::getByTeam($team);
        } else {
            $players = Member::getGiocatoriBySquadraAndGiornata($team->id,$matchday->id);
        }
        if ($lineup != NULL) {
            $module = explode('-',$lineup->getModule());
            if($lineup->getMatchdayId() != $this->currentMatchday->getId()) {
                $lineup = clone $lineup;
                $lineup->setMatchdayId($this->currentMatchday->getId());
                $lineup->setJolly(FALSE);
                $ids = array();
                foreach($lineup->players as $player) {
                    $ids[] = $player->id;
                }
            }
            if($matchday->id != $this->currentMatchday->id) {
                $players = MemberStats::getByIds($ids);
            }
        }
        //$module = array_combine(array("P","D","C","A"), array_map('intval', $module));
        $module = array_map('intval', $module);
        $playersRole = array();
        foreach($players as $player) {
            $playersRole[$player->role->id][$player->getId()] = $player;
        }
        $moduleAllowed = array(
            "1-4-4-2"=>"4-4-2",
            "1-3-5-2"=>"3-5-2",
            "1-3-4-3"=>"3-4-3",
            "1-4-5-1"=>"4-5-1",
            "1-4-3-3"=>"4-3-3",
            "1-5-4-1"=>"5-4-1",
            "1-5-3-2"=>"5-3-2"
        );
        
        $this->templates['content']->assign('usedJolly',Lineup::usedJolly($team,$this->currentMatchday));
        $this->templates['content']->assign('module',$module);
        $this->templates['content']->assign('moduleAllowed',$moduleAllowed);
        $this->templates['content']->assign('players',$playersRole);
        $this->templates['content']->assign('lineup', $lineup);
        $this->templates['content']->assign('team',$team);
        $this->templates['content']->assign('matchday',$matchday);
        $this->templates['content']->assign('captains',array("idCaptain","idVCaptani","idVVCaptain"));
        /*$this->templates['operation']->assign('squadre',  Utente::getByField('idLega',$_SESSION['legaView']));
        $this->templates['operation']->assign('squadra',$squadra);
        $this->templates['operation']->assign('giornata',$giornata);
        $this->templates['operation']->assign('formazioniPresenti',$formazioniPresenti);*/
    }
    
    public function update() {
        try {
            $filterTeam = $this->request->getParam('team_id', $_SESSION['team']->id);
            $filterMatchday = $this->request->getParam('matchday_id', $this->currentMatchday->getId());
            
            $team = Team::getById($filterTeam);
            $matchday = Matchday::getById($filterMatchday);
            if ($filterMatchday == $this->currentMatchday->id && $filterTeam == $_SESSION['team']->id) {
                $regular = $this->request->getParam('regulare');
                $notRegular = $this->request->getParam('notRegular');
                
                $lineup = new Lineup();
                //$formazione->setIdCapitano($this->request->getParam($name))
                FirePHP::getInstance()->log($lineup);
                $lineupOld = Lineup::getByTeamAndMatchday($filterTeam, $filterMatchday);
                if(!is_null($lineupOld)) {
                    $lineup->setId($lineupOld->getId());
                }
                
                $lineup->setIdGiornata($this->currentMatchday->id);
                $lineup->save(array('regular' => $regular, 'notRegular' => $notRegular));
                $this->setFlash(self::FLASH_SUCCESS,'Formazione caricata correttamente');
            }
        } catch(FormException $e) {
            $this->setFlash(self::FLASH_NOTICE, $e->getMessage());
        }
        $this->renderAction('lineups');
    }

}

 