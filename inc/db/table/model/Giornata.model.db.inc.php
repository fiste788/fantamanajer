<?php

require_once(MODELDIR . 'DbTable.inc.php');

abstract class GiornataModel extends DbTable {

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
    public static function getByIds($ids) {
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

?>
