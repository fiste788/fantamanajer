<?php

require_once(MODELDIR . 'Giornata.model.db.inc.php');

abstract class GiornataTable extends GiornataModel {

    const TABLE_NAME = "giornata";

    /**
     *
     * @var DateTime
     */
    public $data;

    public function __construct() {
        parent::__construct();
        $this->data = is_null($this->data) ? NULL : $this->getData();
    }

    /**
     * Setter: data
     * @param DateTime $data
     * @return void
     */
    public function setData($data) {
        $this->data = is_a($data, "DateTime") ? $data : new DateTime($data);
    }

    /**
     * Getter: data
     * @return String
     */
    public function getData() {
        return (is_a($this->data, "DateTime")) ? $this->data : new DateTime($this->data);
    }

    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getData();
    }

}

?>
