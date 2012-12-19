<?php

require_once(MODELDIR . 'DbTable.inc.php');

abstract class LegaModel extends DbTable {

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
