<?php

require_once(TABLEDIR . 'Articolo.table.db.inc.php');

class Articolo extends ArticoloTable {

    public function save($parameters = NULL) {
        if (($id = parent::save()) != FALSE) {
            if (is_null($this->getId()) || (string) $this->getId() === '') {
                $evento = new Evento();
                $evento->setTipo(Evento::CONFERENZASTAMPA);
                $evento->setData($this->getDataCreazione());
                $evento->setIdUtente($this->getIdUtente());
                $evento->setIdLega($this->getIdLega());
                $evento->setIdExternal($id);
                return $evento->save();
            } else
                return TRUE;
        }
    }

    public function delete() {
        $id = $this->getId();
        if (parent::delete())
            return Evento::deleteEventoByIdExternalAndTipo($id, Evento::CONFERENZASTAMPA);
    }

    /**
     *
     * @param int $idGiornata
     * @param int $idLega
     * @return Articolo[]
     */
    public static function getArticoliByGiornataAndLega($idGiornata, $idLega) {
        $q = "SELECT articolo.*,utente.username
				FROM articolo INNER JOIN utente ON articolo.idUtente = utente.id
				WHERE idGiornata = '" . $idGiornata . "' AND utente.idLega = '" . $idLega . "'";
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__))
            $values[$obj->getId()] = $obj;
        return $values;
    }

    public static function getLastArticoli($number) {
        $q = "SELECT articolo.*,utente.username
				FROM articolo INNER JOIN utente ON articolo.idUtente = utente.id
				ORDER BY dataCreazione DESC
				LIMIT 0," . $number . "";
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__))
            $values[$obj->getId()] = $obj;
        return $values;
    }

    /**
     *
     * @param type $idLega
     * @return type
     */
    public static function getGiornateArticoliExist($idLega) {
        $q = "SELECT DISTINCT(idGiornata) as idGiornata
				FROM articolo
				WHERE idLega = '" . $idLega . "'";
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        $values = array();
        while ($obj = $exe->fetchObject())
            $values[$obj->idGiornata] = $obj->idGiornata;
        return $values;
    }

    public function check($array, $message) {
        $post = (object) $array;
        if (empty($post->titolo) || empty($post->testo))
            throw new FormException('Non hai compilato correttamente tutti i campi');
        return TRUE;
    }

}

?>
