<?php

require_once(MODELDIR . 'DbTable.inc.php');

abstract class VotoModel extends DbTable {

    /**
     *
     * @param type $key
     * @param type $value
     * @return Voto[]|Voto|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Voto
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Voto[]|NULL
     */
    public static function getByIds($ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Voto[]
     */
    public static function getList() {
        return parent::getList();
    }

}

?>
