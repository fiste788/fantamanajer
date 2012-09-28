<?php

require_once(MODELDIR . 'DbTable.inc.php');

class FormazioneModel extends DbTable {

    /**
     *
     * @param type $key
     * @param type $value
     * @return Formazione[]|Formazione|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Formazione
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Formazione[]|NULL
     */
    public static function getByIds($ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Formazione[]
     */
    public static function getList() {
        return parent::getList();
    }

}

?>
