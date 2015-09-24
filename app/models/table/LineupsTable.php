<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Lineup;
use Fantamanajer\Models\Member;
use Fantamanajer\Models\Matchday;
use Fantamanajer\Models\Team;
use Fantamanajer\Models\View\GiocatoreStatistiche;
use Lib\Database\Table;

abstract class LineupsTable extends Table {

    const TABLE_NAME = 'lineups';

    /**
     *
     * @var string
     */
    public $module;

    /**
     *
     * @var int
     */
    public $captain_id;

    /**
     *
     * @var int
     */
    public $vcaptain_id;

    /**
     *
     * @var int
     */
    public $vvcaptain_id;

    /**
     *
     * @var boolean
     */
    public $jolly;
    
    /**
     *
     * @var int
     */
    public $matchday_id;
    
    /**
     *
     * @var int
     */
    public $team_id;
    
    public function __construct() {
        parent::__construct();
        $this->module = is_null($this->module) ? NULL : $this->getModule();
        $this->captain_id = is_null($this->captain_id) ? NULL : $this->getCaptainId();
        $this->vcaptain_id = is_null($this->vcaptain_id) ? NULL : $this->getVcaptainId();
        $this->vvcaptain_id = is_null($this->vvcaptain_id) ? NULL : $this->getVvcaptainId();
        $this->jolly = is_null($this->jolly) ? NULL : $this->getJolly();
        $this->matchday_id = is_null($this->matchday_id) ? NULL : $this->getMatchdayId();
        $this->team_id = is_null($this->team_id) ? NULL : $this->getTeamId();
    }

    /**
     * 
     * @return string
     */
    public function getModule() {
        return $this->module;
    }

    /**
     * 
     * @return int
     */
    public function getCaptainId() {
        return (int) $this->captain_id;
    }
    
    /**
     * 
     * @return Member
     */
    public function getCaptain() {
        if (empty($this->captain)) {
            $this->captain = GiocatoreStatistiche::getById($this->getCaptainId());
        }
        return $this->captain;
    }

    /**
     * 
     * @return int
     */
    public function getVcaptainId() {
        return (int) $this->vcaptain_id;
    }
    
    /**
     * 
     * @return Member
     */
    public function getVcaptain() {
        if (empty($this->vcaptain)) {
            $this->vcaptain = GiocatoreStatistiche::getById($this->getVcaptainId());
        }
        return $this->vcaptain;
    }

    /**
     * 
     * @return int
     */
    public function getVvcaptainId() {
        return (int) $this->vvcaptain_id;
    }
    
    /**
     * 
     * @return Member
     */
    public function getVvcaptain() {
        if (empty($this->vvcaptain)) {
            $this->vvcaptain = GiocatoreStatistiche::getById($this->getVvcaptainId());
        }
        return $this->vvcaptain;
    }

    /**
     * 
     * @return boolean
     */
    public function getJolly() {
        return (boolean) $this->jolly;
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
     * @return Matchday
     */
    public function getMatchday() {
        if (empty($this->matchday)) {
            $this->matchday = Matchday::getById($this->getMatchdayId());
        }
        return $this->matchday;
    }

    /**
     * 
     * @return int
     */
    public function getTeamId() {
        return $this->team_id;
    }
    
    /**
     * 
     * @return Team
     */
    public function getTeam() {
        if (empty($this->team)) {
            $this->team = Team::getById($this->getTeamId());
        }
        return $this->team;
    }

    /**
     * 
     * @param string $module
     */
    public function setModule($module) {
        $this->module = $module;
    }

    /**
     * 
     * @param int $captain_id
     */
    public function setCaptainId($captain_id) {
        $this->captain_id = (int) $captain_id;
    }

    /**
     * 
     * @param int $vcaptain_id
     */
    public function setVcaptainId($vcaptain_id) {
        $this->vcaptain_id = (int) $vcaptain_id;
    }

    /**
     * 
     * @param int $vvcaptain_id
     */
    public function setVvcaptainId($vvcaptain_id) {
        $this->vvcaptain_id = (int) $vvcaptain_id;
    }

    /**
     * 
     * @param type $jolly
     */
    public function setJolly($jolly) {
        $this->jolly = (boolean) $jolly;
    }

    /**
     * 
     * @param int $matchday_id
     */
    public function setMatchdayId($matchday_id) {
        $this->matchday_id = $matchday_id;
    }

    /**
     * 
     * @param int $team_id
     */
    public function setTeamId($team_id) {
        $this->team_id = $team_id;
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
     * @return Lineup[]|Lineup|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Lineup
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Lineup[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Lineup[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
