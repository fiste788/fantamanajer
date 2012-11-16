<?php

require_once(MODELDIR . 'Voto.model.db.inc.php');

class VotoTable extends VotoModel {

    const TABLE_NAME = "voto";

    /**
     *
     * @var int
     */
    public $id;

    /**
     *
     * @var int
     */
    public $idGiocatore;

    /**
     *
     * @var int
     */
    public $idGiornata;

    /**
     *
     * @var int
     */
    public $valutato;

    /**
     *
     * @var float
     */
    public $punti;

    /**
     *
     * @var float
     */
    public $voto;

    /**
     *
     * @var int
     */
    public $gol;

    /**
     *
     * @var int
     */
    public $golSubiti;

    /**
     *
     * @var int
     */
    public $golVittoria;

    /**
     *
     * @var int
     */
    public $golPareggio;

    /**
     *
     * @var int
     */
    public $assist;

    /**
     *
     * @var bool
     */
    public $ammonito;

    /**
     *
     * @var bool
     */
    public $espulso;

    /**
     *
     * @var int
     */
    public $rigoriSegnati;

    /**
     *
     * @var int
     */
    public $rigoriSubiti;

    /**
     *
     * @var boolean
     */
    public $presente;

    /**
     *
     * @var boolean
     */
    public $titolare;

    /**
     *
     * @var int
     */
    public $quotazione;

