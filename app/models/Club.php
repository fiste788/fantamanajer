<?php

namespace Fantamanajer\Models;

class Club extends Table\ClubTable {

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

?>
