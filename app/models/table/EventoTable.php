<?php

namespace Fantamanajer\Models\Table;

abstract class EventoTable extends \Fantamanajer\Database\Table {

    const TABLE_NAME = 'evento';

    /**
     *
     * @var int
     */
    public $idUtente;

    /**
     *
     * @var int
     */
    public $idLega;

    /**
     *
     * @var DateTime
     */
    public $data;

    /**
     *
     * @var int
     */
    public $tipo;

    /**
     *
     * @var int
     */
    public $idExternal;

    public function __construct() {
        parent::__construct();
        $this->idUtente = is_null($this->idUtente) ? NULL : $this->getIdUtente();
        $this->idLega = is_null($this->idLega) ? NULL : $this->getIdLega();
        $this->data = is_null($this->data) ? NULL : $this->getData();
        $this->tipo = is_null($this->tipo) ? NULL : $this->getTipo();
        $this->idExternal = is_null($this->idExternal) ? NULL : $this->getIdExternal();
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
     * Setter: idLega
     * @param Int $idLega
     * @return void
     */
    public function setIdLega($idLega) {
        $this->idLega = (int) $idLega;
    }

    /**
     * Setter: data
     * @param DateTime $data
     * @return void
     */
    public function setData($data) {
        if (is_a($data, "DateTime"))
            $this->data = $data;
        else
            $this->data = new DateTime($data);
    }

    /**
     * Setter: tipo
     * @param Int $tipo
     * @return void
     */
    public function setTipo($tipo) {
        $this->tipo = (int) $tipo;
    }

    /**
     * Setter: idExternal
     * @param Int $idExternal
     * @return void
     */
    public function setIdExternal($idExternal) {
        $this->idExternal = (int) $idExternal;
    }

    /**
     * Setter: lega
     * @param Lega $lega
     * @return void
     */
    public function setLega($lega) {
        $this->lega = $lega;
        $this->setIdLega($lega->getIdLega());
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
     * Getter: idUtente
     * @return Int
     */
    public function getIdUtente() {
        return (int) $this->idUtente;
    }

    /**
     * Getter: idLega
     * @return Int
     */
    public function getIdLega() {
        return (int) $this->idLega;
    }

    /**
     * Getter: data
     * @return DateTime
     */
    public function getData() {
        if (is_a($this->data, "DateTime"))
            return $this->data;
        else
            return new DateTime($this->data);
    }

    /**
     * Getter: tipo
     * @return Int
     */
    public function getTipo() {
        return (int) $this->tipo;
    }

    /**
     * Getter: idExternal
     * @return Int
     */
    public function getIdExternal() {
        return (int) $this->idExternal;
    }

    /**
     * Getter: Utente
     * @return Utente
     */
    public function getLega() {
        require_once(INCDIR . 'utente.db.inc.php');
        if (empty($this->utente))
            $this->utente = Utente::getById($this->getIdUtente());
        return $this->utente;
    }

    /**
     * Getter: Giornata
     * @return Giornata
     */
    public function getGiornata() {
        require_once(INCDIR . 'giornata.db.inc.php');
        if (empty($this->giornata))
            $this->giornata = Giornata::getById($this->getIdGiornata());
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
     * @return Evento[]|Evento|NULL
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param type $id
     * @return Evento
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param type $ids
     * @return Evento[]|NULL
     */
    public static function getByIds($ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return Evento[]
     */
    public static function getList() {
        return parent::getList();
    }

}

?>