    public function __construct() {
        $this->id = is_null($this->id) ? NULL : $this->getId();
        $this->idGiocatore = is_null($this->idGiocatore) ? NULL : $this->getIdGiocatore();
        $this->idGiornata = is_null($this->idGiornata) ? NULL : $this->getIdGiornata();
        $this->valutato = is_null($this->valutato) ? NULL : $this->isValutato();
        $this->punti = is_null($this->punti) ? NULL : $this->getPunti();
        $this->voto = is_null($this->voto) ? NULL : $this->getVoto();
        $this->gol = is_null($this->gol) ? NULL : $this->getGol();
        $this->golSubiti = is_null($this->golSubiti) ? NULL : $this->getGolSubiti();
        $this->golVittoria = is_null($this->golVittoria) ? NULL : $this->getGolVittoria();
        $this->golPareggio = is_null($this->golPareggio) ? NUL : $this->getGolPareggio();
        $this->assist = is_null($this->assist) ? NULL : $this->getAssist();
        $this->ammonito = is_null($this->ammonito) ? NULL : $this->isAmmonito();
        $this->espulso = is_null($this->espulso) ? NULL : $this->isEspulso();
        $this->rigoriSegnati = is_null($this->rigoriSegnati) ? NULL : $this->getRigoriSegnati();
        $this->rigoriSubiti = is_null($this->rigoriSubiti) ? NULL : $this->getRigoriSubiti();
        $this->presente = is_null($this->presente) ? NULL : $this->isPresente();
        $this->titolare = is_null($this->titolare) ? NULL : $this->isTitolare();
        $this->quotazione = is_null($this->quotazione) ? NULL : $this->getQuotazione();
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
     * Setter: idGiocatore
     * @param Int $idGiocatore
     * @return void
     */
    public function setIdGiocatore($idGiocatore) {
        $this->idGiocatore = (int) $idGiocatore;
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
     * Setter: valutato
     * @param boolean $valutato
     * @return void
     */
    public function setValutato($valutato) {
        $this->valutato = (boolean) $valutato;
    }

    /**
     * Setter: punti
     * @param Float $punti
     * @return void
     */
    public function setPunti($punti) {
        $this->punti = (float) $punti;
    }

    /**
     * Setter: voto
     * @param Float $voto
     * @return void
     */
    public function setVoto($voto) {
        $this->voto = (float) $voto;
    }

    /**
     * Setter: gol
     * @param Int $gol
     * @return void
     */
    public function setGol($gol) {
        $this->gol = (int) $gol;
    }

    /**
     * Setter: golSubiti
     * @param Int $golSubiti
     * @return void
     */
    public function setGolSubiti($golSubiti) {
        $this->golSubiti = (int) $golSubiti;
    }

    /**
     * Setter: golVittoria
     * @param Int $golVittoria
     * @return void
     */
    public function setGolVittoria($golVittoria) {
        $this->golVittoria = (int) $golVittoria;
    }

    /**
     * Setter: golPareggio
     * @param Int $golPareggio
     * @return void
     */
    public function setGolPareggio($golPareggio) {
        $this->golPareggio = (int) $golPareggio;
    }

    /**
     * Setter: assist
     * @param Int $assist
     * @return void
     */
    public function setAssist($assist) {
        $this->assist = (int) $assist;
    }

    /**
     * Setter: ammonito
     * @param bool $ammonito
     * @return void
     */
    public function setAmmonito($ammonito) {
        $this->ammonito = (bool) $ammonito;
    }

    /**
     * Setter: espulso
     * @param Int $espulso
     * @return void
     */
    public function setEspulso($espulso) {
        $this->espulso = (int) $espulso;
    }

    /**
     * Setter: rigoriSegnati
     * @param Int $rigoriSegnati
     * @return void
     */
    public function setRigoriSegnati($rigoriSegnati) {
        $this->rigoriSegnati = (int) $rigoriSegnati;
    }

    /**
     * Setter: rigoriSubiti
     * @param Int $rigoriSubiti
     * @return void
     */
    public function setRigoriSubiti($rigoriSubiti) {
        $this->rigoriSubiti = (int) $rigoriSubiti;
    }

    /**
     * Setter: presente
     * @param Boolean $presente
     * @return void
     */
    public function setPresente($presente) {
        $this->presente = (boolean) $presente;
    }

    /**
     * Setter: titolare
     * @param Boolean $titolare
     * @return void
     */
    public function setTitolare($titolare) {
        $this->titolare = (boolean) $titolare;
    }

    /**
     * Setter: quotazione
     * @param Int $quotazione
     * @return void
     */
    public function setQuotazione($quotazione) {
        $this->quotazione = (int) $quotazione;
    }

    /**
     * Setter: giocatore
     * @param Giocatore $giocatore
     * @return void
     */
    public function setGiocatore($giocatore) {
        $this->giocatore = $giocatore;
        $this->idGiocatore = $giocatore->getId();
    }

    /**
     * Setter: giornata
     * @param Giornata $giornata
     * @return void
     */
    public function setGiornata($giornata) {
        $this->giornata = $giornata;
        $this->idGiornata = $giornata->getId();
    }

    /**
     * Getter: id
     * @return Int
     */
    public function getId() {
        return (int) $this->id;
    }

    /**
     * Getter: idGiocatore
     * @return Int
     */
    public function getIdGiocatore() {
        return (int) $this->idGiocatore;
    }

    /**
     * Getter: idGiornata
     * @return Int
     */
    public function getIdGiornata() {
        return (int) $this->idGiornata;
    }

    /**
     * Getter: valutato
     * @return Boolean
     */
    public function isValutato() {
        return (boolean) $this->valutato;
    }

    /**
     * Getter: punti
     * @return Float
     */
    public function getPunti() {
        return (float) $this->punti;
    }

    /**
     * Getter: voto
     * @return Float
     */
    public function getVoto() {
        return (float) $this->voto;
    }

    /**
     * Getter: gol
     * @return Int
     */
    public function getGol() {
        return (int) $this->gol;
    }

    /**
     * Getter: golSubiti
     * @return Int
     */
    public function getGolSubiti() {
        return (int) $this->golSubiti;
    }

    /**
     * Getter: golVittoria
     * @return Int
     */
    public function getGolVittoria() {
        return (int) $this->golVittoria;
    }

    /**
     * Getter: golPareggio
     * @return Int
     */
    public function getGolPareggio() {
        return (int) $this->golPareggio;
    }

    /**
     * Getter: assist
     * @return Int
     */
    public function getAssist() {
        return (int) $this->assist;
    }

    /**
     * Getter: ammonito
     * @return bool
     */
    public function isAmmonito() {
        return (bool) $this->ammonito;
    }

    /**
     * Getter: espulso
     * @return bool
     */
    public function isEspulso() {
        return (bool) $this->espulso;
    }

    /**
     * Getter: rigoriSegnati
     * @return Int
     */
    public function getRigoriSegnati() {
        return (int) $this->rigoriSegnati;
    }

    /**
     * Getter: rigoriSubiti
     * @return Int
     */
    public function getRigoriSubiti() {
        return (int) $this->rigoriSubiti;
    }

    /**
     * Getter: presente
     * @return Boolean
     */
    public function isPresente() {
        return (boolean) $this->presente;
    }

    /**
     * Getter: titolare
     * @return Boolean
     */
    public function isTitolare() {
        return (boolean) $this->titolare;
    }

    /**
     * Getter: quotazione
     * @return Int
     */
    public function getQuotazione() {
        return (int) $this->quotazione;
    }

    /**
     * Getter: id
     * @return Giocatore
     */
    public function getGiocatore() {
        require_once(INCDBDIR . 'GiocatoreStatisticheTable.db.inc.php');
        if (empty($this->giocatore))
            $this->giocatore = GiocatoreStatistiche::getByField('id', $this->getIdGiocatore());
        return $this->giocatore;
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
        return $this->getId();
    }

}

?>
