<?php

require_once(TABLEDIR . 'Punteggio.table.db.inc.php');

class Punteggio extends PunteggioTable {

    public static function checkPunteggi($giornata) {
        $q = "SELECT *
				FROM punteggio
				WHERE idGiornata = '" . $giornata . "'";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        if (mysql_num_rows($exe) > 0)
            return FALSE;
        else
            return TRUE;
    }

    /**
     * @param Utente $utente 
     * @param int $idGiornata
     * @return Punteggio
     */
    public static function getByUtenteAndGiornata($utente, $idGiornata) {
        $q = "SELECT *
				FROM punteggio
				WHERE idUtente = '" . $utente->getId() . "' AND idGiornata = '" . $idGiornata . "'";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            return $row;
    }

    public static function getPosClassificaGiornata($idLega) {
        $q = "SELECT *
				FROM punteggio
				WHERE idLega = '" . $idLega . "' AND punteggio >= 0 ORDER BY idGiornata,punteggio DESC";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($row = mysql_fetch_object($exe, __CLASS__))
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
				WHERE idUtente = '" . $idUtente . "'";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $values = $row->giornateVinte;
        return $values;
    }

    public static function getClassificaByGiornata($idLega, $idGiornata) {
        $q = "SELECT punteggio.*, SUM(punteggio.punteggio) AS punteggioTot, AVG(punteggio.punteggio) AS punteggioMed, MAX(punteggio.punteggio) AS punteggioMax, (SELECT MIN(punteggio.punteggio) FROM punteggio WHERE punteggio >= 0 AND idUtente = punteggio.idUtente) AS punteggioMin, COALESCE(giornateVinte,0) as giornateVinte
				FROM punteggio LEFT JOIN view_2_giornatevinte ON punteggio.idUtente = view_2_giornatevinte.idUtente
				WHERE punteggio.idGiornata <= '" . $idGiornata . "' AND punteggio.idLega = '" . $idLega . "'
				GROUP BY punteggio.idUtente
				ORDER BY punteggioTot DESC , giornateVinte DESC";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        $classifica = NULL;
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $classifica[$row->idUtente] = $row;
        return $classifica;
    }

    public static function getAllPunteggiByGiornata($giornata, $idLega) {
        $q = "SELECT *
				FROM punteggio
				WHERE (idGiornata <= " . $giornata . " OR idGiornata IS NULL) AND idLega = '" . $idLega . "'
				ORDER BY idGiornata DESC";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        $classifica = array();
        while ($row = mysql_fetch_object($exe, __CLASS__)) {
            if (isset($classifica[$row->idUtente][$row->idGiornata]))
                $classifica[$row->idUtente][$row->idGiornata] += $row->punteggio;
            else
                $classifica[$row->idUtente][$row->idGiornata] = $row->punteggio;
        }
        $somme = self::getClassificaByGiornata($idLega, $giornata);
        FirePHP::getInstance()->log($somme);
        if (isset($somme)) {
            foreach ($somme as $key => $val)
                $somme[$key] = $classifica[$key];
        } else {
            require_once(INCDBDIR . 'utente.db.inc.php');

            $squadre = Utente::getByField('idLega', $idLega);
            foreach ($squadre as $key => $val)
                $somme[$key][0] = 0;
        }
        return($somme);
    }

    public static function getGiornateWithPunt() {
        $q = "SELECT COUNT(DISTINCT(idGiornata)) as numGiornate
				FROM punteggio";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe))
            return $row->numGiornate;
    }

