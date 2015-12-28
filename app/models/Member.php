<?php

namespace Fantamanajer\Models;

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
		$evento->setData(new \DateTime());
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

    public static function getGiocatoriByIdSquadraAndRuolo($idUtente, $ruolo) {
        $q = "SELECT giocatore.id, cognome, nome, ruolo, idUtente
				FROM giocatore INNER JOIN squadra ON giocatore.id = squadra.idGiocatore
				WHERE idUtente = :idUtente AND ruolo = :ruolo AND giocatore.attivo = :attivo
				ORDER BY giocatore.id ASC";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, PDO::PARAM_INT);
        $exe->bindValue(":ruolo", $ruolo);
        $exe->bindValue(":attivo", TRUE, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    }

    

    public static function getGiocatoreByIdWithStats($idGiocatore, $idLega = NULL) {
        $q = "SELECT view_0_giocatoristatistiche.*,idLega
				FROM (SELECT *
						FROM squadra
						WHERE idLega = :idLega) AS squad RIGHT JOIN view_0_giocatoristatistiche ON squad.idGiocatore = view_0_giocatoristatistiche.id
				WHERE view_0_giocatoristatistiche.id = :idGiocatore";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idLega", $idLega, PDO::PARAM_INT);
        $exe->bindValue(":idGiocatore", $idGiocatore, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->fetchObject(__CLASS__);
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

    public static function updateTabGiocatore($path) {
        $ruoli = array("P", "D", "C", "A");
        $giocatoriOld = self::getList();
        $giocatoriNew = FileSystem::returnArray($path, ";");
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            foreach ($giocatoriNew as $id => $giocatoreNew) {
                if (array_key_exists($id, $giocatoriOld)) {
                    $clubNew = Club::getByField('nome', ucwords(strtolower(trim($giocatoreNew[3], '"'))));
                    if ($giocatoriOld[$id]->getIdClub() != $clubNew->getId()) {
                        $giocatoriOld[$id]->setClub($clubNew);
                        $giocatoriOld[$id]->setAttivo(TRUE);
                        $giocatoriOld[$id]->save(array('numEvento'=>Evento::CAMBIOCLUB));
                    }
                } else {
                    $giocatoreOld = new Giocatore();
                    $giocatoreOld->setId($giocatoreNew[0]);
                    $giocatoreOld->setRuolo($ruoli[$giocatoreNew[5]]);
                    $giocatoreOld->setClub(Club::getByField('nome', trim($giocatoreNew[3], '"')));
                    $esprex = "/[A-Z']*\s?[A-Z']{2,}/";
                    $nominativo = trim($giocatoreNew[2], '"');
                    $ass = NULL;
                    preg_match($esprex, $nominativo, $ass);
                    $cognome = ucwords(strtolower(((!empty($ass)) ? $ass[0] : $nominativo)));
                    $nome = ucwords(strtolower(trim(substr($nominativo, strlen($cognome)))));
                    $giocatoreOld->setCognome($cognome);
                    $giocatoreOld->setNome($nome);
                    $giocatoreOld->setAttivo(TRUE);
                    $giocatoreOld->save(array('numEvento'=>Evento::NUOVOGIOCATORE));
                }
            }
            foreach ($giocatoriOld as $id => $giocatoreOld) {
                if (!array_key_exists($id, $giocatoriNew) && $giocatoreOld->isAttivo()) {
                    $giocatoreOld->setAttivo(0);
                    $giocatoreOld->save(array('numEvento'=>Evento::RIMOSSOGIOCATORE));
                }
            }
            ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
        return TRUE;
    }

    public static function getGiocatoriNotSquadra($idUtente, $idLega) {
        $q = "SELECT giocatore.id, cognome, nome, ruolo, idUtente
				FROM giocatore LEFT JOIN squadra ON giocatore.id = squadra.idGiocatore
				WHERE idLega = :idLega AND idUtente <> :idUtente OR idUtente IS NULL
				ORDER BY giocatore.id ASC";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idLega", $idLega, PDO::PARAM_INT);
        $exe->bindValue(":idUtente", $idUtente, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__)) {
            $values[$obj->getId()] = $obj;
        }
        return $values;
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

    public static function getInactiveByTeam($team) {
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

    public static function getBestPlayerByGiornataAndRuolo($idGiornata, $ruolo) {
        $q = "SELECT giocatore.*,punti
				FROM " . self::TABLE_NAME . " INNER JOIN voto ON giocatore.id = voto.idGiocatore
				WHERE idGiornata = :idGiornata AND ruolo = :ruolo
				ORDER BY punti DESC , voto DESC
				LIMIT 0 , 5";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idGiornata", $idGiornata, PDO::PARAM_INT);
        $exe->bindValue(":ruolo", $ruolo);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__)) {
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

}

 