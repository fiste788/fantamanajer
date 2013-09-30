<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Formazione;
use Fantamanajer\Models\Giocatore;
use Fantamanajer\Models\Schieramento;
use Lib\Database\Table;

abstract class SchieramentoTable extends Table {

    const TABLE_NAME = 'schieramento';

    /**
     *
     * @var int
     */
    public $idFormazione;

    /**
     *
     * @var int
     */
    public $idGiocatore;

    /**
     *
     * @var int
     */
    public $posizione;

    /**
     *
     * @var int 0 = non ha giocato, 1 = giocato, 2 = capitano
     */
    public $considerato;

    public function __construct() {
        parent::__construct();
        $this->idFormazione = is_null($this->idFormazione) ? NULL : $this->getIdFormazione();
        $this->idGiocatore = is_null($this->idGiocatore) ? NULL : $this->getIdGiocatore();
        $this->posizione = is_null($this->posizione) ? NULL : $this->getPosizione();
        $this->considerato = is_null($this->considerato) ? NULL : $this->getConsiderato();
    }

    /**
     * Setter: idFormazione
     * @param int $idFormazione
     * @return void
     */
    public function setIdFormazione($idFormazione) {
        $this->idFormazione = (int) $idFormazione;
    }

    /**
     * Setter: idGiocatore
     * @param int $idGiocatore
     * @return void
     */
    public function setIdGiocatore($idGiocatore) {
        $this->idGiocatore = (int) $idGiocatore;
    }

    /**
     * Setter: posizione
     * @param int $posizione
     * @return void
     */
    public function setPosizione($posizione) {
        $this->posizione = (int) $posizione;
    }

    /**
     * Setter: considerato
     * @param int $considerato
     * @return void
     */
    public function setConsiderato($considerato) {
        $this->considerato = (int) $considerato;
    }

    /**
     * Setter: formazione
     * @param Formazione $formazione
     * @return void
     */
    public function setFormazione($formazione) {
        $this->formazione = $formazione;
        $this->setIdFormazione($formazione->getId());
    }

    /**
     * Setter: giocatore
     * @param Giocatore $giocatore
     * @return void
     */
    public function setGiocatore($giocatore) {
        $this->giocatore = $giocatore;
        $this->setIdGiocatore($giocatore->getId());
    }

    /**
     * Getter: idFormazione
     * @return int
     */
    public function getIdFormazione() {
        return (int) $this->idFormazione;
    }

    /**
     * Getter: idGiocatore
     * @return int
     */
    public function getIdGiocatore() {
        return (int) $this->idGiocatore;
    }

    /**
     * Getter: posizione
     * @return int
     */
    public function getPosizione() {
        return (int) $this->posizione;
    }

    /**
     * Getter: considerato
     * @return int
     */
    public function getConsiderato() {
        return (int) $this->considerato;
    }

    /**
     * Getter: formazione
     * @return int
     */
    public function getFormazione() {
        if (empty($this->formazione))
            $this->formazione = Formazione::getById($this->getIdFormazione());
        return $this->formazione;
    }

    /**
     * Getter: giocatore
     * @return int
     */
    public function getGiocatore() {
        if (empty($this->giocatore))
            $this->giocatore = Giocatore::getById($this->getIdGiocatore());
        return $this->giocatore;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->idGiocatore;
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Schieramento[]|Schieramento|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Schieramento
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Schieramento[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Schieramento[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
