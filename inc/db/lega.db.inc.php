<?php

require_once(TABLEDIR . 'Lega.table.db.inc.php');

class Lega extends LegaTable {

    public static function getDefaultValue() {
        $q = "SHOW COLUMNS
				FROM lega";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $result[$row->Field] = $row->Default;
        return $result;
    }

    public function check($array, $message) {
        $post = (object) $array;
        foreach ($array as $key => $val)
            if ($key != "capitano" && $key != "jolly" && $key != "premi" && empty($val)) {
                $message->error("Non hai compilato tutti i campi" . $key);
                return FALSE;
            }
        if (!is_numeric($post->numTrasferimenti) || !is_numeric($post->numSelezioni) || !is_numeric($post->minFormazione)) {
            $message->error("Tipo di dati incorretto. Controlla i valori numerici");
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Getter: id
     * @return Utente[]
     */
    public function getUtenti() {
        require_once(INCDBDIR . 'utente.db.inc.php');
        if (empty($this->utenti))
            $this->utenti = Utente::getByField('idLega', $this->getId());
        return $this->utenti;
    }

    /**
     * Getter: id
     * @return Articolo[]
     */
    public function getArticoli() {
        require_once(INCDIR . 'articolo.db.inc.php');
        if (empty($this->articoli))
            $this->articoli = Articolo::getByField('idLega', $this->getId());
        return $this->articoli;
    }

    /**
     * Getter: id
     * @return Evento[]
     */
    public function getEventi() {
        require_once(INCDIR . 'evento.db.inc.php');
        if (empty($this->eventi))
            $this->eventi = Evento::getByField('idLega', $this->getId());
        return $this->eventi;
    }

}

?>
