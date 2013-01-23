<?php

namespace Fantamanajer\Db\Table;

class Squadra extends DbTable {

    var $idLega;
    var $idUtente;
    var $idGioc;

    public static function setSquadraGiocatoreByArray($idLega, $giocatori, $idUtente) {
        $q = "INSERT INTO squadra VALUES ";
        foreach ($giocatori as $key => $val)
            $row[] = "('" . $idLega . "','" . $idUtente . "','" . $val . "')";
        $q .= implode(',', $row);
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }

    public static function unsetSquadraGiocatoreByIdSquadra($idUtente) {
        $q = "DELETE
				FROM squadra
				WHERE idUtente = '" . $idUtente . "'";
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }

    public static function updateGiocatore($giocatoreNew, $giocatoreOld, $idUtente) {
        $q = "UPDATE squadra
				SET idGiocatore = '" . $giocatoreNew . "'
				WHERE idGioc = '" . $giocatoreOld . "' AND idUtente = '" . $idUtente . "'";
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }

    public static function updateGiocatoreSquadra($idGioc, $idLega, $idUtente) {
        $q = "UPDATE squadra
				SET idUtente = '" . $idUtente . "'
				WHERE idGiocatore = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }

    public static function getSquadraByIdGioc($idGioc, $idLega) {
        $q = "SELECT idUtente
				FROM squadra
				WHERE idGiocatore = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        return $exe->fetchColumn();
    }

    public static function setSquadraByIdGioc($idGioc, $idLega, $idUtente) {
        $q = "INSERT INTO squadra
				VALUES ('" . $idLega . "','" . $idUtente . "','" . $idGioc . "')";
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }

    public static function unsetSquadraByIdGioc($idGioc, $idLega) {
        $q = "DELETE
				FROM squadra
				WHERE idGiocatore = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }

}

?>
