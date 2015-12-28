<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\ScoresTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use PDO;
use PDOException;

class Score extends ScoresTable {

    public static function getByTeamAndMatchday(Team $team, Matchday $matchday) {
        $q = "SELECT *
		FROM " . self::TABLE_NAME . "
		WHERE team_id = :team_id AND matchday_id = :matchday_id";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":team_id", $team->getId(), PDO::PARAM_INT);
        $exe->bindValue(":matchday_id", $matchday->getId(), PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->fetchObject(__CLASS__);
    }

    public static function getPosClassificaGiornata($idLega) {
        $q = "SELECT *
				FROM punteggio
				WHERE idLega = :idLega AND punteggio >= 0 ORDER BY idGiornata,punteggio DESC";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idLega", $idLega, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($row = $exe->fetchObject(__CLASS__)) {
            $values[$row->idGiornata][] = $row;
        }
        if (!empty($values)) {
            $appo = array();
            foreach ($values as $giornata => $pos) {
                foreach ($pos as $key => $val) {
                    $appo[$giornata][$val->idUtente] = $key + 1;
                }
            }
            return $appo;
        } else {
            return null;
        }
    }

    public static function getGiornateVinte($idUtente) {
        $q = "SELECT giornateVinte
				FROM giornatevinte
				WHERE idUtente = :idUtente";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->fetchColumn();
    }

    public static function getRankingByMatchday(Championship $championship, Matchday $matchday, $details = FALSE) {
        $q = "SELECT scores.team_id, SUM(scores.points) AS sum_points, AVG(scores.points) AS avg_points, MAX(scores.points) AS max_points, MIN(scores.points) AS punteggioMin, COALESCE(matchday_win,0) as matchday_win
		FROM (scores LEFT JOIN teams ON scores.team_id = teams.id) LEFT JOIN view_1_matchday_win ON scores.team_id = view_1_matchday_win.team_id
		WHERE scores.matchday_id <= :matchday_id AND teams.championship_id = :championship_id
		GROUP BY scores.team_id
		ORDER BY sum_points DESC , matchday_win DESC";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":matchday_id", $matchday->getId(), PDO::PARAM_INT);
        $exe->bindValue(":championship_id", $championship->getId(), PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        if ($details) {
            $det = self::getAllByMatchday($matchday, $championship);
        }
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__)) {
            $values[$obj->getTeamId()] = $obj;
            $values[$obj->getTeamId()]->details = $det[$obj->getTeamId()];
        }
        return $values;
    }

