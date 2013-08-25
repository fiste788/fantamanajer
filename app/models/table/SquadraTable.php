<?php

namespace Fantamanajer\Models\Table;

abstract class SquadraTable extends \Lib\Database\Table {

    const TABLE_NAME = 'squadras';

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
    public $idGiocatore;

    
    public function __construct() {
        parent::__construct();
        $this->idLega = is_null($this->idLega) ? NULL : $this->getIdLega();
        $this->idUtente = is_null($this->idUtente) ? NULL : $this->getIdUtente();
        $this->idGiocatore = is_null($this->idGiocatore) ? NULL : $this->getIdGiocatore();
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
     * Setter: idGiocatore
     * @param Int $idGiocatore
     * @return void
     */
    public function setIdGiocatore($idGiocatore) {
        $this->idGiocatore = (int) $idGiocatore;
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
    public function setGiocatore($giocatore) {
        $this->giocatore = $giocatore;
        $this->setIdGiocatore($giocatore->getId());
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
     * Getter: idGiocatore
     * @return Int
     */
    public function getIdGiocatore() {
        return (int) $this->idGiocatore;
    }

    /**
     * Getter: Lega
     * @return Lega
     */
    public function getLega() {
        if (empty($this->lega))
            $this->lega = \Fantamanajer\Models\Lega::getById($this->getIdLega());
        return $this->lega;
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
     * Getter: id
     * @return Giocatore
     */
    public function getGiocatore() {
        if (empty($this->giocatore))
            $this->giocatore = \Fantamanajer\Models\Giocatore::getById($this->getIdGiocatore());
        return $this->giocatore;
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

 
