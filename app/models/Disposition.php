<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\DispositionsTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use PDO;

class Disposition extends DispositionsTable {

    public static function getSchieramentoById($idFormazione) {
        $q = "SELECT *
				FROM schieramento
				WHERE idFormazione = :idFormazione
				ORDER BY posizione";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idFormazione", $idFormazione, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->fetchAll(PDO::FETCH_CLASS,__CLASS__);
    }

    public function getVoto() {
        return Voto::getByGiocatoreAndGiornata($this->getIdGiocatore(), $this->getFormazione()->getIdGiornata());
    }

}

 