    public static function getAllByMatchday(Matchday $matchday, Championship $championship) {
        $q = "SELECT *
		FROM " . self::TABLE_NAME . " INNER JOIN teams ON " . self::TABLE_NAME . ".team_id = teams.id 
		WHERE (matchday_id <= :matchday_id OR matchday_id IS NULL) AND championship_id = :championship_id
		ORDER BY matchday_id DESC";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":matchday_id", $matchday->getId(), PDO::PARAM_INT);
        $exe->bindValue(":championship_id", $championship->getId(), PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $ranking = array();
        while ($row = $exe->fetchObject(__CLASS__)) {
            /*if (isset($ranking[$row->team_id][$row->matchday_id])) {
                $ranking[$row->team_id][$row->matchday_id] += $row->real_points;
            } else {*/
                $ranking[$row->team_id][$row->matchday_id] = $row;
            //}
        }
        /*$sum = self::getRankingByMatchday($championship, $matchday);
        if (isset($sum)) {
            foreach ($sum as $key => $val) {
                $sum[$key]->matchdays = $ranking[$key];
            }
        } else {
            $teams = Teams::getByField('championship_id', $championship->getId());
            foreach ($teams as $key => $val) {
                $sum[$key]->matchdays[$matchday->getId()] = 0;
            }
        }*/
        return $ranking;
    }

    public static function getMatchdayWithScores() {
        $q = "SELECT MAX(matchday_id) as number_matchday
				FROM " . self::TABLE_NAME;
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        return $exe->fetchColumn();
    }

    protected static function sostituzione($giocatore, &$panchinari, &$cambi, $giornata) {
        for ($i = 0; $i < count($panchinari); $i++) {
            $schieramento = $panchinari[$i];
            $giocatorePanchina = $schieramento->getGiocatore();
            $voto = $giocatorePanchina->getVotoByGiornata($giornata);
            if (($giocatore->getRuolo() == $giocatorePanchina->getRuolo()) && ($voto->isValutato())) {
                array_splice($panchinari, $i, 1);
                $cambi++;
                return $schieramento;
            }
        }
        return FALSE;
    }

    public static function getCapitanoAttivo($formazione) {
        $capitani = array();
        $capitani[] = $formazione->getIdCapitano();
        $capitani[] = $formazione->getIdVCapitano();
        $capitani[] = $formazione->getIdVVCapitano();
        foreach ($capitani as $cap) {
            if (!is_null($cap) && $cap != "") {
                $giocatore = Giocatore::getById($cap);
                $voto = $giocatore->getVotoByGiornata($formazione->getIdGiornata());
                if ($voto->isPresente()) {
                    return $cap;
                }
            }
        }
        return FALSE;
    }

    /**
     *
     * @param Utente $utente
     * @param int $giornata
     * @return boolean
     * @throws PDOException
     */
    public static function calcolaPunti(Utente $utente, $giornata) {
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            $formazione = Formazione::getLastFormazione($utente->id, $giornata);
            $punteggio = self::getByTeamAndMatchday($utente, $giornata);
            $lega = $utente->getLega();
            if ($punteggio == FALSE) {
                $punteggio = new Punteggio();
            }
            if ($formazione == FALSE || ($formazione->getIdGiornata() != $giornata && $lega->getPunteggioFormazioneDimenticata() == 0)) {
                $punteggio->setIdGiornata($giornata);
                $punteggio->setIdUtente($utente->getId());
                $punteggio->setIdLega($lega->getId());
                $punteggio->setPunteggio(0);
                $punteggio->save();
            } else {
                $idUtente = $formazione->getIdUtente();
                if ($formazione->getIdGiornata() != $giornata) {
                    if (!$lega->isCapitanoFormazioneDimenticata()) {
                        $formazione->idCapitano = NULL;
                        $formazione->idVCapitano = NULL;
                        $formazione->idVVCapitano = NULL;
                    }
                    //$formazione = clone $formazione;
                    $formazione->duplicate($giornata);
                }
                $cambi = 0;
                $somma = 0;
                $cap = self::getCapitanoAttivo($formazione);
                $panchinari = $formazione->giocatori;
                $titolari = array_splice($panchinari, 0, 11);
                foreach ($titolari as $schieramento) {
                    $giocatore = $schieramento->getGiocatore();
					//FirePHP::getInstance()->log($giocatore);
                    $voto = $giocatore->getVotoByGiornata($giornata);
                    if ((!$giocatore->isAttivo() || !$voto->isValutato()) && ($cambi < 3)) {
                        FirePHP::getInstance()->log("sostituisco");
                        $sostituto = self::sostituzione($giocatore, $panchinari, $cambi, $giornata);
                        if ($sostituto != FALSE) {
                            if ($schieramento->getConsiderato() != 0) {
                                $schieramento->setConsiderato(0);
                                $schieramento->save();
                            }
                            $schieramento = $sostituto;
                            $giocatore = $schieramento->getGiocatore();
                            $voto = $giocatore->getVotoByGiornata($giornata);
                        }
                    }
                    if ($schieramento) {
                        $schieramento->setConsiderato(1);
                        $punti = $voto->getPunti();
                        FirePHP::getInstance()->log($giocatore->getId() . " " . $cap);
                        FirePHP::getInstance()->log($lega->isCapitano());
                        if ($lega->isCapitano() && $giocatore->getId() == $cap) {
                            FirePHP::getInstance()->log("raddoppio punteggio");
                            $schieramento->setConsiderato(2);
                            $punti *= 2;
                        }
                        $schieramento->save();
                        $somma += $punti;
                    }
                }
                foreach ($panchinari as $schieramento) {
                    if ($schieramento->getConsiderato() != 0) {
                        $schieramento->setConsiderato(0);
                        $schieramento->save();
                    }
                }

                if ($formazione->jolly == 1) {
                    $somma *= 2;
                }
                if (!$punteggio) {
                    $punteggio = new Punteggio();
                }
                $punteggio->setIdGiornata($giornata);
                $punteggio->setIdUtente($idUtente);
                $punteggio->setIdLega($lega->id);
                $punteggio->setPunteggio($somma);
                FirePHP::getInstance()->log("salvo punteggio");
                $punteggio->save();
                if ($lega->getPunteggioFormazioneDimenticata() != 100 && $giornata != $formazione->getIdGiornata()) {
                    $puntiDaTogliere = round((($somma / 100) * (100 - $lega->getPunteggioFormazioneDimenticata())), 1);
                    $modulo = ($puntiDaTogliere * 10) % 5;
                    $penalita = new Punteggio();
                    $penalita->setIdGiornata($giornata);
                    $penalita->setIdUtente($idUtente);
                    $penalita->setIdLega($lega->id);
                    $penalita->setPunteggio(-(($puntiDaTogliere * 10) - $modulo) / 10);
                    $penalita->setPenalità('Formazione non settata');
                    $penalita->save();
                }
            }
            ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
        return $punteggio->getPunteggio();
    }

    public static function getPenalitàBySquadraAndGiornata($idUtente, $idGiornata) {
        $q = "SELECT punteggio,penalità
				FROM punteggio
				WHERE punteggio < 0 AND idUtente = :idUtente AND idGiornata = :idGiornata";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, PDO::PARAM_INT);
        $exe->bindValue(":idGiornata", $idGiornata, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $val = $exe->fetchObject();
        return (!$val) ? NULL : val;
    }

    public static function getPenalitàByLega($idLega) {
        $q = "SELECT *
				FROM punteggio
				WHERE punteggio < 0 AND idLega = :idLega";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idLega", $idLega, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($row = $exe->fetchObject(__CLASS__)) {
            $values[$row->idUtente][$row->idGiornata] = $row->punteggio;
        }
        return $values;
    }

    public static function unsetPenalità($idUtente, $idGiornata) {
        $q = "DELETE FROM punteggio
				WHERE punteggio < 0 AND idUtente = :idUtente AND idGiornata = :idGiornata";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, PDO::PARAM_INT);
        $exe->bindValue(":idGiornata", $idGiornata, PDO::PARAM_INT);
        return $exe->execute();
    }

    public static function unsetPunteggio($idUtente, $idGiornata) {
        $q = "DELETE FROM punteggio
				WHERE punteggio > 0 AND idUtente = :idUtente AND idGiornata = :idGiornata";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, PDO::PARAM_INT);
        $exe->bindValue(":idGiornata", $idGiornata, PDO::PARAM_INT);
        return $exe->execute();
    }

}

 