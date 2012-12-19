<?php

require_once(MODELDIR . 'DbTable.inc.php');

abstract class SelezioneModel extends DbTable {

    /**
     *
     * @param type $key
     * @param type $value
     * @return Selezione[]|Selezione|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Selezione
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Selezione[]|NULL
     */
    public static function getByIds($ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Selezione[]
     */
    public static function getList() {
        return parent::getList();
    }

}

?>
