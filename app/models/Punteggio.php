<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\PunteggioTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use PDO;
use PDOException;

class Punteggio extends PunteggioTable {

    /**
     * @param Utente $utente
     * @param int $idGiornata
     * @return Punteggio
     */
    public static function getByUtenteAndGiornata($utente, $idGiornata) {
        $q = "SELECT *
				FROM punteggio
				WHERE idUtente = :idUtente AND idGiornata = :idGiornata";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $utente->getId(), PDO::PARAM_INT);
        $exe->bindValue(":idGiornata", $idGiornata, PDO::PARAM_INT);
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
        while ($row = $exe->fetchObject(__CLASS__))
            $values[$row->idGiornata][] = $row;
        if (!empty($values)) {
            $appo = array();
            foreach ($values as $giornata => $pos)
                foreach ($pos as $key => $val)
                    $appo[$giornata][$val->idUtente] = $key + 1;
            return $appo;
        }else
            return null;
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

    public static function getClassificaByGiornata($idLega, $idGiornata) {
        $q = "SELECT punteggio.*, SUM(punteggio.punteggio) AS punteggioTot, AVG(punteggio.punteggio) AS punteggioMed, MAX(punteggio.punteggio) AS punteggioMax, (SELECT MIN(punteggio.punteggio) FROM punteggio WHERE punteggio >= 0 AND idUtente = punteggio.idUtente) AS punteggioMin, COALESCE(giornateVinte,0) as giornateVinte
				FROM punteggio LEFT JOIN view_2_giornatevinte ON punteggio.idUtente = view_2_giornatevinte.idUtente
				WHERE punteggio.idGiornata <= :idGiornata AND punteggio.idLega = :idLega
				GROUP BY punteggio.idUtente
				ORDER BY punteggioTot DESC , giornateVinte DESC";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idGiornata", $idGiornata, PDO::PARAM_INT);
        $exe->bindValue(":idLega", $idLega, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__))
            $values[$obj->getIdUtente()] = $obj;
        return $values;
    }

    public static function getAllPunteggiByGiornata($giornata, $idLega) {
        $q = "SELECT *
				FROM punteggio
				WHERE (idGiornata <= :idGiornata OR idGiornata IS NULL) AND idLega = :idLega
				ORDER BY idGiornata DESC";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idGiornata", $giornata, PDO::PARAM_INT);
        $exe->bindValue(":idLega", $idLega, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $classifica = array();
        while ($row = $exe->fetchObject(__CLASS__)) {
            if (isset($classifica[$row->idUtente][$row->idGiornata]))
                $classifica[$row->idUtente][$row->idGiornata] += $row->punteggio;
            else
                $classifica[$row->idUtente][$row->idGiornata] = $row->punteggio;
        }
        $somme = self::getClassificaByGiornata($idLega, $giornata);
        if (isset($somme)) {
            foreach ($somme as $key => $val)
                $somme[$key] = $classifica[$key];
        } else {
            $squadre = Utente::getByField('idLega', $idLega);
            foreach ($squadre as $key => $val)
                $somme[$key][0] = 0;
        }
        return($somme);
    }

    public static function getGiornateWithPunt() {
        $q = "SELECT COUNT(DISTINCT(idGiornata)) as numGiornate
				FROM punteggio";
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
                if ($voto->isPresente())
                    return $cap;
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
            $punteggio = self::getByUtenteAndGiornata($utente, $giornata);
            $lega = $utente->getLega();
            if ($punteggio == FALSE)
                $punteggio = new Punteggio();
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
                        $formazione->setIdCapitano(NULL);
                        $formazione->setIdVCapitano(NULL);
                        $formazione->setIdVVCapitano(NULL);
                    }
                    $formazione = clone $formazione;
                    $formazione->duplicate($giornata);
                }
                $cambi = 0;
                $somma = 0;
                $cap = self::getCapitanoAttivo($formazione);
                $panchinari = $formazione->giocatori;
                $titolari = array_splice($panchinari, 0, 11);
                foreach ($titolari as $schieramento) {
                    $giocatore = $schieramento->getGiocatore();
                    $voto = $giocatore->getVotoByGiornata($giornata);
                    if ((!$voto->isValutato()) && ($cambi < 3)) {
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

                if ($formazione->jolly == 1)
                    $somma *= 2;
                if (!$punteggio)
                    $punteggio = new Punteggio();
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
        return $exe->fetchObject();
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
        while ($row = $exe->fetchObject(__CLASS__))
            $values[$row->idUtente][$row->idGiornata] = $row->punteggio;
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

 