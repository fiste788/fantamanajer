<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\PlayersTable;

class Player extends PlayersTable {

    public static function getByFullname($surname,$name) {
         $q = "SELECT *
		FROM players
		WHERE suername = :surname AND name = :name";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":surname", $surname, PDO::PARAM_INT);
        $exe->bindValue(":name", $name, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->fetchObject(PDO::FETCH_CLASS, __CLASS__);
    }
}

 