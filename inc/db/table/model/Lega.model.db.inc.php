<?php

namespace Fantamanajer\Database\Table;

abstract class LegaModel extends \Fantamanajer\Database\Table {

    /**
     *
     * @param type $key
     * @param type $value
     * @return Lega[]|Lega|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Lega
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Lega[]|NULL
     */
    public static function getByIds($ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Lega[]
     */
    public static function getList() {
        return parent::getList();
    }

}

?>
