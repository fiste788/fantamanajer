<?php

require_once(MODELDIR . 'DbTable.inc.php');

abstract class TrasferimentoModel extends DbTable {

    /**
     *
     * @param type $key
     * @param type $value
     * @return Trasferimento[]|Trasferimento|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Trasferimento
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Trasferimento[]|NULL
     */
    public static function getByIds($ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Trasferimento[]
     */
    public static function getList() {
        return parent::getList();
    }

}

?>
