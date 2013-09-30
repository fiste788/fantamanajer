<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\LegaTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use Lib\FormException;

class Lega extends LegaTable {

    public static function getDefaultValue() {
        $q = "SHOW COLUMNS
				FROM lega";
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__))
            $values[$obj->Field] = $obj->Default;
        return $values;
    }

    public function check(array $array = array()) {
        $post = (object) $array;
        FirePHP::getInstance()->log($this);
        FirePHP::getInstance()->log("aaaa");
        foreach ($array as $key => $val)
            if ($key != "capitano" && $key != "capitanoFormazioneDimenticata" && $key != "jolly" && $key != "premi" && empty($val))
                throw new FormException("Non hai compilato tutti i campi" . $key);
        if (!is_numeric($this->numTrasferimenti) || !is_numeric($this->numSelezioni) || !is_numeric($this->minFormazione))
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

 