<?php

require_once(TABLEDIR . 'Club.table.db.inc.php');

class Club extends ClubTable {

    public static function getClubByIdWithStats($idClub) {
        $q = "SELECT *
				FROM clubstatistiche
				WHERE idClub = '" . $idClub . "'";
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        return $exe->fetchObject(__CLASS__);
    }

}

?>
