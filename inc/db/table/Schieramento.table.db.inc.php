<?php

require_once(MODELDIR . 'Schieramento.model.db.inc.php');

class SchieramentoTable extends SchieramentoModel {

    const TABLE_NAME = 'schieramento';

    /**
     *
     * @var int
     */
    var $id;

    /**
     *
     * @var int
     */
    var $idFormazione;

    /**
     *
     * @var int
     */
    var $idGiocatore;

    /**
     *
     * @var int
     */
    var $posizione;

    /**
     *
     * @var int 0 = non ha giocato, 1 = giocato, 2 = capitano
     */
    var $considerato;

    public function __construct() {
        $this->id = is_null($this->id) ? NULL : $this->getId();
        $this->idFormazione = is_null($this->idFormazione) ? NULL : $this->getIdFormazione();
        $this->idGiocatore = is_null($this->idGiocatore) ? NULL : $this->getIdGiocatore();
        $this->posizione = is_null($this->posizione) ? NULL : $this->getPosizione();
        $this->considerato = is_null($this->considerato) ? NULL : $this->getConsiderato();
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
     * Setter: idFormazione
     * @param Int $idFormazione
     * @return void
     */
    public function setIdFormazione($idFormazione) {
        $this->idFormazione = (int) $idFormazione;
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
     * Setter: posizione
     * @param Int $posizione
     * @return void
     */
    public function setPosizione($posizione) {
        $this->posizione = (int) $posizione;
    }

    /**
     * Setter: considerato
     * @param Int $considerato
     * @return void
     */
    public function setConsiderato($considerato) {
        $this->considerato = (int) $considerato;
    }

    /**
     * Setter: formazione
     * @param Formazione $formazione
     * @return void
     */
    public function setFormazione($formazione) {
        $this->formazione = $formazione;
        $this->setIdFormazione($formazione->getId());
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
     * Getter: id
     * @return Int
     */
    public function getId() {
        return (int) $this->id;
    }

    /**
     * Getter: idFormazione
     * @return Int
     */
    public function getIdFormazione() {
        return (int) $this->idFormazione;
    }

    /**
     * Getter: idGiocatore
     * @return Int
     */
    public function getIdGiocatore() {
        return (int) $this->idGiocatore;
    }

    /**
     * Getter: posizione
     * @return Int
     */
    public function getPosizione() {
        return (int) $this->posizione;
    }

    /**
     * Getter: considerato
     * @return Int
     */
    public function getConsiderato() {
        return (int) $this->considerato;
    }

    /**
     * Getter: formazione
     * @return Int
     */
    public function getFormazione() {
        require_once(INCDBDIR . 'formazione.db.inc.php');
        if (empty($this->formazione))
            $this->formazione = Formazione::getById($this->getIdFormazione());
        return $this->formazione;
    }

    /**
     * Getter: giocatore
     * @return Int
     */
    public function getGiocatore() {
        require_once(INCDBDIR . 'giocatore.db.inc.php');
        if (empty($this->giocatore))
            $this->giocatore = Giocatore::getById($this->getIdGiocatore());
        return $this->giocatore;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->idGiocatore;
    }

}

?>
