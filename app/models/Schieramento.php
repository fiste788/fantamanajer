<?php

namespace Fantamanajer\Models;
use Lib\Database as Db;

class Schieramento extends Table\SchieramentoTable {

    public static function getSchieramentoById($idFormazione) {
        $q = "SELECT *
				FROM schieramento
				WHERE idFormazione = :idFormazione
				ORDER BY posizione";
        $exe = Db\ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idFormazione", $idFormazione, \PDO::PARAM_INT);
        $exe->execute();
        \FirePHP::getInstance()->log($q);
        return $exe->fetchAll(\PDO::FETCH_CLASS,__CLASS__);
    }

    public function getVoto() {
        return Voto::getByGiocatoreAndGiornata($this->getIdGiocatore(), $this->getFormazione()->getIdGiornata());
    }

}

?>
