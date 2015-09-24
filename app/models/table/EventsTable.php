<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Event;
use Lib\Database\Table;

abstract class EventsTable extends Table {

    const TABLE_NAME = 'events';

     /**
     *
     * @var \DateTime
     */
    public $created_at;
    
        /**
     *
     * @var int
     */
    public $type;
    
     /**
     *
     * @var int
     */
    public $external;
    
    /**
     *
     * @var int
     */
    public $team_id;

    public function __construct() {
        parent::__construct();
        $this->created_at = is_null($this->created_at) ? NULL : $this->getCreatedAt();
        $this->type = is_null($this->type) ? NULL : $this->getType();
        $this->external = is_null($this->external) ? NULL : $this->getExternal();
        $this->team_id = is_null($this->team_id) ? NULL : $this->getTeamId();
    }

    /**
     * 
     * @return \DateTime
     */
    public function getCreatedAt() {
        return (is_a($this->created_at, "DateTime")) ? $this->created_at : new \DateTime($this->created_at);
    }

    /**
     * 
     * @return int
     */
    public function getType() {
        return (int) $this->type;
    }

    /**
     * 
     * @return int
     */
    public function getExternal() {
        return (int) $this->external;
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
     * @param \DateTime $created_at
     */
    public function setCreatedAt(\DateTime $created_at) {
        $this->created_at = is_a($created_at, "DateTime") ? $created_at : new \DateTime($created_at);
    }

    /**
     * 
     * @param int $type
     */
    public function setType($type) {
        $this->type = (int) $type;
    }

    /**
     * 
     * @param int $external
     */
    public function setExternal($external) {
        $this->external = (int) $external;
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
     * @return string
     */
    public function __toString() {
        return $this->getId();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Event[]|Event|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Event
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Event[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Event[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
