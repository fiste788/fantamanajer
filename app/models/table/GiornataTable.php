<?php

namespace Fantamanajer\Models\Table;

use DateTime;
use Fantamanajer\Models\Giornata;
use Lib\Database\Table;

abstract class GiornataTable extends Table {

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
        $this->data = is_a($data, "DateTime") ? $data : new DateTime($data);
    }

    /**
     * Getter: data
     * @return DateTime
     */
    public function getData() {
        return (is_a($this->data, "DateTime")) ? $this->data : new DateTime($this->data);
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getData()->format(DATE_W3C);
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Giornata[]|Giornata|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Giornata
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int $ids
     * @return Giornata[]|null
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

 
