<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\DispositionsTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use PDO;

class Disposition extends DispositionsTable {

    public static function getByLineupId($lineupId) {
        $q = "SELECT *
		FROM dispositions
		WHERE lineup_id = :lineup_id
		ORDER BY position";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":lineup_id", $lineupId, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->fetchAll(PDO::FETCH_CLASS,__CLASS__);
    }

}

 