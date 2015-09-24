<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Matchday;
use Fantamanajer\Models\Season;
use Lib\Database\Table;

abstract class MatchdaysTable extends Table {

    const TABLE_NAME = "matchdays";

    /**
     *
     * @var int
     */
    public $number;
    
    /**
     *
     * @var \DateTime
     */
    public $date;
    
    /**
     *
     * @var int
     */
    public $season_id;

    public function __construct() {
        parent::__construct();
        $this->date = is_null($this->date) ? NULL : $this->getDate();
    }

    /**
     * 
     * @return int
     */
    public function getNumber() {
        return (int) $this->number;
    }
    
    /**
     * 
     * @return \DateTime
     */
    public function getDate() {
        return (is_a($this->date, "DateTime")) ? $this->date : new \DateTime($this->date);
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
     * @param int $number
     */
    public function setNumber($number) {
        $this->number = (int) $number;
    }
    
    /**
     * 
     * @param \DateTime $date
     */
    public function setDate($date) {
        $this->date = is_a($date, "DateTime") ? $date : new DateTime($date);
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
     * @return string
     */
    public function __toString() {
        return $this->getDate()->format(DATE_W3C);
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Matchday[]|Matchday|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Matchday
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int $ids
     * @return Matchday[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Matchday[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
