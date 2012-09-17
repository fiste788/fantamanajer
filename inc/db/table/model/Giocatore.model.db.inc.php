<?php

require_once(MODELDIR . 'DbTable.inc.php');

class GiocatoreModel extends DbTable {

    /**
     *
     * @param type $key
     * @param type $value
     * @return Giocatore[]|Giocatore|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Giocatore
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Giocatore[]|NULL
     */
    public static function getByIds($ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Giocatore[]
     */
    public static function getList() {
        return parent::getList();
    }

}

?>
