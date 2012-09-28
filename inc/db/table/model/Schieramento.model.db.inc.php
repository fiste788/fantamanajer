<?php

require_once(MODELDIR . 'DbTable.inc.php');

class SchieramentoModel extends DbTable {

    /**
     *
     * @param type $key
     * @param type $value
     * @return Schieramento[]|Schieramento|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Schieramento
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Schieramento[]|NULL
     */
    public static function getByIds($ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Schieramento[]
     */
    public static function getList() {
        return parent::getList();
    }

}

?>
