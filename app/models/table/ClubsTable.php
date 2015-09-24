<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Club;
use Fantamanajer\Models\Member;
use Fantamanajer\Models\View\GiocatoreStatistiche;
use Lib\Database\Table;

abstract class ClubsTable extends Table {

    const TABLE_NAME = 'clubs';

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $partitive;

    /**
     *
     * @var string
     */
    public $determinant;

    public function __construct() {
        parent::__construct();
        $this->name = is_null($this->name) ? NULL : $this->getName();
        $this->partitive = is_null($this->partitive) ? NULL : $this->getPartitive();
        $this->determinant = is_null($this->determinant) ? NULL : $this->getDeterminant();
    }

    /**
     * 
     * @return text
     */
    public function getName() {
        return $this->name;
    }

    /**
     * 
     * @return text
     */
    public function getPartitive() {
        return $this->partitive;
    }

    /**
     * 
     * @return text
     */
    public function getDeterminant() {
        return $this->determinant;
    }

    /**
     * 
     * @param text $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * 
     * @param text $partitive
     */
    public function setPartitive($partitive) {
        $this->partitive = $partitive;
    }

    /**
     * 
     * @param text $determinant
     */
    public function setDeterminant($determinant) {
        $this->determinant = $determinant;
    }

        
    /**
     * Getter: giocatori
     * @return Member[]
     */
    public function getMembers() {
        if (empty($this->members)) {
            $this->members = Member::getByFields(array('idClub' => $this->getId()));
        }
        return $this->members;
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
     * @return Club[]|Club|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Club
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Club[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Club[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
