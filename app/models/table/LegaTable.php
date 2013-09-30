<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Lega;
use Lib\Database\Table;

abstract class LegaTable extends Table {

    const TABLE_NAME = "lega";

    /**
     *
     * @var string
     */
    public $nome;

    /**
     *
     * @var boolean
     */
    public $capitano;

    /**
     *
     * @var int
     */
    public $numTrasferimenti;

    /**
     *
     * @var int
     */
    public $numSelezioni;

    /**
     *
     * @var int
     */
    public $minFormazione;

    /**
     *
     * @var string
     */
    public $premi;

    /**
     *
     * @var int
     */
    public $punteggioFormazioneDimenticata;

    /**
     *
     * @var boolean
     */
    public $capitanoFormazioneDimenticata;

    /**
     *
     * @var boolean
     */
    public $jolly;

    public function __construct() {
        $this->nome = is_null($this->nome) ? NULL : $this->getNome();
        $this->capitano = is_null($this->capitano) ? NULL : $this->isCapitano();
        $this->numTrasferimenti = is_null($this->numTrasferimenti) ? NULL : $this->getNumTrasferimenti();
        $this->numSelezioni = is_null($this->numSelezioni) ? NULL : $this->getNumSelezioni();
        $this->minFormazione = is_null($this->minFormazione) ? NULL : $this->getMinFormazione();
        $this->premi = is_null($this->premi) ? NULL : $this->getPremi();
        $this->punteggioFormazioneDimenticata = is_null($this->punteggioFormazioneDimenticata) ? NULL : $this->getPunteggioFormazioneDimenticata();
        $this->capitanoFormazioneDimenticata = is_null($this->capitanoFormazioneDimenticata) ? NULL : $this->isCapitanoFormazioneDimenticata();
        $this->jolly = is_null($this->jolly) ? NULL : $this->isJolly();
        parent::__construct();
    }

    /**
     * Setter: nome
     * @param string $nome
     * @return void
     */
    public function setNome($nome) {
        $this->nome = (string) $nome;
    }

    /**
     * Setter: capitano
     * @param boolean $capitano
     * @return void
     */
    public function setCapitano($capitano) {
        $this->capitano = (boolean) $capitano;
    }

    /**
     * Setter: numTrasferimenti
     * @param int $numTrasferimenti
     * @return void
     */
    public function setNumTrasferimenti($numTrasferimenti) {
        $this->numTrasferimenti = (int) $numTrasferimenti;
    }

    /**
     * Setter: numSelezioni
     * @param int $numSelezioni
     * @return void
     */
    public function setNumSelezioni($numSelezioni) {
        $this->numSelezioni = (int) $numSelezioni;
    }

    /**
     * Setter: minFormazione
     * @param int $minFormazione
     * @return void
     */
    public function setMinFormazione($minFormazione) {
        $this->minFormazione = (int) $minFormazione;
    }

    /**
     * Setter: premi
     * @param string $premi
     * @return void
     */
    public function setPremi($premi) {
        $this->premi = (string) $premi;
    }

    /**
     * Setter: punteggioFormazioneDimenticata
     * @param int $punteggioFormazioneDimenticata
     * @return void
     */
    public function setPunteggioFormazioneDimenticata($punteggioFormazioneDimenticata) {
        $this->punteggioFormazioneDimenticata = (int) $punteggioFormazioneDimenticata;
    }

    /**
     * Setter: capitanoFormazioneDimenticata
     * @param boolean $capitanoFormazioneDimenticata
     * @return void
     */
    public function setCapitanoFormazioneDimenticata($capitanoFormazioneDimenticata) {
        $this->capitanoFormazioneDimenticata = (boolean) $capitanoFormazioneDimenticata;
    }

    /**
     * Setter: jolly
     * @param boolean $jolly
     * @return void
     */
    public function setJolly($jolly) {
        $this->jolly = (boolean) $jolly;
    }

    /**
     * Getter: nome
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * Getter: capitano
     * @return boolean
     */
    public function isCapitano() {
        return (boolean) $this->capitano;
    }

    /**
     * Getter: numTrasferimenti
     * @return int
     */
    public function getNumTrasferimenti() {
        return (int) $this->numTrasferimenti;
    }

    /**
     * Getter: numSelezioni
     * @return int
     */
    public function getNumSelezioni() {
        return (int) $this->numSelezioni;
    }

    /**
     * Getter: minFormazione
     * @return int
     */
    public function getMinFormazione() {
        return (int) $this->minFormazione;
    }

    /**
     * Getter: premi
     * @return string
     */
    public function getPremi() {
        return (string) $this->premi;
    }

    /**
     * Getter: punteggioFormazioneDimenticata
     * @return int
     */
    public function getPunteggioFormazioneDimenticata() {
        return (int) $this->punteggioFormazioneDimenticata;
    }

    /**
     * Getter: capitanoFormazioneDimenticata
     * @return boolean
     */
    public function isCapitanoFormazioneDimenticata() {
        return (boolean) $this->capitanoFormazioneDimenticata;
    }

    /**
     * Getter: jolly
     * @return boolean
     */
    public function isJolly() {
        return (boolean) $this->jolly;
    }

    /**
     * tostring
     * @return string
     */
    public function __toString() {
        return $this->getNome();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Lega[]|Lega|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Lega
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Lega[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Lega[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
