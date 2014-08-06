<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Club;
use Fantamanajer\Models\Giocatore;
use Lib\Database\Table;

abstract class GiocatoreTable extends Table {

    const TABLE_NAME = "giocatore";

    /**
     *
     * @var string
     */
    public $nome;

    /**
     *
     * @var string
     */
    public $cognome;

    /**
     *
     * @var string
     */
    public $ruolo;

    /**
     *
     * @var int
     */
    public $idClub;

    /**
     *
     * @var boolean
     */
    public $attivo;

    public function __construct() {
        parent::__construct();
        $this->nome = is_null($this->nome) ? NULL : $this->getNome();
        $this->cognome = is_null($this->cognome) ? NULL : $this->getCognome();
        $this->ruolo = is_null($this->ruolo) ? NULL : $this->getRuolo();
        $this->idClub = is_null($this->idClub) ? NULL : $this->getIdClub();
        $this->attivo = is_null($this->attivo) ? NULL : $this->isAttivo();
    }

    /**
     * Setter: nome
     * @param string $nome
     * @return void
     */
    public function setNome($nome) {
        $this->nome = $nome;
    }

    /**
     * Setter: cognome
     * @param string $cognome
     * @return void
     */
    public function setCognome($cognome) {
        $this->cognome = $cognome;
    }

    /**
     * Setter: ruolo
     * @param string $ruolo
     * @return void
     */
    public function setRuolo($ruolo) {
        $this->ruolo = $ruolo;
    }

    /**
     * Setter: idClub
     * @param int $idClub
     * @return void
     */
    public function setIdClub($idClub) {
        $this->idClub = (int) $idClub;
    }

    /**
     * Setter: attivo
     * @param boolean $attivo
     * @return void
     */
    public function setAttivo($attivo) {
        $this->attivo = (boolean) $attivo;
    }

    /**
     * Setter: club
     * @param Club $club
     * @return void
     */
    public function setClub($club) {
        $this->club = $club;
        $this->setIdClub($club->getId());
    }

    /**
     * Getter: nome
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * Getter: cognome
     * @return string
     */
    public function getCognome() {
        return $this->cognome;
    }

    /**
     * Getter: ruolo
     * @return string
     */
    public function getRuolo() {
        return $this->ruolo;
    }

    /**
     * Getter: idClub
     * @return int
     */
    public function getIdClub() {
        return (int) $this->idClub;
    }

    /**
     * Getter: attivo
     * @return boolean
     */
    public function isAttivo() {
        return (boolean) $this->attivo;
    }

    /**
     * Getter: club
     * @return Club
     */
    public function getClub() {
        if (empty($this->club))
            $this->club = Club::getById($this->getIdClub());
        return $this->club;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getCognome() . " " . $this->getNome();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Giocatore[]|Giocatore|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Giocatore
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Giocatore[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Giocatore[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
