<?php

require_once(TABLEDIR . 'Schieramento.table.db.inc.php');

class Schieramento extends SchieramentoTable {

    public static function getSchieramentoById($idFormazione) {
        $q = "SELECT *
				FROM schieramento
				WHERE idFormazione = '" . $idFormazione . "'
				ORDER BY posizione";
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        return $exe->fetchAll(PDO::FETCH_CLASS,__CLASS__);
    }

    public function check($array, $message) {
        return TRUE;
    }

    public function getVoto() {
        require_once(INCDBDIR . 'voto.db.inc.php');
        return Voto::getByGiocatoreAndGiornata($this->getIdGiocatore(), $this->getFormazione()->getIdGiornata());
    }

}

?>
