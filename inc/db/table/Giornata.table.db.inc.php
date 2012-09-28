<?php

require_once(MODELDIR . 'Giornata.model.db.inc.php');

class GiornataTable extends GiornataModel {

    const TABLE_NAME = "giornata";

    /**
     *
     * @var int
     */
    var $id;

    /**
     *
     * @var DateTime
     */
    var $dataInizio;

    /**
     *
     * @var DateTime
     */
    var $dataFine;

    public function __construct() {
        $this->id = is_null($this->id) ? NULL : $this->getId();
        $this->dataInizio = is_null($this->dataFine) ? NULL : $this->getDataInizio();
        $this->dataFine = is_null($this->dataFine) ? NULL : $this->getDataFine();
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
     * Setter: dataInizio
     * @param DateTime $dataInizio
     * @return void
     */
    public function setDataInizio($dataInizio) {
        if (is_a($this->dataInizio, "DateTime"))
            $this->dataInizio = $dataInizio;
        else
            $this->dataInizio = new DateTime($dataInizio);
    }

    /**
     * Setter: dataFine
     * @param DateTime $dataFine
     * @return void
     */
    public function setDataFine($dataFine) {
        if (is_a($this->dataFine, "DateTime"))
            $this->dataFine = $dataFine;
        else
            $this->dataFine = new DateTime($dataFine);
    }

    /**
     * Getter: id
     * @return Int
     */
    public function getId() {
        return (int) $this->id;
    }

    /**
     * Getter: dataInizio
     * @return String
     */
    public function getDataInizio() {
        if (is_a($this->dataInizio, "DateTime"))
            return $this->dataInizio;
        else
            return new DateTime($this->dataInizio);
    }

    /**
     * Getter: dataFine
     * @return String
     */
    public function getDataFine() {
        if (is_a($this->dataFine, "DateTime"))
            return $this->dataFine;
        else
            return new DateTime($this->dataFine);
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
