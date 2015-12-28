<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Score;
use Lib\Database\Table;

abstract class ScoresTable extends Table {

    const TABLE_NAME = 'scores';

    /**
     *
     * @var float
     */
    public $points;

    /**
     *
     * @var float
     */
    public $real_points;
    
    /**
     *
     * @var float
     */
    public $penality_points;

    /**
     *
     * @var string
     */
    public $penality;

    /**
     *
     * @var int
     */
    public $team_id;

    /**
     *
     * @var int
     */
    public $matchday_id;

    public function __construct() {
        parent::__construct();
        $this->points = is_null($this->points) ? NULL : $this->getPoints();
        $this->real_points = is_null($this->real_points) ? NULL : $this->getRealPoints();
        $this->penality_points = is_null($this->penality_points) ? NULL : $this->getPenalityPoints();
        $this->penality = is_null($this->penality) ? NULL : $this->getPenality();
        $this->team_id = is_null($this->team_id) ? NULL : $this->getTeamId();
        $this->matchday_id = is_null($this->matchday_id) ? NULL : $this->getMatchdayId();
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
    public function getRealPoints() {
        return (float) $this->real_points;
    }

    /**
     * 
     * @return float
     */
    public function getPenalityPoints() {
        return (float) $this->penality_points;
    }

    /**
     * 
     * @return string
     */
    public function getPenality() {
        return $this->penality;
    }

    /**
     * 
     * @return int
     */
    public function getTeamId() {
        return (int) $this->team_id;
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
     * @param float $points
     */
    public function setPoints($points) {
        $this->points = (float) $points;
    }

    /**
     * 
     * @param float $real_points
     */
    public function setRealPoints($real_points) {
        $this->real_points = (float) $real_points;
    }

    /**
     * 
     * @param float $penality_points
     */
    public function setPenalityPoints($penality_points) {
        $this->penality_points = (float) $penality_points;
    }

    /**
     * 
     * @param string $penality
     */
    public function setPenality($penality) {
        $this->penality = $penality;
    }

    /**
     * 
     * @param int $team_id
     */
    public function setTeamId($team_id) {
        $this->team_id = (int) $team_id;
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
        return (string) $this->getPoints();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Score[]|Score|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Score
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Score[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Score[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
