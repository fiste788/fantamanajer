<?php

namespace Fantamanajer\Models\Table;

abstract class GiornataTable extends \Lib\Database\Table {

    const TABLE_NAME = "giornata";

    /**
     *
     * @var DateTime
     */
    public $data;

    public function __construct() {
        parent::__construct();
        $this->data = is_null($this->data) ? NULL : $this->getData();
    }

    /**
     * Setter: data
     * @param DateTime $data
     * @return void
     */
    public function setData($data) {
        $this->data = is_a($data, "DateTime") ? $data : new \DateTime($data);
    }

    /**
     * Getter: data
     * @return String
     */
    public function getData() {
        return (is_a($this->data, "DateTime")) ? $this->data : new \DateTime($this->data);
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getData();
    }

    /**
     *
     * @param type $key
     * @param type $value
     * @return Giornata[]|Giornata|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Giornata
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Giornata[]|NULL
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Giornata[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
