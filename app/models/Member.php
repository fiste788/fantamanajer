<?php

namespace Fantamanajer\Models;

use DateTime;
use Fantamanajer\Lib\FileSystem;
use Fantamanajer\Models\Table\MembersTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use PDO;
use PDOException;

class Member extends MembersTable {

    public function save(array $parameters = NULL) {
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            parent::save($parameters);
            if (!is_null($parameters)) {
                $evento = new Evento();
                $evento->setIdExternal($this->id);
		$evento->setData(new DateTime());
                $evento->setTipo($parameters['numEvento']);
                $evento->save();
            }
            ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
        return TRUE;
    }

    public static function getGiocatoriByIdSquadra($idUtente) {
        $q = "SELECT giocatore.id, cognome, nome, ruolo, idUtente
				FROM giocatore INNER JOIN squadra ON giocatore.id = squadra.idGiocatore
				WHERE idUtente = :idUtente
				ORDER BY ruolo DESC,cognome ASC";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__)) {
            $values[$obj->getId()] = $obj;
        }
        return $values;
    }

    public static function getLineupDetailsByMatchdayAndTeam(Matchday $matchday, Team $team) {
        $q = "SELECT *
		FROM view_0_lineups_details
		WHERE matchday_id = :matchday_id AND team_id = :team_id ORDER BY posizione";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":matchday_id", $matchday->getId(), PDO::PARAM_INT);
        $exe->bindValue(":team_id", $team->getId(), PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        //$elenco = $exe->fetchAll(PDO::FETCH_CLASS, __CLASS__);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__)) {
            $obj->member = new Member();
            $obj->member->getFromObject($obj);
            $obj->player = new Player();
            $obj->player->getFromObject($obj);
            $obj->disposition = new Disposition();
            $obj->disposition->getFromObject($obj);
            $obj->club = new Club();
            $obj->club->getFromObject($obj);
            $obj->role = new Role();
            $obj->role->getFromObject($obj);
            $values[$obj->getId()] = $obj;
        }
        return $values;
    }

    public static function updateTable(Season $season,$path) {
        $roles = Role::getListByAbbreviation();
        $oldMembers = self::getBySeason($season);
        $newMembers = FileSystem::returnArray($path, ";");
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            foreach ($newMembers as $id => $newMember) {
                if (array_key_exists($id, $oldMembers)) {
                    $clubNew = Club::getByField('name', ucwords(strtolower(trim($newMember[3], '"'))));
                    if ($oldMembers[$id]->getClubId() != $clubNew->getId()) {
                        $oldMembers[$id]->setClub($clubNew);
                        $oldMembers[$id]->setActive(TRUE);
                        $oldMembers[$id]->save(array('numEvent'=> Event::CAMBIOCLUB));
                    }
                } else {
                    $oldMember = new Member();
                    $oldMember->setId($newMember[0]);
                    $oldMember->setRole($roles[$newMember[5]]);
                    $oldMember->setClub(Club::getByField('name', trim($newMember[3], '"')));
                    $esprex = "/[A-Z']*\s?[A-Z']{2,}/";
                    $fullname = trim($newMember[2], '"');
                    $ass = NULL;
                    preg_match($esprex, $fullname, $ass);
                    $surname = ucwords(strtolower(((!empty($ass)) ? $ass[0] : $fullname)));
                    $name = ucwords(strtolower(trim(substr($fullname, strlen($surname)))));
                    $player = Player::getByFullname($surname, $name);
                    if($player == NULL) {
                        $player = new Player();
                        $player->setName($name);
                        $player->setSurname($surname);
                        $player->save();
                    }
                    $oldMember->setPlayerId($player->getId());
                    $oldMember->setActive(TRUE);
                    $oldMember->save(array('numEvento'=>Event::NUOVOGIOCATORE));
                }
            }
            foreach ($oldMembers as $id => $oldMember) {
                if (!array_key_exists($id, $newMembers) && $oldMember->isActivo()) {
                    $oldMember->setActiv(FALSE);
                    $oldMember->save(array('numEvento'=>Event::RIMOSSOGIOCATORE));
                }
            }
            ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
        return TRUE;
    }

    public static function getGiocatoriBySquadraAndGiornata($idUtente, $idGiornata) {
        $giocatori = self::getGiocatoriByIdSquadra($idUtente);
        $trasferimenti = Trasferimento::getTrasferimentiByIdSquadra($idUtente, $idGiornata);
        if (!empty($trasferimenti)) {
            $sort_arr = array();
            foreach ($trasferimenti as $uniqid => $row) {
                foreach ($row as $key => $value) {
                    $sort_arr[$key][$uniqid] = $value;
                }
            }
            array_multisort($sort_arr['idGiornata'], SORT_DESC, $trasferimenti);
            foreach ($trasferimenti as $key => $val) {
                foreach ($giocatori as $key2 => $val2) {
                    if ($val2->id == $val->idGiocatoreNew) {
                        $giocatori[$key2] = self::getById($val->idGiocatoreOld);
                    }
                }
            }
            $sort_arr2 = array();
            foreach ($giocatori as $uniqid => $row) {
                foreach ($row as $key => $value) {
                    $sort_arr2[$key][$uniqid] = $value;
                }
            }
            array_multisort($sort_arr['cognome'], SORT_ASC, $giocatori);
        }
        return $giocatori;
    }

    public static function getInactiveByTeam(Team $team) {
        $q = "SELECT *
		FROM " . self::TABLE_NAME . " INNER JOIN members_teams ON members.id = members_teams.member_id
		WHERE team_id = :team_id AND active = :active";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":team_id", $team->getId(), PDO::PARAM_INT);
        $exe->bindValue(":active", FALSE, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__)) {
            $values[$obj->getId()] = $obj;
        }
        return $values;
    }

    public static function getBestByMatchdayIdAndRole($matchdayId, Role $role) {
        $q = "SELECT members.*,players.name as player_name, players.surname as player_surname,points
		FROM " . self::TABLE_NAME . " INNER JOIN ratings ON members.id = ratings.member_id
                    INNER JOIN players ON members.player_id = players.id
		WHERE matchday_id = :matchday_id AND role_id = :role_id
		ORDER BY points DESC , rating DESC
		LIMIT 0 , 5";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":matchday_id", $matchdayId, PDO::PARAM_INT);
        $exe->bindValue(":role_id", $role->getId(), PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__)) {
            $obj->player = new Player();
            $obj->player->getFromObject($obj);
            $values[$obj->getId()] = $obj;
        }
        return $values;
    }

    /**
     *
     * @param int $giornata
     * @return Voto
     */
    public function getVotoByGiornata($giornata) {
        return Voto::getByGiocatoreAndGiornata($this->getId(), $giornata);
    }

    /**
     *
     * @return Rating[]
     */
    public function getRatings(Season $season) {
        $q = "SELECT ratings.*, matchdays.number as matchday_number, matchdays.date as matchday_date, matchdays.season_id as matchday_season_id
		FROM ratings INNER JOIN matchdays ON ratings.matchday_id = matchdays.id
		WHERE member_id = :member_id AND season_id = :season_id
		ORDER BY matchdays.number ASC";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":member_id", $this->getId(), PDO::PARAM_INT);
        $exe->bindValue(":season_id", $season->getId(), PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(Rating::class)) {
            $obj->matchday = new Matchday();
            $obj->matchday->getFromObject($obj);
            $values[$obj->getId()] = $obj;
        }
        return $values;
        //return Rating::getByField('member_id',$this->id);
    }
    
    /**
     *
     * @return Table[]
     */
    public static function getBySeason(Season $season) {
        $q = "SELECT * FROM " . self::TABLE_NAME . 
                " WHERE season_id = :season_id";
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        $exe->bindColumn("season_id", $season->getId(), PDO::PARAM_INT);
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject($c)) {
            $values[$obj->getCodeGazzetta()] = $obj;
        }
        return $values;
    }

}

 