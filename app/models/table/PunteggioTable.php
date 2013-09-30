<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Punteggio;
use Lib\Database\Table;

abstract class PunteggioTable extends Table {

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
     * @param float $punteggio
     * @return void
     */
    public function setPunteggio($punteggio) {
        $this->punteggio = (float) $punteggio;
    }

    /**
     * Setter: penalità
     * @param string $penalità
     * @return void
     */
    public function setPenalità($penalità) {
        $this->penalità = $penalità;
    }

    /**
     * Setter: idGiornata
     * @param int $idGiornata
     * @return void
     */
    public function setIdGiornata($idGiornata) {
        $this->idGiornata = (int) $idGiornata;
    }

    /**
     * Setter: idUtente
     * @param int $idUtente
     * @return void
     */
    public function setIdUtente($idUtente) {
        $this->idUtente = (int) $idUtente;
    }

    /**
     * Setter: idLega
     * @param int $idLega
     * @return void
     */
    public function setIdLega($idLega) {
        $this->idLega = (int) $idLega;
    }

    /**
     * Getter: punteggio
     * @return float
     */
    public function getPunteggio() {
        return (float) $this->punteggio;
    }

    /**
     * Getter: penalità
     * @return string
     */
    public function getPenalità() {
        return $this->penalità;
    }

    /**
     * Getter: idGiornata
     * @return int
     */
    public function getIdGiornata() {
        return (int) $this->idGiornata;
    }

    /**
     * Getter: idUtente
     * @return int
     */
    public function getIdUtente() {
        return (int) $this->idUtente;
    }

    /**
     * Getter: idLega
     * @return int
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
     * @param string $key
     * @param mixed $value
     * @return Punteggio[]|Punteggio|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Punteggio
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Punteggio[]|null
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

 
