<?php

namespace Fantamanajer\Models\Table;

abstract class UtenteTable extends \Lib\Database\Table {

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
     * @param String $nomeSquadra
     * @return void
     */
    public function setNomeSquadra($nomeSquadra) {
        $this->nomeSquadra = (string) $nomeSquadra;
    }

    /**
     * Setter: cognome
     * @param String $cognome
     * @return void
     */
    public function setCognome($cognome) {
        $this->cognome = (string) $cognome;
    }

    /**
     * Setter: nome
     * @param String $nome
     * @return void
     */
    public function setNome($nome) {
        $this->nome = (string) $nome;
    }

    /**
     * Setter: mail
     * @param String $mail
     * @return void
     */
    public function setMail($mail) {
        $this->mail = (string) $mail;
    }

    /**
     * Setter: mailAbilitata
     * @param Boolean $mailAbilitata
     * @return void
     */
    public function setMailAbilitata($mailAbilitata) {
        $this->mailAbilitata = (boolean) $mailAbilitata;
    }

    /**
     * Setter: username
     * @param String $username
     * @return void
     */
    public function setUsername($username) {
        $this->username = (string) $username;
    }

    /**
     * Setter: password
     * @param String $password
     * @return void
     */
    public function setPassword($password) {
        $this->password = (string) $password;
    }

    /**
     * Setter: amministratore
     * @param Int $amministratore
     * @return void
     */
    public function setAmministratore($amministratore) {
        $this->amministratore = (int) $amministratore;
    }

    /**
     * Setter: chiave
     * @param String $chiave
     * @return void
     */
    public function setChiave($chiave) {
        $this->chiave = $chiave;
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
     * @return String
     */
    public function getNomeSquadra() {
        return (string) $this->nomeSquadra;
    }

    /**
     * Getter: cognome
     * @return String
     */
    public function getCognome() {
        return (string) $this->cognome;
    }

    /**
     * Getter: nome
     * @return String
     */
    public function getNome() {
        return (string) $this->nome;
    }

    /**
     * Getter: mail
     * @return String
     */
    public function getEmail() {
        return (string) $this->email;
    }

    /**
     * Getter: mailAbilitata
     * @return Boolean
     */
    public function isMailAbilitata() {
        return (boolean) $this->mailAbilitata;
    }

    /**
     * Getter: username
     * @return String
     */
    public function getUsername() {
        return (string) $this->username;
    }

    /**
     * Getter: password
     * @return String
     */
    public function getPassword() {
        return (string) $this->password;
    }

    /**
     * Getter: amministratore
     * @return Integer
     */
    public function getAmministratore() {
        return (int) $this->amministratore;
    }

    /**
     * Getter: chiave
     * @return String
     */
    public function getChiave() {
        return $this->chiave;
    }

    /**
     * Getter: idLega
     * @return Integer
     */
    public function getIdLega() {
        return (int) $this->idLega;
    }

    /**
     * Getter: Lega
     * @return Lega
     */
    public function getLega() {
        require_once(INCDBDIR . 'lega.db.inc.php');
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
     * @param type $key
     * @param type $value
     * @return Utente[]|Utente|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Utente
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Utente[]|NULL
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

?>
