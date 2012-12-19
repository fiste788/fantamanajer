<?php

require_once(MODELDIR . 'DbTable.inc.php');

abstract class ArticoloModel extends DbTable {

    /**
     *
     * @param type $key
     * @param type $value
     * @return Articolo[]|Articolo|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Articolo
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Articolo[]|NULL
     */
    public static function getByIds($ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Articolo[]
     */
    public static function getList() {
        return parent::getList();
    }

}

?>
