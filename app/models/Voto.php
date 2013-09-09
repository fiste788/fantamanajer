<?php

namespace Fantamanajer\Models;
use Lib\Database as Db;

class Voto extends Table\VotoTable {

    /**
     *
     * @param type $idGiocatore
     * @param type $idGiornata
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

    /**
     *
     * @param Giocatore $giocatore
     * @return Voto[]
     */
    public static function getByGiocatore($giocatore) {
        $q = "SELECT *
				FROM voto
				WHERE idGiocatore = :idGiocatore AND valutato = :valutato
                ORDER BY idGiornata ASC";
        $exe = Db\ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idGiocatore", $giocatore->getId(), \PDO::PARAM_INT);
        $exe->bindValue(":valutato", TRUE, \PDO::PARAM_INT);
        $exe->execute();
        \FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__))
            $values[$obj->getIdGiornata()] = $obj;
        return $values;
    }

    public static function checkVotiExist($giornata) {
        $q = "SELECT *
				FROM voto
                WHERE idGiornata = :idGiornata";
        $exe = Db\ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue("idGiornata", $giornata, \PDO::PARAM_INT);
        $exe->execute();
        \FirePHP::getInstance()->log($q);
        return $exe->rowCount() > 0;
    }

    public static function importVoti($path, $giornata) {
        $players = \Fantamanajer\Lib\FileSystem::returnArray($path, ";");  //true per intestazione
        foreach ($players as $id => $stats) {
            $valutato = Db\ConnectionFactory::getFactory()->getConnection()->quote($stats[6],\PDO::PARAM_INT); //1=valutato,0=senzavoto
            $punti = Db\ConnectionFactory::getFactory()->getConnection()->quote($stats[7]);
            $voto = Db\ConnectionFactory::getFactory()->getConnection()->quote($stats[10]);
            $gol = Db\ConnectionFactory::getFactory()->getConnection()->quote($stats[11],\PDO::PARAM_INT);
            $golsub = Db\ConnectionFactory::getFactory()->getConnection()->quote($stats[12],\PDO::PARAM_INT);
            $golvit = Db\ConnectionFactory::getFactory()->getConnection()->quote($stats[13],\PDO::PARAM_INT);
            $golpar = Db\ConnectionFactory::getFactory()->getConnection()->quote($stats[14],\PDO::PARAM_INT);
            $assist = Db\ConnectionFactory::getFactory()->getConnection()->quote($stats[15],\PDO::PARAM_INT);
            $ammonito = Db\ConnectionFactory::getFactory()->getConnection()->quote($stats[16],\PDO::PARAM_INT);
            $espulso = Db\ConnectionFactory::getFactory()->getConnection()->quote($stats[17],\PDO::PARAM_INT);
            $rigorisegn = Db\ConnectionFactory::getFactory()->getConnection()->quote($stats[18],\PDO::PARAM_INT);
            $rigorisub = Db\ConnectionFactory::getFactory()->getConnection()->quote($stats[19],\PDO::PARAM_INT);
            $presenza = Db\ConnectionFactory::getFactory()->getConnection()->quote($stats[23],\PDO::PARAM_INT);
            $titolare = Db\ConnectionFactory::getFactory()->getConnection()->quote($stats[24],\PDO::PARAM_INT);
            $quotazione = Db\ConnectionFactory::getFactory()->getConnection()->quote($stats[27],\PDO::PARAM_INT);
            $rows[] = "($id,$giornata,$valutato,$punti,$voto,$gol,$golsub,$golvit,$golpar,$assist,$ammonito,$espulso,$rigorisegn,$rigorisub,$presenza,$titolare,$quotazione)";
        }
        $q = "INSERT INTO voto (idGiocatore,idGiornata,valutato,punti,voto,gol,golSubiti,golVittoria,golPareggio,assist,ammonito,espulso,rigoriSegnati,rigoriSubiti,presente,titolare,quotazione) VALUES ";
        $q .= implode(',', $rows);
        $exe = Db\ConnectionFactory::getFactory()->getConnection()->prepare($q);
        return $exe->execute();
    }

}

 