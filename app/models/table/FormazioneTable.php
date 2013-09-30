<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Formazione;
use Fantamanajer\Models\Giocatore;
use Fantamanajer\Models\Giornata;
use Fantamanajer\Models\Utente;
use Fantamanajer\Models\View\GiocatoreStatistiche;
use Lib\Database\Table;

abstract class FormazioneTable extends Table {

    const TABLE_NAME = 'formazione';

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
     * @var string
     */
    public $modulo;

    /**
     *
     * @var int
     */
    public $idCapitano;

    /**
     *
     * @var int
     */
    public $idVCapitano;

    /**
     *
     * @var int
     */
    public $idVVCapitano;

    /**
     *
     * @var boolean
     */
    public $jolly;

    public function __construct() {
        parent::__construct();
        $this->idGiornata = is_null($this->idGiornata) ? NULL : $this->getIdGiornata();
        $this->idUtente = is_null($this->idUtente) ? NULL : $this->getIdUtente();
        $this->modulo = is_null($this->modulo) ? NULL : $this->getModulo();
        $this->idCapitano = is_null($this->idCapitano) ? NULL : $this->getIdCapitano();
        $this->idVCapitano = is_null($this->idVCapitano) ? NULL : $this->getIdVCapitano();
        $this->idVVCapitano = is_null($this->idVVCapitano) ? NULL : $this->getIdVVCapitano();
        $this->jolly = is_null($this->jolly) ? NULL : $this->getJolly();
    }

    /**
     * Setter: id
     * @param int $id
     * @return void
     */
    public function setId($id) {
        $this->id = (int) $id;
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
     * Setter: modulo
     * @param string $modulo
     * @return void
     */
    public function setModulo($modulo) {
        $this->modulo = $modulo;
    }

    /**
     * Setter: idCapitano
     * @param int $idCapitano
     * @return void
     */
    public function setIdCapitano($idCapitano) {
        $this->idCapitano = (int) $idCapitano;
    }

    /**
     * Setter: idVCapitano
     * @param int $idVCapitano
     * @return void
     */
    public function setIdVCapitano($idVCapitano) {
        $this->idVCapitano = (int) $idVCapitano;
    }

    /**
     * Setter: idVVCapitano
     * @param int $idVVCapitano
     * @return void
     */
    public function setIdVVCapitano($idVVCapitano) {
        $this->idVVCapitano = (int) $idVVCapitano;
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
     * Setter: utente
     * @param Utente $utente
     * @return void
     */
    public function setUtente($utente) {
        $this->utente = $utente;
        $this->setIdUtente($utente->getId());
    }

    /**
     * Setter: capitano
     * @param Giocatore $giocatore
     * @return void
     */
    public function setCapitano($giocatore) {
        $this->capitano = $giocatore;
        $this->setIdCapitano($giocatore->getId());
    }

    /**
     * Setter: VCapitano
     * @param Giocatore $giocatore
     * @return void
     */
    public function setVCapitano($giocatore) {
        $this->VCapitano = $giocatore;
        $this->setIdVCapitano($giocatore->getId());
    }

    /**
     * Setter: VVCapitano
     * @param Giocatore $giocatore
     * @return void
     */
    public function setVVCapitano($giocatore) {
        $this->VVCapitano = $giocatore;
        $this->setIdVVCapitano($giocatore->getId());
    }

    /**
     * Setter: utente
     * @param Giornata $giornata
     * @return void
     */
    public function setGiornata($giornata) {
        $this->giornata = $giornata;
        $this->setIdGiornata($giornata->getId());
    }

    /**
     * Getter: id
     * @return int
     */
    public function getId() {
        return (int) $this->id;
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
     * Getter: modulo
     * @return string
     */
    public function getModulo() {
        return $this->modulo;
    }

    /**
     * Getter: idCapitano
     * @return int
     */
    public function getIdCapitano() {
        return (int) $this->idCapitano;
    }

    /**
     * Getter: idVCapitano
     * @return int
     */
    public function getIdVCapitano() {
        return (int) $this->idVCapitano;
    }

    /**
     * Getter: idVVCapitano
     * @return int
     */
    public function getIdVVCapitano() {
        return (int) $this->idVVCapitano;
    }

    /**
     * Getter: jolly
     * @return boolean
     */
    public function getJolly() {
        return (boolean) $this->jolly;
    }

    /**
     * Getter: Utente
     * @return Utente
     */
    public function getUtente() {
        if (empty($this->utente))
            $this->utente = Utente::getById($this->getIdUtente());
        return $this->utente;
    }

    /**
     * Getter: Giornata
     * @return Giornata
     */
    public function getGiornata() {
        if (empty($this->giornata))
            $this->giornata = Giornata::getById($this->getIdGiornata());
        return $this->giornata;
    }

    /**
     * Getter: Capitano
     * @return Giocatore
     */
    public function getCapitano() {
        if (empty($this->capitano))
            $this->capitano = GiocatoreStatistiche::getById($this->getId());
        return $this->capitano;
    }

    /**
     * Getter: VCapitano
     * @return Giocatore
     */
    public function getVCapitano() {
        if (empty($this->VCapitano))
            $this->VCapitano = GiocatoreStatistiche::getById($this->getId());
        return $this->VCapitano;
    }

    /**
     * Getter: VVCapitano
     * @return Giocatore
     */
    public function getVVCapitano() {
        if (empty($this->VVCapitano))
            $this->VVCapitano = GiocatoreStatistiche::getById($this->getId());
        return $this->VVCapitano;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getId();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Formazione[]|Formazione|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Formazione
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Formazione[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Formazione[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
