<?php

require_once(MODELDIR . 'DbTable.inc.php');

class UtenteModel extends DbTable {

    /**
     *
     * @param type $key
     * @param type $value
     * @return Utente[]|Utente|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Utente
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Utente[]|NULL
     */
    public static function getByIds($ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Utente[]
     */
    public static function getList() {
        return parent::getList();
    }

}

?>
