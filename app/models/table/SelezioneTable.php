<?php

namespace Fantamanajer\Models\Table;

abstract class SelezioneTable extends \Lib\Database\Table {

    const TABLE_NAME = 'selezione';

    /**
     *
     * @var int
     */
    public $idLega;

    /**
     *
     * @var int
     */
    public $idUtente;

    /**
     *
     * @var int
     */
    public $idGiocatoreOld;

    /**
     *
     * @var int
     */
    public $idGiocatoreNew;

    /**
     *
     * @var int
     */
    public $numSelezioni;

    public function __construct() {
        parent::__construct();
        $this->idLega = is_null($this->idLega) ? NULL : $this->getIdLega();
        $this->idUtente = is_null($this->idUtente) ? NULL : $this->getIdUtente();
        $this->idGiocatoreOld = is_null($this->idGiocatoreOld) ? NULL : $this->getIdGiocatoreOld();
        $this->idGiocatoreNew = is_null($this->idGiocatoreNew) ? NULL : $this->getIdGiocatoreNew();
        $this->numSelezioni = is_null($this->numSelezioni) ? NULL : $this->getNumSelezioni();
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
     * Setter: idUtente
     * @param Int $idUtente
     * @return void
     */
    public function setIdUtente($idUtente) {
        $this->idUtente = (int) $idUtente;
    }

    /**
     * Setter: idGiocatoreOld
     * @param Int $idGiocatoreOld
     * @return void
     */
    public function setIdGiocatoreOld($idGiocatoreOld) {
        $this->idGiocatoreOld = (int) $idGiocatoreOld;
    }

    /**
     * Setter: idGiocatoreNew
     * @param Int $idGiocatoreNew
     * @return void
     */
    public function setIdGiocatoreNew($idGiocatoreNew) {
        $this->idGiocatoreNew = (int) $idGiocatoreNew;
    }

    /**
     * Setter: numSelezioni
     * @param Int $numSelezioni
     * @return void
     */
    public function setNumSelezioni($numSelezioni) {
        $this->numSelezioni = (int) $numSelezioni;
    }

    /**
     * Setter: lega
     * @param Lega $lega
     * @return void
     */
    public function setLega($lega) {
        $this->lega = $lega;
        $this->idLega = $lega->getIdLega();
    }

    /**
     * Setter: giocatore
     * @param Giocatore $giocatore
     * @return void
     */
    public function setGiocatoreOld($giocatoreOld) {
        $this->giocatoreOld = $giocatoreOld;
        $this->setIdGiocatoreOld($giocatoreOld->getId());
    }

    /**
     * Setter: giocatore
     * @param Giocatore $giocatore
     * @return void
     */
    public function setGiocatoreNew($giocatoreNew) {
        $this->giocatoreNew = $giocatoreNew;
        $this->setIdGiocatoreNew($giocatoreNew->getId());
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
     * Getter: idLega
     * @return Int
     */
    public function getIdLega() {
        return (int) $this->idLega;
    }

    /**
     * Getter: idUtente
     * @return Int
     */
    public function getIdUtente() {
        return (int) $this->idUtente;
    }

    /**
     * Getter: idGiocatoreOld
     * @return Int
     */
    public function getIdGiocatoreOld() {
        return (int) $this->idGiocatoreOld;
    }

    /**
     * Getter: idGiocatoreNew
     * @return Int
     */
    public function getIdGiocatoreNew() {
        return (int) $this->idGiocatoreNew;
    }

    /**
     * Getter: numSelezioni
     * @return Int
     */
    public function getNumSelezioni() {
        return (int) $this->numSelezioni;
    }

    /**
     * Getter: Lega
     * @return Lega
     */
    public function getLega() {
        if (empty($this->lega))
            $this->lega = Lega::getById($this->getIdLega());
        return $this->lega;
    }

    /**
     * Getter: utente
     * @return Utente
     */
    public function getUtente() {
        if (empty($this->utente))
            $this->utente = Utente::getById($this->getIdUtente());
        return $this->utente;
    }

    /**
     * Getter: id
     * @return Giocatore
     */
    public function getGiocatoreNew() {
        if (empty($this->giocatoreNew))
            $this->giocatoreNew = Giocatore::getById($this->getIdGiocatoreNew());
        return $this->giocatoreNew;
    }

    /**
     * Getter: id
     * @return Giocatore
     */
    public function getGiocatoreOld() {
        if (empty($this->giocatoreOld))
            $this->giocatoreOld = Giocatore::getById($this->getIdGiocatoreOld());
        return $this->giocatoreOld;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->id;
    }

    /**
     *
     * @param type $key
     * @param type $value
     * @return Selezione[]|Selezione|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Selezione
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Selezione[]|NULL
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Selezione[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
