<?php

require_once(MODELDIR . 'DbTable.inc.php');

abstract class EventoModel extends DbTable {

    /**
     *
     * @param type $key
     * @param type $value
     * @return Evento[]|Evento|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Evento
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Evento[]|NULL
     */
    public static function getByIds($ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Evento[]
     */
    public static function getList() {
        return parent::getList();
    }

}

?>
