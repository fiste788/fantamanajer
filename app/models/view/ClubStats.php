<?php

namespace Fantamanajer\Models\View;

use Fantamanajer\Models\Club;

class ClubStats extends Club {

    const TABLE_NAME = "view_2_clubs_stats";

    var $sum_present;
    var $sum_valued;
    var $avg_points;
    var $avg_rating;
    var $sum_goals;
    var $sum_goals_against;
    var $sum_assist;
    var $sum_yellow_card;
    var $sum_red_card;
    var $quotation;

    function __construct() {
        parent::__construct();
        $this->avg_points = $this->getAvgPoints();
        $this->avg_rating = $this->getAvgRating();
        $this->sum_goals = $this->getSumGoals();
        $this->sum_goals_against = $this->getSumGoalsAgainst();
        $this->sum_assist = $this->getSumAssist();
        $this->sum_yellow_card = $this->getSumYellowCard();
        $this->sum_red_card = $this->getSumRedCard();
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
}
