<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Player;
use Lib\Database\Table;

abstract class PlayersTable extends Table {

    const TABLE_NAME = "players";

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $surname;

    public function __construct() {
        parent::__construct();
        $this->name = is_null($this->name) ? NULL : $this->getName();
        $this->surname = is_null($this->surname) ? NULL : $this->getSurname();
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
     * @return string
     */
    public function getSurname() {
        return $this->surname;
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
     * @param string $surname
     */
    public function setSurname($surname) {
        $this->surname = $surname;
    }
    
    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getSurname() . " " . $this->getName();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Player[]|Player|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Player
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Player[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Player[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
