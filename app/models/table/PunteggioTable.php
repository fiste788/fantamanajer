<?php

namespace Fantamanajer\Models\Table;

abstract class PunteggioTable extends \Lib\Database\Table {

    const TABLE_NAME = 'punteggio';

    /**
     *
     * @var float
     */
    public $punteggio;

    /**
     *
     * @var float
     */
    public $penalità;

    /**
     *
     * @var int
     */
    public $idGiornata;

    /**
     *
     * @var int
     */
    public $idUtente;

    /**
     *
     * @var int
     */
    public $idLega;

    public function __construct() {
        parent::__construct();
        $this->punteggio = is_null($this->punteggio) ? NULL : $this->getPunteggio();
        $this->penalità = is_null($this->penalità) ? NULL : $this->getPenalità();
        $this->idGiornata = is_null($this->idGiornata) ? NULL : $this->getIdGiornata();
        $this->idUtente = is_null($this->idUtente) ? NULL : $this->getIdUtente();
        $this->idLega = is_null($this->idLega) ? NULL : $this->getIdLega();
    }

    /**
     * Setter: punteggio
     * @param Float $punteggio
     * @return void
     */
    public function setPunteggio($punteggio) {
        $this->punteggio = (float) $punteggio;
    }

    /**
     * Setter: penalità
     * @param String $penalità
     * @return void
     */
    public function setPenalità($penalità) {
        $this->penalità = $penalità;
    }

    /**
     * Setter: idGiornata
     * @param Int $idGiornata
     * @return void
     */
    public function setIdGiornata($idGiornata) {
        $this->idGiornata = (int) $idGiornata;
    }

    /**
     * Setter: idUtente
     * @param Int $idUtente
     * @return void
     */
    public function setIdUtente($idUtente) {
        $this->idUtente = (int) $idUtente;
    }

    /**
     * Setter: idLega
     * @param Int $idLega
     * @return void
     */
    public function setIdLega($idLega) {
        $this->idLega = (int) $idLega;
    }

    /**
     * Getter: punteggio
     * @return Float
     */
    public function getPunteggio() {
        return (float) $this->punteggio;
    }

    /**
     * Getter: penalità
     * @return String
     */
    public function getPenalità() {
        return $this->penalità;
    }

    /**
     * Getter: idGiornata
     * @return Int
     */
    public function getIdGiornata() {
        return (int) $this->idGiornata;
    }

    /**
     * Getter: idUtente
     * @return Int
     */
    public function getIdUtente() {
        return (int) $this->idUtente;
    }

    /**
     * Getter: idLega
     * @return Int
     */
    public function getIdLega() {
        return (int) $this->idLega;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return (string) $this->getPunteggio();
    }

    /**
     *
     * @param type $key
     * @param type $value
     * @return Punteggio[]|Articolo|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Punteggio
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Punteggio[]|NULL
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Punteggio[]
     */
    public static function getList() {
        return parent::getList();
    }

}

?>
