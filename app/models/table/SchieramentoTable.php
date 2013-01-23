<?php

namespace Fantamanajer\Models\Table;

abstract class SchieramentoTable extends \Lib\Database\Table {

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
     * @param Int $idFormazione
     * @return void
     */
    public function setIdFormazione($idFormazione) {
        $this->idFormazione = (int) $idFormazione;
    }

    /**
     * Setter: idGiocatore
     * @param Int $idGiocatore
     * @return void
     */
    public function setIdGiocatore($idGiocatore) {
        $this->idGiocatore = (int) $idGiocatore;
    }

    /**
     * Setter: posizione
     * @param Int $posizione
     * @return void
     */
    public function setPosizione($posizione) {
        $this->posizione = (int) $posizione;
    }

    /**
     * Setter: considerato
     * @param Int $considerato
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
     * @return Int
     */
    public function getIdFormazione() {
        return (int) $this->idFormazione;
    }

    /**
     * Getter: idGiocatore
     * @return Int
     */
    public function getIdGiocatore() {
        return (int) $this->idGiocatore;
    }

    /**
     * Getter: posizione
     * @return Int
     */
    public function getPosizione() {
        return (int) $this->posizione;
    }

    /**
     * Getter: considerato
     * @return Int
     */
    public function getConsiderato() {
        return (int) $this->considerato;
    }

    /**
     * Getter: formazione
     * @return Int
     */
    public function getFormazione() {
        if (empty($this->formazione))
            $this->formazione = Formazione::getById($this->getIdFormazione());
        return $this->formazione;
    }

    /**
     * Getter: giocatore
     * @return Int
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
     * @param type $key
     * @param type $value
     * @return Schieramento[]|Schieramento|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Schieramento
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Schieramento[]|NULL
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

?>
