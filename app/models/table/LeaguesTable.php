<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\League;
use Lib\Database\Table;

abstract class LeaguesTable extends Table {

    const TABLE_NAME = "leagues";

    /**
     *
     * @var string
     */
    public $name;

    public function __construct() {
        parent::__construct();
        $this->name = is_null($this->name) ? NULL : $this->getName();
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
     * @param type $name
     */
    public function setName($name) {
        $this->name = $name;
    }
        
    /**
     * tostring
     * @return string
     */
    public function __toString() {
        return $this->getNome();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return League[]|League|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return League
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return League[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return League[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
