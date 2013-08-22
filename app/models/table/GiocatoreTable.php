<?php

namespace Fantamanajer\Models\Table;

abstract class GiocatoreTable extends \Lib\Database\Table {

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
     * @param String $nome
     * @return void
     */
    public function setNome($nome) {
        $this->nome = $nome;
    }

    /**
     * Setter: cognome
     * @param String $cognome
     * @return void
     */
    public function setCognome($cognome) {
        $this->cognome = $cognome;
    }

    /**
     * Setter: ruolo
     * @param String $ruolo
     * @return void
     */
    public function setRuolo($ruolo) {
        $this->ruolo = $ruolo;
    }

    /**
     * Setter: idClub
     * @param Int $idClub
     * @return void
     */
    public function setIdClub($idClub) {
        $this->idClub = (int) $idClub;
    }

    /**
     * Setter: attivo
     * @param Boolean $attivo
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
     * @return String
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * Getter: cognome
     * @return String
     */
    public function getCognome() {
        return $this->cognome;
    }

    /**
     * Getter: ruolo
     * @return String
     */
    public function getRuolo() {
        return $this->ruolo;
    }

    /**
     * Getter: idClub
     * @return Int
     */
    public function getIdClub() {
        return (int) $this->idClub;
    }

    /**
     * Getter: attivo
     * @return Boolean
     */
    public function isAttivo() {
        return (boolean) $this->attivo;
    }

    /**
     * Getter: club
     * @return Club
     */
    public function getClub() {
        require_once(INCDBDIR . 'club.db.inc.php');
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
     * @param type $key
     * @param type $value
     * @return Giocatore[]|Giocatore|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Giocatore
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Giocatore[]|NULL
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

 
