<?php

namespace Fantamanajer\Controllers;
use \Fantamanajer\Models as Models;

class ScoresController extends ApplicationController {

    public function index() {
        $maxMatchday = Models\Score::getMatchdayWithScores();
        $filterMatchday = $this->request->getParam('matchday_id',$maxMatchday);

        $this->request->setParam('matchday_id', $filterMatchday);
        $matchday = Models\Matchday::getById($filterMatchday);
        $ranking = Models\Score::getRankingByMatchday($this->currentChampionship, $matchday, TRUE);
        //$ranking_details = Models\Score::getAllByMatchday($matchday,$this->currentChampionship);
        $teams = Models\Team::getByField('championship_id', $this->currentChampionship->getId());

        $matchdays = array();
        for ($i = 1; $i <= $maxMatchday; $i++) {
            $matchdays[$i] = $i;
        }

        //$this->quickLinks->set('giornata',$giornate,"");
        $this->templates['content']->assign('matchdays',$matchdays);
        $this->templates['content']->assign('ranking',$ranking);
        //$this->templates['content']->assign('ranking_details',$ranking_details);
        //$this->templates['content']->assign('penalità',Models\Punteggio::getPenalitàByLega($_SESSION['legaView']));
        $this->templates['content']->assign('teams',$teams);
        //$this->templates['content']->assign('posizioni',Models\Punteggio::getPosClassificaGiornata($_SESSION['legaView']));

        //$this->templates['operation']->assign('getGiornata',$filterGiornata);
        //$this->templates['operation']->assign('giornate',$maxGiornate);
    }

    public function show() {
        $filterMatchday = $this->request->getParam('matchday_id',  Models\Score::getMatchdayWithScores());
        $filterTeam =  $this->request->getParam('team_id');

        //die(var_dump($filterMatchday));
        $matchday = Models\Matchday::getById($filterMatchday);
        //die(var_dump($matchday));
        $team = Models\Team::getById($filterTeam);
        $details = Models\View\RatingDetails::getByMatchdayAndTeam($matchday,$team);
        //$formazione = Models\Lineup::getFormazioneBySquadraAndGiornata($filterSquadra,$filterGiornata);
        /*if($dettaglio == FALSE && $formazione == FALSE)
            Lib\Request::send404();
*/
        
        //$utente = Models\Utente::getById($filterSquadra);
        $maxMatchday = Models\Score::getMatchdayWithScores();
        for($i = 1;$i <= $maxMatchday ;$i++) {
            $matchdays[$i] = $i;
        }

        if ($details != FALSE) {
            $regulars = array_splice($details, 0, 11);
        } else {
            $regulars = FALSE;
        }
        
        $this->noLayout = $this->request->isXmlHttpRequest();
        

        //$this->quickLinks->set('giornata',$giornate,"",array('squadra'=>$filterSquadra));
        //\FirePHP::getInstance()->log($utente->getPunteggioByGiornata($filterSquadra));
        //$this->templates['content']->assign('media',$squadraDett->punteggioMed);
        $this->templates['content']->assign('score',  Models\Score::getByTeamAndMatchday($team,$matchday));
        $this->templates['content']->assign('regular',$regulars);
        $this->templates['content']->assign('notRegular',$details);
        //$this->templates['content']->assign('penality',  Models\Score::getPenalitàBySquadraAndGiornata($filterSquadra,$filterGiornata));
        //$this->templates['content']->assign('squadraDett',$utente);
        //$this->templates['operation']->assign('squadre',$this->currentLega->getUtenti());
        //$this->templates['operation']->assign('giornate',$giornate);
    }
}

 