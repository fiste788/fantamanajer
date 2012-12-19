<?php

require_once(MODELDIR . 'DbTable.inc.php');

abstract class ClubModel extends DbTable {

    /**
     *
     * @param type $key
     * @param type $value
     * @return Club[]|Club|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Club
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Club[]|NULL
     */
    public static function getByIds($ids) {
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

?>
