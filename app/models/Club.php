<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\ClubsTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use PDO;

class Club extends ClubsTable {
    
    /**
     * 
     * @param \Fantamanajer\Models\Season $season
     */
    public static function getBySeason(Season $season) {
        $q = "SELECT * "
                . "FROM clubs "
                . "WHERE id IN (SELECT distinct club_id "
                    . "FROM members WHERE season_id = :season_id)";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(':season_id', $season->getId(), PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__)) {
            $values[$obj->getId()] = $obj;
        }
        return $values;
        
    }

}

 