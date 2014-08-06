<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\Lega;
use Fantamanajer\Models\Utente;
use Lib\Database\Table;

abstract class UtenteTable extends Table {

    const TABLE_NAME = "utente";

    /**
     *
     * @var string
     */
    public $nomeSquadra;

    /**
     *
     * @var string
     */
    public $cognome;

    /**
     *
     * @var string
     */
    public $nome;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var boolean
     */
    public $mailAbilitata;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var int
     */
    public $amministratore;

    /**
     *
     * @var string
     */
    public $chiave;

    /**
     *
     * @var int
     */
    public $idLega;

    public function __construct() {
        parent::__construct();
        $this->nomeSquadra = is_null($this->nomeSquadra) ? NULL : $this->getNomeSquadra();
        $this->cognome = is_null($this->cognome) ? NULL : $this->getCognome();
        $this->nome = is_null($this->nome) ? NULL : $this->getNome();
        $this->email = is_null($this->email) ? NULL : $this->getEmail();
        $this->mailAbilitata = is_null($this->mailAbilitata) ? NULL : $this->isMailAbilitata();
        $this->username = is_null($this->username) ? NULL : $this->getUsername();
        $this->password = is_null($this->password) ? NULL : $this->getPassword();
        $this->amministratore = is_null($this->amministratore) ? NULL : $this->getAmministratore();
        $this->chiave = is_null($this->chiave) ? NULL : $this->getChiave();
        $this->idLega = is_null($this->idLega) ? NULL : $this->getIdLega();
    }

    /**
     * Setter: nomeSquadra
     * @param string $nomeSquadra
     * @return void
     */
    public function setNomeSquadra($nomeSquadra) {
        $this->nomeSquadra = (string) $nomeSquadra;
    }

    /**
     * Setter: cognome
     * @param string $cognome
     * @return void
     */
    public function setCognome($cognome) {
        $this->cognome = (string) $cognome;
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
     * Setter: mail
     * @param string $mail
     * @return void
     */
    public function setMail($mail) {
        $this->mail = (string) $mail;
    }

    /**
     * Setter: mailAbilitata
     * @param boolean $mailAbilitata
     * @return void
     */
    public function setMailAbilitata($mailAbilitata) {
        $this->mailAbilitata = (boolean) $mailAbilitata;
    }

    /**
     * Setter: username
     * @param string $username
     * @return void
     */
    public function setUsername($username) {
        $this->username = (string) $username;
    }

    /**
     * Setter: password
     * @param string $password
     * @return void
     */
    public function setPassword($password) {
        $this->password = (string) $password;
    }

    /**
     * Setter: amministratore
     * @param int $amministratore
     * @return void
     */
    public function setAmministratore($amministratore) {
        $this->amministratore = (int) $amministratore;
    }

    /**
     * Setter: chiave
     * @param string $chiave
     * @return void
     */
    public function setChiave($chiave) {
        $this->chiave = $chiave;
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
     * Setter: lega
     * @param Lega $lega
     * @return void
     */
    public function setLega($lega) {
        $this->lega = $lega;
        $this->idLega = $lega->getIdLega();
    }

    /**
     * Getter: nomeSquadra
     * @return string
     */
    public function getNomeSquadra() {
        return (string) $this->nomeSquadra;
    }

    /**
     * Getter: cognome
     * @return string
     */
    public function getCognome() {
        return (string) $this->cognome;
    }

    /**
     * Getter: nome
     * @return string
     */
    public function getNome() {
        return (string) $this->nome;
    }

    /**
     * Getter: mail
     * @return string
     */
    public function getEmail() {
        return (string) $this->email;
    }

    /**
     * Getter: mailAbilitata
     * @return boolean
     */
    public function isMailAbilitata() {
        return (boolean) $this->mailAbilitata;
    }

    /**
     * Getter: username
     * @return string
     */
    public function getUsername() {
        return (string) $this->username;
    }

    /**
     * Getter: password
     * @return string
     */
    public function getPassword() {
        return (string) $this->password;
    }

    /**
     * Getter: amministratore
     * @return int
     */
    public function getAmministratore() {
        return (int) $this->amministratore;
    }

    /**
     * Getter: chiave
     * @return string
     */
    public function getChiave() {
        return $this->chiave;
    }

    /**
     * Getter: idLega
     * @return int
     */
    public function getIdLega() {
        return (int) $this->idLega;
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
     *
     * @return string
     */
    public function __toString() {
        return $this->getUsername();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Utente[]|Utente|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return Utente
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return Utente[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Utente[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
