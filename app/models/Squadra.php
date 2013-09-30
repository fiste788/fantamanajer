<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\SquadraTable;
use FirePHP;
use Lib\Database\ConnectionFactory;

class Squadra extends SquadraTable {

    public static function setSquadraGiocatoreByArray($idLega, $giocatori, $idUtente) {
        $q = "INSERT INTO squadra VALUES ";
        FirePHP::getInstance()->log($giocatori);
        foreach ($giocatori as $val) {
            $row[] = "('" . $idLega . "','" . $idUtente . "','" . $val . "')";
        }
        $q .= implode(',', $row);
        FirePHP::getInstance()->log($q);
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }

    public static function unsetSquadraGiocatoreByIdSquadra($idUtente) {
        $q = "DELETE
				FROM squadra
				WHERE idUtente = '" . $idUtente . "'";
        FirePHP::getInstance()->log($q);
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }

    public static function updateGiocatore($giocatoreNew, $giocatoreOld, $idUtente) {
        $q = "UPDATE squadra
				SET idGiocatore = '" . $giocatoreNew . "'
				WHERE idGiocatore = '" . $giocatoreOld . "' AND idUtente = '" . $idUtente . "'";
        FirePHP::getInstance()->log($q);
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }

    public static function updateGiocatoreSquadra($idGioc, $idLega, $idUtente) {
        $q = "UPDATE squadra
				SET idUtente = '" . $idUtente . "'
				WHERE idGiocatore = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
        FirePHP::getInstance()->log($q);
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }

    public static function getSquadraByIdGioc($idGioc, $idLega) {
        $q = "SELECT idUtente
				FROM squadra
				WHERE idGiocatore = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
        FirePHP::getInstance()->log($q);
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        return $exe->fetchColumn();
    }

    public static function setSquadraByIdGioc($idGioc, $idLega, $idUtente) {
        $q = "INSERT INTO squadra
				VALUES ('" . $idLega . "','" . $idUtente . "','" . $idGioc . "')";
        FirePHP::getInstance()->log($q);
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }

    public static function unsetSquadraByIdGioc($idGioc, $idLega) {
        $q = "DELETE
				FROM squadra
				WHERE idGiocatore = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
        FirePHP::getInstance()->log($q);
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }

}
