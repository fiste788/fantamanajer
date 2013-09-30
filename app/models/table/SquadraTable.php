<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Giocatore;
use Fantamanajer\Models\Lega;
use Fantamanajer\Models\Utente;
use Fantamanajer\Models\Utente as Utente2;
use Lib\Database\Table;

abstract class SquadraTable extends Table {

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
     * @param int $idLega
     * @return void
     */
    public function setIdLega($idLega) {
        $this->idLega = (int) $idLega;
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
     * Setter: idGiocatore
     * @param int $idGiocatore
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
     * @param Utente2 $utente
     * @return void
     */
    public function setUtente($utente) {
        $this->utente = $utente;
        $this->setIdUtente($utente->getId());
    }

    /**
     * Getter: idLega
     * @return int
     */
    public function getIdLega() {
        return (int) $this->idLega;
    }

    /**
     * Getter: idUtente
     * @return int
     */
    public function getIdUtente() {
        return (int) $this->idUtente;
    }

    /**
     * Getter: idGiocatore
     * @return int
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
            $this->lega = Lega::getById($this->getIdLega());
        return $this->lega;
    }

    /**
     * Getter: utente
     * @return Utente2
     */
    public function getUtente() {
        if (empty($this->utente))
            $this->utente = Utente2::getById($this->getIdUtente());
        return $this->utente;
    }

    /**
     * Getter: id
     * @return Giocatore
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
        return $this->id;
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Selezione[]|Selezione|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Selezione
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Selezione[]|null
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

 
