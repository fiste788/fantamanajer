<?php

require_once(MODELDIR . 'DbTable.inc.php');

class PunteggioModel extends DbTable {

    /**
     *
     * @param type $key
     * @param type $value
     * @return Punteggio[]|Punteggio|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Punteggio
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Punteggio[]|NULL
     */
    public static function getByIds($ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Punteggio[]
     */
    public static function getList() {
        return parent::getList();
    }

}

?>
