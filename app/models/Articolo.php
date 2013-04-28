<?php

namespace Fantamanajer\Models;
use Lib\Database as Db;

class Articolo extends Table\ArticoloTable {

    public function save(array $parameters = array()) {
        try {
            Db\ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            parent::save($parameters);
            $evento = new Evento();
            $evento->setTipo(Evento::CONFERENZASTAMPA);
            $evento->setData($this->getDataCreazione());
            $evento->setIdUtente($this->getIdUtente());
            $evento->setIdLega($this->getIdLega());
            $evento->setIdExternal($this->getId());
            $evento->save();
            Db\ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            Db\ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
        return TRUE;
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
				WHERE idGiornata = :idGiornata AND utente.idLega = :idLega";
        $exe =Db\ ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(':idGiornata', $idGiornata, \PDO::PARAM_INT);
        $exe->bindValue(':idLega', $idLega, \PDO::PARAM_INT);
        $exe->execute();
        \FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__))
            $values[$obj->getId()] = $obj;
        return $values;
    }

    /**
     *
     * @param int $number
     * @return Articolo[]
     */
    public static function getLastArticoli($number) {
        $q = "SELECT articolo.*,utente.username
				FROM articolo INNER JOIN utente ON articolo.idUtente = utente.id
				ORDER BY dataCreazione DESC
				LIMIT 0,:number";
        $exe = Db\ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(':number', $number, \PDO::PARAM_INT);
        $exe->execute();
        \FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__))
            $values[$obj->getId()] = $obj;
        return $values;
    }

    /**
     *
     * @param int $idLega
     * @return int[]
     */
    public static function getGiornateArticoliExist($idLega) {
        $q = "SELECT DISTINCT(idGiornata) as idGiornata
				FROM articolo
				WHERE idLega = :idLega";
        $exe = Db\ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(':idLega', $idLega, PDO::PARAM_INT);
        $exe->execute();
        \FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject())
            $values[$obj->idGiornata] = $obj->idGiornata;
        return $values;
    }

    public function check(array $parameters) {
        if (empty($this->titolo) || empty($this->testo))
            throw new \Lib\FormException('Non hai compilato correttamente tutti i campi');
        return TRUE;
    }

}

?>
