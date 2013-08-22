<?php

namespace Fantamanajer\Models\Table;

abstract class TrasferimentoTable extends \Lib\Database\Table {

    const TABLE_NAME = "trasferimento";

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
    public $idUtente;

    /**
     *
     * @var int
     */
    public $idGiornata;

    /**
     *
     * @var boolean
     */
    public $obbligato;

    public function __construct() {
        parent::__construct();
        $this->idGiocatoreOld = is_null($this->idGiocatoreOld) ? NULL : $this->getIdGiocatoreOld();
        $this->idGiocatoreNew = is_null($this->idGiocatoreNew) ? NULL : $this->getIdGiocatoreNew();
        $this->idUtente = is_null($this->idUtente) ? NULL : $this->getIdUtente();
        $this->idGiornata = is_null($this->idGiornata) ? NULL : $this->getIdGiornata();
        $this->obbligato = is_null($this->obbligato) ? NULL : $this->isObbligato();
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
     * Setter: idUtente
     * @param Int $idUtente
     * @return void
     */
    public function setIdUtente($idUtente) {
        $this->idUtente = (int) $idUtente;
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
     * Setter: obbligato
     * @param Boolean $obbligato
     * @return void
     */
    public function setObbligato($obbligato) {
        $this->obbligato = (boolean) $obbligato;
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
     * Setter: giornata
     * @param Giornata $giornata
     * @return void
     */
    public function setGiornata($giornata) {
        $this->giornata = $giornata;
        $this->setIdGiornata($giornata->getId());
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
     * Getter: idUtente
     * @return Int
     */
    public function getIdUtente() {
        return (int) $this->idUtente;
    }

    /**
     * Getter: idGiornata
     * @return Int
     */
    public function getIdGiornata() {
        return (int) $this->idGiornata;
    }

    /**
     * Getter: obbligato
     * @return Boolean
     */
    public function isObbligato() {
        return (boolean) $this->obbligato;
    }

    /**
     * Getter: giocatore
     * @return Int
     */
    public function getGiocatoreOld() {
        if (empty($this->giocatoreOld))
            $this->giocatoreOld = \Fantamanajer\Models\Giocatore::getById($this->getIdGiocatoreOld());
        return $this->giocatoreOld;
    }

    /**
     * Getter: giocatore
     * @return Int
     */
    public function getGiocatoreNew() {
        if (empty($this->giocatoreNew))
            $this->giocatoreNew = \Fantamanajer\Models\Giocatore::getById($this->getIdGiocatoreNew());
        return $this->giocatoreNew;
    }

    /**
     * Getter: utente
     * @return Utente
     */
    public function getUtente() {
        if (empty($this->utente))
            $this->utente = \Fantamanajer\Models\Utente::getById($this->getIdUtente());
        return $this->utente;
    }

    /**
     * Getter: giornata
     * @return Giornata
     */
    public function getGiornata() {
        if (empty($this->giornata))
            $this->giornata = \Fantamanajer\Models\Giornata::getById($this->getIdGiornata());
        return $this->giornata;
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
     * @param type $key
     * @param type $value
     * @return Trasferimento[]|Articolo|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Trasferimento
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Trasferimento[]|NULL
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Trasferimento[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
