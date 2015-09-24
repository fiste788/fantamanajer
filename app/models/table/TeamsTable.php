<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Championship;
use Fantamanajer\Models\Team;
use Lib\Database\Table;

abstract class TeamsTable extends Table {

    const TABLE_NAME = "teams";

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $championship_id;

    public function __construct() {
        parent::__construct();
        $this->name = is_null($this->name) ? NULL : $this->getName();
        $this->user_id = is_null($this->user_id) ? NULL : $this->getUserId();
        $this->championship_id = is_null($this->championship_id) ? NULL : $this->getChampionshipId();
    }

    /**
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * 
     * @return int
     */
    public function getUserId() {
        return (int) $this->user_id;
    }
    
    /**
     * 
     * @return User
     */
    public function getUser() {
        if (empty($this->user)) {
            $this->user = User::getById($this->getUserId());
        }
        return $this->user;
    }

    /**
     * 
     * @return int
     */
    public function getChampionshipId() {
        return (int) $this->championship_id;
    }
    
    /**
     * 
     * @return Championship
     */
    public function getChampionship() {
        if (empty($this->championship)) {
            $this->championship = Championship::getById($this->getChampionshipId());
        }
        return $this->championship;
    }

    /**
     * 
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * 
     * @param int $user_id
     */
    public function setUserId($user_id) {
        $this->user_id = (int) $user_id;
    }

    /**
     * 
     * @param int $championship_id
     */
    public function setChampionshipId($championship_id) {
        $this->championship_id = (int) $championship_id;
    }
 
    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getName();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Team[]|Team|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Team
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Team[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Team[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
