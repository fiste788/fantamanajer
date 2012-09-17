<?php

require_once(MODELDIR . 'Articolo.model.db.inc.php');

class ArticoloTable extends ArticoloModel {

    const TABLE_NAME = "articolo";

    /**
     *
     * @var int
     */
    public $id;

    /**
     *
     * @var string
     */
    public $titolo;

    /**
     *
     * @var string
     */
    public $sottoTitolo;

    /**
     *
     * @var string
     */
    public $testo;

    /**
     *
     * @var DateTime
     */
    public $dataCreazione;

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
     * @var int
     */
    public $idLega;

    public function __construct() {
        $this->id = is_null($this->id) ? NULL : $this->getId();
        $this->titolo = is_null($this->titolo) ? NULL : $this->getTitolo();
        $this->sottoTitolo = is_null($this->sottoTitolo) ? NULL : $this->getSottoTitolo();
        $this->testo = is_null($this->testo) ? NULL : $this->getTesto();
        $this->dataCreazione = is_null($this->dataCreazione) ? NULL : $this->getDataCreazione();
        $this->idUtente = is_null($this->id) ? NULL : $this->getIdUtente();
        $this->idGiornata = is_null($this->id) ? NULL : $this->getIdGiornata();
        $this->idLega = is_null($this->id) ? NULL : $this->getIdLega();
    }

    /**
     * Setter: id
     * @param Int $id
     * @return void
     */
    public function setId($id) {
        $this->id = (int) $id;
    }

    /**
     * Setter: titolo
     * @param String $titolo
     * @return void
     */
    public function setTitle($titolo) {
        $this->titolo = $titolo;
    }

    /**
     * Setter: sottoTitolo
     * @param String $sottoTitolo
     * @return void
     */
    public function setSottoTitolo($sottoTitolo) {
        $this->sottoTitolo = $sottoTitolo;
    }

    /**
     * Setter: testo
     * @param String $testo
     * @return void
     */
    public function setTesto($testo) {
        $this->testo = $testo;
    }

    /**
     * Setter: dataCreazione
     * @param DateTime $dataCreazione
     * @return void
     */
    public function setDataCreazione($dataCreazione) {
        if (is_a($this->dataCreazione, "DateTime"))
            $this->dataCreazione = $dataCreazione;
        else
            $this->dataCreazione = new DateTime($dataCreazione);
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
     * Setter: utente
     * @param Giornata $giornata
     * @return void
     */
    public function setGiornata($giornata) {
        $this->giornata = $giornata;
        $this->setIdGiornata($giornata->getIdGiornata());
    }

    /**
     * Getter: id
     * @return Int
     */
    public function getId() {
        return (int) $this->id;
    }

    /**
     * Getter: titolo
     * @return String
     */
    public function getTitolo() {
        return $this->titolo;
    }

    /**
     * Getter: sottoTitolo
     * @return String
     */
    public function getSottoTitolo() {
        return $this->sottoTitolo;
    }

    /**
     * Getter: testo
     * @return String
     */
    public function getTesto() {
        return $this->testo;
    }

    /**
     * Getter: dataCreazione
     * @return DateTime
     */
    public function getDataCreazione() {
        return (is_a($this->dataCreazione, "DateTime")) ? $this->dataCreazione : new DateTime($this->dataCreazione);
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
     * Getter: idLega
     * @return Int
     */
    public function getIdLega() {
        return (int) $this->idLega;
    }

    /**
     * Getter: Lega
     * @return Lega
     */
    public function getLega() {
        require_once(INCDIR . 'lega.db.inc.php');
        if (empty($this->lega))
            $this->lega = Lega::getById($this->getIdLega());
        return $this->lega;
    }

    /**
     * Getter: Utente
     * @return Utente
     */
    public function getUtente() {
        require_once(INCDBDIR . 'utente.db.inc.php');
        if (empty($this->utente))
            $this->utente = Utente::getById($this->getIdUtente());
        return $this->utente;
    }

    /**
     * Getter: Giornata
     * @return Giornata
     */
    public function getGiornata() {
        require_once(INCDBDIR . 'giornata.db.inc.php');
        if (empty($this->giornata))
            $this->giornata = Giornata::getById($this->getIdGiornata());
        return $this->giornata;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getTitolo();
    }

}

?>
