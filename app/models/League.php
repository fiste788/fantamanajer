<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\LeaguesTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use Lib\FormException;

class League extends LeaguesTable {

    public static function getDefaultValue() {
        $q = "SHOW COLUMNS
                FROM " . self::TABLE_NAME;
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__)) {
            $values[$obj->Field] = $obj->Default;
        }
        return $values;
    }

    public function check(array $array = array()) {
        $post = (object) $array;
        FirePHP::getInstance()->log($this);
        FirePHP::getInstance()->log("aaaa");
        foreach ($array as $key => $val)
            if ($key != "capitano" && $key != "capitanoFormazioneDimenticata" && $key != "jolly" && $key != "premi" && empty($val))
                throw new FormException("Non hai compilato tutti i campi" . $key);
        if (!is_numeric($this->numTrasferimenti) || !is_numeric($this->numSelezioni) || !is_numeric($this->minFormazione))
            throw new FormException("Tipo di dati incorretto. Controlla i valori numerici");
        return TRUE;
    }

}

 