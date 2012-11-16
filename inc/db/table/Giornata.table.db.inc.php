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
    var $data;

    public function __construct() {
        $this->id = is_null($this->id) ? NULL : $this->getId();
        $this->data = is_null($this->data) ? NULL : $this->getData();
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
     * Setter: data
     * @param DateTime $data
     * @return void
     */
    public function setData($data) {
        $this->data = is_a($this->data, "DateTime") ? $data : new DateTime($data);
    }

    /**
     * Getter: id
     * @return Int
     */
    public function getId() {
        return (int) $this->id;
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
        return $this->getId();
    }

}

?>
