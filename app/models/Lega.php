<?php

namespace Fantamanajer\Models;
use Lib\Database as Db;

class Lega extends Table\LegaTable {

    public static function getDefaultValue() {
        $q = "SHOW COLUMNS
				FROM lega";
        $exe = Db\ConnectionFactory::getFactory()->getConnection()->query($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__))
            $values[$obj->Field] = $obj->Default;
        return $values;
    }

    public function check(array $array) {
        $post = (object) $array;
        foreach ($array as $key => $val)
            if ($key != "capitano" && $key != "capitanoFormazioneDimenticata" && $key != "jolly" && $key != "premi" && empty($val))
                throw new FormException("Non hai compilato tutti i campi" . $key);
        if (!is_numeric($post->numTrasferimenti) || !is_numeric($post->numSelezioni) || !is_numeric($post->minFormazione))
            throw new FormException("Tipo di dati incorretto. Controlla i valori numerici");
        return TRUE;
    }

    /**
     * Getter: id
     * @return Utente[]
     */
    public function getUtenti() {
        if (empty($this->utenti))
            $this->utenti = Utente::getByField('idLega', $this->getId());
        return $this->utenti;
    }

    /**
     * Getter: id
     * @return Articolo[]
     */
    public function getArticoli() {
        if (empty($this->articoli))
            $this->articoli = Articolo::getByField('idLega', $this->getId());
        return $this->articoli;
    }

    /**
     *
     * @param int $idGiornata
     * @return Articolo[]
     */
    public function getArticoliByGiornata($idGiornata) {
        if (empty($this->articoli))
            $this->articoli = Articolo::getArticoliByGiornataAndLega($idGiornata, $this->id);
        return $this->articoli;
    }

    /**
     * Getter: id
     * @return Evento[]
     */
    public function getEventi() {
        if (empty($this->eventi))
            $this->eventi = Evento::getByField('idLega', $this->getId());
        return $this->eventi;
    }

}

?>
