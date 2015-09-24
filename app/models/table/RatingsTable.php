<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Member;
use Fantamanajer\Models\Matchday;
use Fantamanajer\Models\View\GiocatoreStatistiche;
use Fantamanajer\Models\Rating;
use Lib\Database\Table;

abstract class RatingsTable extends Table {

    const TABLE_NAME = "ratings";

    /**
     *
     * @var int
     */
    public $valued;

    /**
     *
     * @var float
     */
    public $points;

    /**
     *
     * @var float
     */
    public $rating;

    /**
     *
     * @var int
     */
    public $goals;

    /**
     *
     * @var int
     */
    public $goals_against;

    /**
     *
     * @var int
     */
    public $goals_victory;

    /**
     *
     * @var int
     */
    public $goals_tie;

    /**
     *
     * @var int
     */
    public $assist;

    /**
     *
     * @var bool
     */
    public $yellow_card;

    /**
     *
     * @var bool
     */
    public $red_card;

    /**
     *
     * @var int
     */
    public $penalities_scored;

    /**
     *
     * @var int
     */
    public $penalities_taken;

    /**
     *
     * @var boolean
     */
    public $present;

    /**
     *
     * @var boolean
     */
    public $regular;

    /**
     *
     * @var int
     */
    public $quotation;
    
    /**
     *
     * @var int 
     */
    public $member_id;
    
    /**
     *
     * @var int 
     */
    public $matchday_id;

    public function __construct() {
        parent::__construct();
        $this->valuated = is_null($this->valuated) ? NULL : $this->isValuated();
        $this->points = is_null($this->points) ? NULL : $this->getPoints();
        $this->rating = is_null($this->rating) ? NULL : $this->getRating();
        $this->goals = is_null($this->goals) ? NULL : $this->getGoals();
        $this->goals_against = is_null($this->goals_taken) ? NULL : $this->getGoalsTaken();
        $this->goals_victory = is_null($this->goals_victory) ? NULL : $this->getGoalsVictory();
        $this->goals_tie = is_null($this->goals_tie) ? NUL : $this->getGoalsTie();
        $this->assist = is_null($this->assist) ? NULL : $this->getAssist();
        $this->yellow_card = is_null($this->yellow_card) ? NULL : $this->isYellowCard();
        $this->red_card = is_null($this->red_card) ? NULL : $this->isRedCard();
        $this->penalities_scored = is_null($this->penalities_scored) ? NULL : $this->getPenalitiesScored();
        $this->penalities_taken = is_null($this->penalities_taken) ? NULL : $this->getPenalitiesTaken();
        $this->present = is_null($this->present) ? NULL : $this->isPresent();
        $this->regular = is_null($this->regular) ? NULL : $this->isRegular();
        $this->quotation = is_null($this->quotation) ? NULL : $this->getQuotation();
        $this->member_id = is_null($this->member_id) ? NULL : $this->getMemberId();
        $this->matchday_id = is_null($this->matchday_id) ? NULL : $this->getMatchdayId();
    }

    /**
     * 
     * @return boolean
     */
    public function isValued() {
        return (boolean) $this->valued;
    }

    /**
     * 
     * @return float
     */
    public function getPoints() {
        return (float) $this->points;
    }

    /**
     * 
     * @return float
     */
    public function getRating() {
        return (float) $this->rating;
    }

    /**
     * 
     * @return int
     */
    public function getGoals() {
        return (int) $this->goals;
    }

    /**
     * 
     * @return int
     */
    public function getGoalsAgainst() {
        return (int) $this->goals_against;
    }

    /**
     * 
     * @return int
     */
    public function getGoalsVictory() {
        return (int) $this->goals_victory;
    }

    /**
     * 
     * @return int
     */
    public function getGoalsTie() {
        return (int) $this->goals_tie;
    }

    /**
     * 
     * @return int
     */
    public function getAssist() {
        return (int) $this->assist;
    }

    /**
     * 
     * @return boolean
     */
    public function isYellowCard() {
        return (boolean) $this->yellow_card;
    }

    /**
     * 
     * @return boolean
     */
    public function getRedCard() {
        return (boolean) $this->red_card;
    }

    /**
     * 
     * @return int
     */
    public function getPenalitiesScored() {
        return (int) $this->penalities_scored;
    }

    /**
     * 
     * @return int
     */
    public function getPenalitiesTaken() {
        return (int) $this->penalities_taken;
    }

    /**
     * 
     * @return boolean
     */
    public function isPresent() {
        return (boolean) $this->present;
    }

    /**
     * 
     * @return boolean
     */
    public function isRegular() {
        return (boolean) $this->regular;
    }

    /**
     * 
     * @return int
     */
    public function getQuotation() {
        return (int) $this->quotation;
    }

    /**
     * 
     * @return int
     */
    public function getMemberId() {
        return (int) $this->member_id;
    }

    /**
     * 
     * @return int
     */
    public function getMatchdayId() {
        return (int) $this->matchday_id;
    }

    /**
     * 
     * @param boolean $valued
     */
    public function setValued($valued) {
        $this->valued = (boolean) $valued;
    }

    /**
     * 
     * @param float $points
     */
    public function setPoints($points) {
        $this->points = (float) $points;
    }

    /**
     * 
     * @param float $rating
     */
    public function setRating($rating) {
        $this->rating = (float) $rating;
    }

    /**
     * 
     * @param int $goals
     */
    public function setGoals($goals) {
        $this->goals = (int) $goals;
    }

    /**
     * 
     * @param int $goals_against
     */
    public function setGoalsAgainst($goals_against) {
        $this->goals_against = (int) $goals_against;
    }

    /**
     * 
     * @param int $goals_victory
     */
    public function setGoalsVictory($goals_victory) {
        $this->goals_victory = (int) $goals_victory;
    }

    /**
     * 
     * @param int $goals_tie
     */
    public function setGoalsTie($goals_tie) {
        $this->goals_tie = (int) $goals_tie;
    }

    /**
     * 
     * @param int $assist
     */
    public function setAssist($assist) {
        $this->assist = (int) $assist;
    }

    /**
     * 
     * @param boolean $yellow_card
     */
    public function setYellowCard($yellow_card) {
        $this->yellow_card = (boolean) $yellow_card;
    }

    /**
     * 
     * @param boolean $red_card
     */
    public function setRedCard($red_card) {
        $this->red_card = (boolean) $red_card;
    }

    /**
     * 
     * @param int $penalities_scored
     */
    public function setPenalitiesScored($penalities_scored) {
        $this->penalities_scored = (int) $penalities_scored;
    }

    /**
     * 
     * @param int $penalities_taken
     */
    public function setPenalitiesTaken($penalities_taken) {
        $this->penalities_taken = $penalities_taken;
    }

    /**
     * 
     * @param boolean $present
     */
    public function setPresent($present) {
        $this->present = (boolean) $present;
    }

    /**
     * 
     * @param boolean $regular
     */
    public function setRegular($regular) {
        $this->regular = (boolean) $regular;
    }

    /**
     * 
     * @param int $quotation
     */
    public function setQuotation($quotation) {
        $this->quotation = (int) $quotation;
    }

    /**
     * 
     * @param int $member_id
     */
    public function setMemberId($member_id) {
        $this->member_id = (int) $member_id;
    }

    /**
     * 
     * @param int $matchday_id
     */
    public function setMatchdayId($matchday_id) {
        $this->matchday_id = (int) $matchday_id;
    }
        
    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getId();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Rating[]|Rating|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Rating
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Rating[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Rating[]
     */
    public static function getList() {
        return parent::getList();
    }
}

 
