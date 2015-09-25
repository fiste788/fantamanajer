<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\ClubsTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use PDO;

class Club extends ClubsTable {

    public static function getClubByIdWithStats($idClub) {
        $q = "SELECT *
				FROM clubstatistiche
				WHERE idClub = :idClub";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(':idClub', $idClub, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->fetchObject(__CLASS__);
    }

}

 