    protected static function sostituzione($giocatore, &$panchinari, &$cambi, $giornata) {
        require_once(INCDBDIR . 'voto.db.inc.php');

        for ($i = 0; $i < count($panchinari); $i++) {
            $schieramento = $panchinari[$i];
            $giocatorePanchina = $schieramento->getGiocatore();
            $voto = $giocatorePanchina->getVotoByGiornata($giornata);
            if (($giocatore->getRuolo() == $giocatorePanchina->getRuolo()) && ($voto->isPresente())) {
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

    public static function calcolaPunti($utente, $giornata) {
        require_once(INCDBDIR . 'utente.db.inc.php');
        require_once(INCDBDIR . 'formazione.db.inc.php');
        require_once(INCDBDIR . 'voto.db.inc.php');
        require_once(INCDBDIR . 'giocatore.db.inc.php');
        require_once(INCDBDIR . 'schieramento.db.inc.php');

        $formazione = Formazione::getLastFormazione($utente->id, $giornata);
        $punteggio = self::getByUtenteAndGiornata($utente, $giornata);
        $lega = $utente->getLega();
        if ($punteggio == FALSE)
            $punteggio = new Punteggio();
        if ($formazione == FALSE || ($formazione->getIdGiornata() != $giornata && $lega->getPunteggioFormazioneDimenticata() != 0)) {
            $punteggio->setIdGiornata($giornata);
            $punteggio->setIdUtente($utente->getId());
            $punteggio->setIdLega($lega->getId());
            $punteggio->setPunteggio(0);
            $punteggio->save();
        } else {
            $idUtente = $formazione->getIdUtente();
            $giornata = $formazione->getIdGiornata();
            if ($giornata != $giornata) {
                if (!$lega->getCapitanoFormazioneDimenticata()) {
                    $formazione->setIdCapitano(NULL);
                    $formazione->setIdVCapitano(NULL);
                    $formazione->setIdVVCapitano(NULL);
                }
                $formazione->setId(NULL);
                $formazione->setGiornata($giornata);
                $formazione->save();
            }
            $cambi = 0;
            $somma = 0;
            $cap = self::getCapitanoAttivo($formazione);
            $panchinari = $formazione->giocatori;
            $titolari = array_splice($panchinari, 0, 11);
            foreach ($titolari as $schieramento) {
                $giocatore = $schieramento->getGiocatore();
                $voto = $giocatore->getVotoByGiornata($giornata);
                if ((!$voto->isPresente()) && ($cambi < 3)) {
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
                    if ($lega->capitano && $giocatore->getId() == $cap) {
                        $schieramento->setConsiderato(2);
                        $punti *= 2;
                    }
                    $schieramento->save();
                    $somma += $punti;
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
            return TRUE;
        }
    }

    public static function setPunteggiToZero($idUtente, $idLega) {
        $giornateWithPunt = self::getGiornateWithPunt();
        if (empty($giornateWithPunt))
            $giornateWithPunt = 0;
        for ($i = 1; $i <= $giornateWithPunt; $i++) {
            $q = "INSERT INTO punteggio (punteggio,idGiornata,idUtente,idLega)
					VALUES('0','" . $i . "','" . $idUtente . "','" . $idLega . "')";
            if (DEBUG)
                FirePHP::getInstance(true)->log($q);
            mysql_query($q) or self::sqlError($q);
        }
        return TRUE;
    }

    public static function setPunteggiToZeroByGiornata($idUtente, $idLega, $idGiornata) {
        if (self::getB($idUtente, $idGiornata) != '0')
            $q = "INSERT INTO punteggio (punteggio,idGiornata,idUtente,idLega)
					VALUES('0','" . $idGiornata . "','" . $idUtente . "','" . $idLega . "')";
        else
            return TRUE;
        FirePHP::getInstance()->log($q);
        return mysql_query($q) or self::sqlError($q);
    }

    public static function getPenalitàBySquadraAndGiornata($idUtente, $idGiornata) {
        $q = "SELECT punteggio,penalità
				FROM punteggio
				WHERE punteggio < 0 AND idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "'";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        $values = FALSE;
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $values = $row;
        return $values;
    }

    public static function getPenalitàByLega($idLega) {
        $q = "SELECT *
				FROM punteggio
				WHERE punteggio < 0 AND idLega = '" . $idLega . "'";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        $values = FALSE;
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $values[$row->idUtente][$row->idGiornata] = $row->punteggio;
        return $values;
    }

    public static function unsetPenalità($idUtente, $idGiornata) {
        $q = "DELETE FROM punteggio
				WHERE punteggio < 0 AND idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "'";
        return mysql_query($q) or self::sqlError($q);
    }

    public static function unsetPunteggio($idUtente, $idGiornata) {
        $q = "DELETE FROM punteggio
				WHERE punteggio > 0 AND idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "'";
        return mysql_query($q) or self::sqlError($q);
    }

}

?>
