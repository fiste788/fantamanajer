<?php

require_once(TABLEDIR . 'Formazione.table.db.inc.php');

class Formazione extends FormazioneTable {

    public function save($parameters = NULL) {
        require_once(INCDBDIR . "schieramento.db.inc.php");

        $success = TRUE;
        $giocatoriIds = array();
        if (is_null($parameters))
            return FALSE;
        else {
            $titolari = $parameters['titolari'];
            $panchinari = $parameters['panchinari'];
            $modulo = array('P' => 0, 'D' => 0, 'C' => 0, 'A' => 0);
            $giocatoriIds = array_merge($titolari, $panchinari);
            $giocatori = Giocatore::getByIds($giocatoriIds);
            foreach ($titolari as $titolare)
                $modulo[$giocatori[$titolare]->ruolo] += 1;
            $this->setModulo(implode($modulo, '-'));
        }

        self::startTransaction();
        if (($idFormazione = parent::save()) != FALSE) {
            if (!empty($giocatoriIds)) {
                $schieramenti = Schieramento::getSchieramentoById($idFormazione);
                foreach ($giocatoriIds as $posizione => $idGiocatore) {
                    $schieramento = isset($schieramenti[$posizione]) ? $schieramenti[$posizione] : new Schieramento();
                    if (!is_null($idGiocatore) && !empty($idGiocatore)) {
                        if ($schieramento->idGiocatore != $idGiocatore) {
                            $schieramento->setIdFormazione($idFormazione);
                            $schieramento->setPosizione($posizione + 1);
                            $schieramento->setIdGiocatore($idGiocatore);
                            $schieramento->setConsiderato(0);
                            $success = ($success and $schieramento->save());
                        }
                    } else
                        $success = ($success and $schieramento->delete());
                }
                if ($success) {
                    $evento = new Evento();
                    $evento->setIdExternal($idFormazione);
                    $evento->setIdUtente($this->getIdUtente());
                    $evento->setLega($this->getUtente()->getIdLega());
                    $evento->setTipo(Evento::FORMAZIONE);
                    if($evento->save())
                        self::commit();
                    else {
                        self::rollback ();
                        return FALSE;
                    }
                }
                else {
                    self::rollback();
                    return FALSE;
                }
            }
        } else {
            self::rollback();
            return FALSE;
        }
        return TRUE;
    }

    public static function getLastFormazione($idUtente, $giornata) {
        $i = 0;
        $formazione = self::getFormazioneBySquadraAndGiornata($idUtente, $giornata - $i);
        while ($formazione == FALSE && $i < $giornata) {
            $formazione = self::getFormazioneBySquadraAndGiornata($idUtente, $giornata - $i);
            $i++;
        }
        return $formazione;
    }

    /**
     *
     * @param type $idUtente
     * @param type $giornata
     * @return Formazione
     */
    public static function getFormazioneBySquadraAndGiornata($idUtente, $giornata) {
        require_once(INCDBDIR . "schieramento.db.inc.php");

        $q = "SELECT *
				FROM formazione
				WHERE formazione.idUtente = '" . $idUtente . "' AND formazione.idGiornata = '" . $giornata . "'";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        $formazione = mysql_fetch_object($exe, __CLASS__);
        if (!empty($formazione))
            $formazione->giocatori = Schieramento::getSchieramentoById($formazione->getId());
        return $formazione;
    }

    public static function getFormazioneByGiornataAndLega($giornata, $idLega) {
        $q = "SELECT formazione.*
				FROM formazione INNER JOIN utente ON formazione.idUtente = utente.id
				WHERE idGiornata = '" . $giornata . "' AND idLega = '" . $idLega . "'";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($row = mysql_fetch_object($exe, __CLASS__)) {
            $values[] = $row->idUtente;
        }
        return $values;
    }

    public static function changeCap($idFormazione, $idGiocNew, $cap) {
        $q = "UPDATE formazione
				SET " . $cap . " = '" . $idGiocNew . "'
				WHERE idFormazione = '" . $idFormazione . "'";
        FirePHP::getInstance()->log($q);
        return mysql_query($q) or self::sqlError($q);
    }

    public static function usedJolly($idUtente) {
        $q = "SELECT jolly
				FROM formazione
				WHERE idGiornata " . ((GIORNATA <= 19) ? "<=" : ">") . " 19 AND idUtente = '" . $idUtente . "' AND jolly = '1'";
        FirePHP::getInstance()->log($q);
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($_SESSION);
        return (mysql_num_rows($exe) == 1);
    }

    public function check($array, $message) {
        require_once(INCDBDIR . 'giocatore.db.inc.php');

        $post = (object) $array;
        $formazione = array();
        $capitano = array();
        foreach ($post->titolari as $key => $val) {
            if (empty($val)) {
                $message->error("Non hai compilato correttamente tutti i campi");
                return FALSE;
            }
            if (!in_array($val, $formazione))
                $formazione[] = $val;
            else {
                $message->error("Giocatore doppio");
                return FALSE;
            }
        }
        foreach ($post->panchinari as $key => $val) {
            if (!empty($val)) {
                if (!in_array($val, $formazione))
                    $formazione[] = $val;
                else {
                    $message->error("Giocatore doppio");
                    return FALSE;
                }
            }
        }
        $cap = array();
        $cap[] = $post->C;
        $cap[] = $post->VC;
        $cap[] = $post->VVC;
        foreach ($cap as $key => $val) {
            if (!empty($val)) {
                $giocatore = Giocatore::getById($val);
                if ($giocatore->ruolo == 'P' || $giocatore->ruolo == 'D') {
                    if (!in_array($val, $capitano))
                        $capitano[$key] = $val;
                    else {
                        $message->error("Capitano doppio");
                        return FALSE;
                    }
                } else {
                    $message->error("Capitano non difensore o portiere");
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

}

?>
