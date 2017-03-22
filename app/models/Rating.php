<?php

namespace Fantamanajer\Models;

use Fantamanajer\Lib\FileSystem;
use Fantamanajer\Models\Table\RatingsTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use PDO;

class Rating extends RatingsTable {

    /**
     *
     * @param int $idGiocatore
     * @param int $idGiornata
     * @return Voto
     */
    public static function getByGiocatoreAndGiornata($idGiocatore, $idGiornata) {
        $q = "SELECT *
				FROM voto
				WHERE idGiocatore = :idGiocatore AND idGiornata = :idGiornata";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idGiocatore", $idGiocatore,PDO::PARAM_INT);
        $exe->bindValue(":idGiornata", $idGiornata,PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->fetchObject(__CLASS__);
    }

    public static function checkRatingsExists(Matchday $matchday) {
        $q = "SELECT *
		FROM ratings
                WHERE matchday_id = :matchday_id";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue("matchday_id", $matchday->getId(), PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->rowCount() > 0;
    }

    public static function importRatings($path, $giornata) {
        $players = FileSystem::returnArray($path, ";");  //true per intestazione
        foreach ($players as $id => $stats) {
            $valutato = ConnectionFactory::getFactory()->getConnection()->quote($stats[6],PDO::PARAM_INT); //1=valutato,0=senzavoto
            $punti = ConnectionFactory::getFactory()->getConnection()->quote($stats[7]);
            $voto = ConnectionFactory::getFactory()->getConnection()->quote($stats[10]);
            $gol = ConnectionFactory::getFactory()->getConnection()->quote($stats[11],PDO::PARAM_INT);
            $golsub = ConnectionFactory::getFactory()->getConnection()->quote($stats[12],PDO::PARAM_INT);
            $golvit = ConnectionFactory::getFactory()->getConnection()->quote($stats[13],PDO::PARAM_INT);
            $golpar = ConnectionFactory::getFactory()->getConnection()->quote($stats[14],PDO::PARAM_INT);
            $assist = ConnectionFactory::getFactory()->getConnection()->quote($stats[15],PDO::PARAM_INT);
            $ammonito = ConnectionFactory::getFactory()->getConnection()->quote($stats[16],PDO::PARAM_INT);
            $espulso = ConnectionFactory::getFactory()->getConnection()->quote($stats[17],PDO::PARAM_INT);
            $rigorisegn = ConnectionFactory::getFactory()->getConnection()->quote($stats[18],PDO::PARAM_INT);
            $rigorisub = ConnectionFactory::getFactory()->getConnection()->quote($stats[19],PDO::PARAM_INT);
            $presenza = ConnectionFactory::getFactory()->getConnection()->quote($stats[23],PDO::PARAM_INT);
            $titolare = ConnectionFactory::getFactory()->getConnection()->quote($stats[24],PDO::PARAM_INT);
            $quotazione = ConnectionFactory::getFactory()->getConnection()->quote($stats[27],PDO::PARAM_INT);
            $rows[] = "($id,$giornata,$valutato,$punti,$voto,$gol,$golsub,$golvit,$golpar,$assist,$ammonito,$espulso,$rigorisegn,$rigorisub,$presenza,$titolare,$quotazione)";
        }
        $q = "INSERT INTO ratings (member_id,matchday_id,valued,points,rating,goals,goals_against,goals_victory,goals_tie,assist,yellow_card,red_card,penalities_scored,penalities_taken,present,regular,quotation) VALUES ";
        $q .= implode(',', $rows);
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        return $exe->execute();
    }

}

 