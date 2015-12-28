<?php

namespace Fantamanajer\Models\View;

use Fantamanajer\Models\Team;
use Fantamanajer\Models\User;

class TeamStats extends Team {

    const TABLE_NAME = 'view_2_teams_stats';

     /**
     *
     * @var User
     */
    var $user;
    
    var $sum_present;
    var $sum_valued;
    var $avg_points;
    var $avg_rating;
    var $sum_goals;
    var $sum_goals_against;
    var $sum_assist;
    var $sum_yellow_card;
    var $sum_red_card;
    /*var $punteggioMax;
    var $punteggioMin;
    var $punteggioMed;
    var $giornateVinte;*/

    function __construct() {
        parent::__construct();
        $this->user = new User();
        $this->user->getFromObject($this);
        $this->avg_points = $this->getAvgPoints();
        $this->avg_rating = $this->getAvgRating();
        $this->sum_goals = $this->getSumGoals();
        $this->sum_goals_against = $this->getSumGoalsAgainst();
        $this->sum_assist = $this->getSumAssist();
        $this->sum_yellow_card = $this->getSumYellowCard();
        $this->sum_red_card = $this->getSumRedCard();
        /*$this->punteggioMax = is_null($this->punteggioMax) ? NULL : $this->getPunteggioMax();
        $this->punteggioMin = is_null($this->punteggioMin) ? NULL : $this->getPunteggioMin();
        $this->punteggioMed = is_null($this->punteggioMed) ? NULL : $this->getPunteggioMed();
        $this->giornateVinte = is_null($this->giornateVinte) ? NULL : $this->getGiornateVinte();*/
    }

    /**
     * 
     * @return double
     */
    public function getAvgPoints() {
        return (double) $this->avg_points;
    }

    /**
     * 
     * @return double
     */
    public function getAvgRating() {
        return (double) $this->avg_rating;
    }

    /**
     * 
     * @return int
     */
    public function getSumGoals() {
        return (int) $this->sum_goals;
    }

    /**
     * 
     * @return int
     */
    public function getSumGoalsAgainst() {
        return (int) $this->sum_goals_against;
    }

    /**
     * 
     * @return int
     */
    public function getSumAssist() {
        return (int) $this->sum_assist;
    }

    /**
     * 
     * @return int
     */
    public function getSumYellowCard() {
        return (int) $this->sum_yellow_card;
    }

    /**
     * 
     * @return int
     */
    public function getSumRedCard() {
        return (int) $this->sum_red_card;
    }

    /**
     * Getter: punteggioMin
     * @return Double
     */
    public function getPunteggioMin() {
        return (double) $this->punteggioMin;
    }

    /**
     * Getter: punteggioMed
     * @return Double
     */
    public function getPunteggioMed() {
        return (double) $this->punteggioMed;
    }

    /**
     * Getter: giornateVinte
     * @return Int
     */
    public function getGiornateVinte() {
        return (int) $this->giornateVinte;
    }

}

