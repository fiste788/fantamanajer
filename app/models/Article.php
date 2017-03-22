<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\ArticlesTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use Lib\FormException;
use PDO;
use PDOException;

class Article extends ArticlesTable {

    public function save(array $parameters = array()) {
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            $id = $this->getId();
            parent::save($parameters);
            if(is_null($id)) {
                $event = new Event();
                $event->setType(Event::CONFERENZASTAMPA);
                $event->setCreatedAt($this->getCreatedAt());
                $event->setTeamId($this->getTeamId());
                $event->setExternal($this->getId());
                $event->save();
            }
            ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
        return TRUE;
    }

    public function delete() {
        $id = $this->getId();
        if (parent::delete()) {
            return Event::deleteByIdExternalAndType($id, Event::CONFERENZASTAMPA);
        }
    }
    
    public static function getByChampionship($championship_id) {
        $q = "SELECT " . self::TABLE_NAME . ".*,teams.name as team_name
		FROM " . self::TABLE_NAME . " INNER JOIN teams ON articles.team_id = teams.id
		WHERE teams.championship_id = :championship_id
                ORDER BY created_at DESC";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(':championship_id', $championship_id, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__)) {
            $obj->team = new Team();
            $obj->team->getFromObject($obj);
            $values[$obj->getId()] = $obj;
        }
        return $values;
    }
    
    public static function getByTeam($team_id) {
        $q = "SELECT " . self::TABLE_NAME . ".*,teams.name as team_name
		FROM " . self::TABLE_NAME . " INNER JOIN teams ON articles.team_id = teams.id
		WHERE articles.team_id = :team_id
                ORDER BY created_at DESC";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(':team_id', $team_id, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__)) {
            $obj->team = new Team();
            $obj->team->getFromObject($obj);
            $values[$obj->getId()] = $obj;
        }
        return $values;
    }

    /**
     *
     * @param int $number
     * @return Articolo[]
     */
    public static function getLast($number) {
        $q = "SELECT " . self::TABLE_NAME . ".*,teams.name as team_name
		FROM " . self::TABLE_NAME . " INNER JOIN teams ON articles.team_id = teams.id
		ORDER BY created_at DESC
		LIMIT 0,:number";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(':number', $number, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__)) {
            $obj->team = new Team();
            $obj->team->getFromObject($obj);
            $values[$obj->getId()] = $obj;
        }
        return $values;
    }

    public function check(array $parameters) {
        if (empty($this->title) || empty($this->body)) {
            throw new FormException('Non hai compilato correttamente tutti i campi');
        }
        return TRUE;
    }

}

 