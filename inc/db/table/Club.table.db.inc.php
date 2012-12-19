<?php

require_once(MODELDIR . 'Club.model.db.inc.php');

abstract class ClubTable extends ClubModel {

    const TABLE_NAME = 'club';

    /**
     *
     * @var string
     */
    public $nome;

    /**
     *
     * @var string
     */
    public $partitivo;

    /**
     *
     * @var string
     */
    public $determinativo;

    public function __construct() {
        parent::__construct();
        $this->nome = is_null($this->nome) ? NULL : $this->getNome();
        $this->partitivo = is_null($this->partitivo) ? NULL : $this->getPartitivo();
        $this->determinativo = is_null($this->determinativo) ? NULL : $this->getPartitivo();
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
     * Setter: partitivo
     * @param string $partitivo
     * @return void
     */
    public function setPartitivo($partitivo) {
        $this->partitivo = $partitivo;
    }

    /**
     * Setter: determinativo
     * @param string $determinativo
     * @return void
     */
    public function setDeterminativo($determinativo) {
        $this->determinativo = $determinativo;
    }

    /**
     * Getter: nome
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * Getter: partitivo
     * @return string
     */
    public function getPartitivo() {
        return $this->partitivo;
    }

    /**
     * Getter: determinativo
     * @return string
     */
    public function getDeterminativo() {
        return $this->determinativo;
    }

    /**
     * Getter: giocatori
     * @return Giocatore[]
     */
    public function getGiocatori() {
        require_once(INCDIR . 'GiocatoreStatisticheTable.db.inc.php');
        if (empty($this->giocatori))
            $this->giocatori = GiocatoreStatistiche::getByFields(array('idClub' => $this->getId()));
        return $this->giocatori;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getNome();
    }

}

?>
