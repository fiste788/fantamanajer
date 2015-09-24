<?php

namespace Fantamanajer\Models\Table;

abstract class RolesTable extends Table {

    const TABLE_NAME = 'roles';

    /**
     *
     * @var string
     */
    public $singolar;

    /**
     *
     * @var string
     */
    public $plural;

    /**
     *
     * @var string
     */
    public $abbreviation;

    public function __construct() {
        parent::__construct();
        $this->singolar = is_null($this->singolar) ? NULL : $this->getSingolar();
        $this->plural = is_null($this->plural) ? NULL : $this->getPlural();
        $this->abbreviation = is_null($this->abbreviation) ? NULL : $this->getAbbreviation();
    }

    /**
     * 
     * @return string
     */
    public function getSingolar() {
        return $this->singolar;
    }

    /**
     * 
     * @return string
     */
    public function getPlural() {
        return $this->plural;
    }

    /**
     * 
     * @return string
     */
    public function getAbbreviation() {
        return $this->abbrevition;
    }

    /**
     * 
     * @param string $singolar
     */
    public function setSingolar($singolar) {
        $this->singolar = $singolar;
    }

    /**
     * 
     * @param string $plural
     */
    public function setPlural($plural) {
        $this->plural = $plural;
    }

    /**
     * 
     * @param string $abbreviation
     */
    public function setAbbreviation($abbreviation) {
        $this->abbreviation = $abbreviation;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getSingolar();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Role[]|Role|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Role
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Role[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Role[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 