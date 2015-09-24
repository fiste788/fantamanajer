<?php

namespace Fantamanajer\Models\Table;

use Lib\Database\Table;

abstract class SeasonsTable extends Table {

    const TABLE_NAME = 'seasons';

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var int
     */
    public $year;

    /**
     *
     * @var string
     */
    public $key_gazzetta;

    public function __construct() {
        parent::__construct();
        $this->name = is_null($this->name) ? NULL : $this->getName();
        $this->year = is_null($this->year) ? NULL : $this->getYear();
        $this->key_gazzetta = is_null($this->key_gazzetta) ? NULL : $this->getKeyGazzetta();
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
    public function getYear() {
        return (int) $this->year;
    }

    /**
     * 
     * @return string
     */
    public function getKeyGazzetta() {
        return $this->key_gazzetta;
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
     * @param int $year
     */
    public function setYear($year) {
        $this->year = $year;
    }

    /**
     * 
     * @param string $key_gazzetta
     */
    public function setKeyGazzetta($key_gazzetta) {
        $this->key_gazzetta = $key_gazzetta;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->id;
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Season[]|Season|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Season
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Season[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Season[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
