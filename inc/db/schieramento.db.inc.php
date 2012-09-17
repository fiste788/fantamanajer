<?php

require_once(TABLEDIR . 'Schieramento.table.db.inc.php');

class Schieramento extends SchieramentoTable {

    public static function getByIdAndGiocatore($idFormazione, $idGiocatore) {
        $q = "SELECT *
				FROM schieramento
				WHERE idFormazione = '" . $idFormazione . "' AND idGiocatore = '" . $idGiocatore . "'";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        return mysql_fetch_object($exe, __CLASS__);
    }

    public static function getSchieramentoById($idFormazione) {
        $q = "SELECT *
				FROM schieramento
				WHERE idFormazione = '" . $idFormazione . "'
				ORDER BY posizione";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $values[] = $row;
        return $values;
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
