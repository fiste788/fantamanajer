<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Championship;
use Fantamanajer\Models\League;
use Fantamanajer\Models\Season;
use Lib\Database\Table;

abstract class ChampionshipsTable extends Table {

    const TABLE_NAME = "championships";

    /**
     *
     * @var boolean
     */
    public $captain;

    /**
     *
     * @var int
     */
    public $number_transfert;

    /**
     *
     * @var int
     */
    public $number_selection;

    /**
     *
     * @var int
     */
    public $minute_lineup;

    /**
     *
     * @var int
     */
    public $points_missed_lineup;

    /**
     *
     * @var boolean
     */
    public $captain_missed_lineup;

    /**
     *
     * @var boolean
     */
    public $jolly;
    
    /**
     *
     * @var int 
     */
    public $season_id;

    /**
     *
     * @var int
     */
    public $league_id;
    
    public function __construct() {
        $this->captain = is_null($this->captain) ? NULL : $this->isCaptain();
        $this->number_transfert = is_null($this->number_transfert) ? NULL : $this->getNumberTransfert();
        $this->number_selection = is_null($this->number_selection) ? NULL : $this->getNumberSelection();
        $this->minute_lineup = is_null($this->minute_lineup) ? NULL : $this->getMinuteLineup();
        $this->points_missed_lineup = is_null($this->points_missed_lineup) ? NULL : $this->getPointsMissedLineup();
        $this->captain_missed_lineup = is_null($this->captain_missed_lineup) ? NULL : $this->isCaptainMissedLineup();
        $this->jolly = is_null($this->jolly) ? NULL : $this->isJolly();
        $this->season_id = is_null($this->season_id) ? NULL : $this->getSeasonId();
        $this->league_id = is_null($this->league_id) ? NULL : $this->getLeagueId();
        parent::__construct();
    }

    /**
     * 
     * @return boolean
     */
    public function isCaptain() {
        return (boolean) $this->captain;
    }

    /**
     * 
     * @return int
     */
    public function getNumberTransfert() {
        return (int) $this->numberTransfert;
    }

    /**
     * 
     * @return int
     */
    public function getNumberSelection() {
        return (int) $this->number_selection;
    }

    /**
     * 
     * @return int
     */
    public function getMinuteLineup() {
        return (int) $this->minute_lineup;
    }

    /**
     * 
     * @return int
     */
    public function getPointsMissedLineup() {
        return (int) $this->points_missed_lineup;
    }

    /**
     * 
     * @return boolean
     */
    public function isCaptainMissedLineup() {
        return (boolean) $this->captain_missed_lineup;
    }

    /**
     * 
     * @return boolean
     */
    public function isJolly() {
        return (boolean) $this->jolly;
    }

    /**
     * 
     * @return int
     */
    public function getSeasonId() {
        return (int) $this->season_id;
    }
    
    /**
     * 
     * @return Season
     */
    public function getSeason() {
        if (empty($this->season)) {
            $this->season = Season::getById($this->getSeasonId());
        }
        return $this->season;
    }

    /**
     * 
     * @return int
     */
    public function getLeagueId() {
        return (int) $this->league_id;
    }
    
    /**
     * 
     * @return League
     */
    public function getLeague() {
        if (empty($this->league)) {
            $this->league = League::getById($this->getLeagueId());
        }
        return $this->league;
    }

    /**
     * 
     * @param boolean $captain
     */
    public function setCaptain($captain) {
        $this->captain = (boolean) $captain;
    }

    /**
     * 
     * @param int $number_transfert
     */
    public function setNumberTransfert($number_transfert) {
        $this->number_transfert = (int) $number_transfert;
    }

    /**
     * 
     * @param int $number_selection
     */
    public function setNumberSelection($number_selection) {
        $this->number_selection = (int) $number_selection;
    }

    /**
     * 
     * @param int $minute_lineup
     */
    public function setMinuteLineup($minute_lineup) {
        $this->minute_lineup = (int) $minute_lineup;
    }

    /**
     * 
     * @param int $points_missed_lineup
     */
    public function setPointsMissedLineup($points_missed_lineup) {
        $this->points_missed_lineup = (int) $points_missed_lineup;
    }

    /**
     * 
     * @param boolean $captain_missed_lineup
     */
    public function setCaptainMissedLineup($captain_missed_lineup) {
        $this->captain_missed_lineup = (boolean) $captain_missed_lineup;
    }

    /**
     * 
     * @param boolean $jolly
     */
    public function setJolly($jolly) {
        $this->jolly = (boolean) $jolly;
    }

    /**
     * 
     * @param int $season_id
     */
    public function setSeasonId($season_id) {
        $this->season_id = (int) $season_id;
    }
    
    /**
     * 
     * @param Season $season
     */
    public function setSeason(Season $season) {
        $this->season_id = $season->getId();
        $this->season = $season;
    }

    /**
     * 
     * @param int $league_id
     */
    public function setLeagueId($league_id) {
        $this->league_id = (int) $league_id;
    }

    /**
     * 
     * @param League $league
     */
    public function setLeague(League $league) {
        $this->league_id = $league->getId();
        $this->league = $league;
    }
        
    /**
     * tostring
     * @return string
     */
    public function __toString() {
        return $this->getId();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Championship[]|Championship|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Championship
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Championship[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Championship[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
