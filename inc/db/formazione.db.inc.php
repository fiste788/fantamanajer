<?php

require_once(TABLEDIR . 'Formazione.table.db.inc.php');

class Formazione extends FormazioneTable {

    public function duplicate($giornata) {
        $giocatori = Schieramento::getSchieramentoById($this->getId());
        $giocatoriAppo = array();
        foreach ($giocatori as $giocatore)
            $giocatoriAppo[] = $giocatore->idGiocatore;
        $titolari = array_splice($giocatoriAppo, 0, 11);
        $this->setId(NULL);
        $this->setIdGiornata($giornata);
        $this->save(array('titolari' => $titolari, 'panchinari' => $giocatoriAppo, 'evento' => FALSE));
    }

    public function save($parameters = NULL) {
        require_once(INCDBDIR . "schieramento.db.inc.php");

        $giocatoriIds = array();
        if (is_null($parameters))
            return FALSE;
        else {
            $titolari = $parameters['titolari'];
            $panchinari = $parameters['panchinari'];
            $modulo = array('P' => 0, 'D' => 0, 'C' => 0, 'A' => 0);
            $giocatoriIds = array_merge($titolari, $panchinari);
            $giocatori = Giocatore::getByIds($giocatoriIds);
            FirePHP::getInstance()->log($giocatori);
            FirePHP::getInstance()->log($titolari);
            foreach ($titolari as $titolare)
                if($titolare != '')
                    $modulo[$giocatori[$titolare]->ruolo] += 1;
            $this->setModulo(implode($modulo, '-'));
        }

        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            $idFormazione = parent::save();
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
                            $schieramento->save();
                        }
                    } else
                        $success = ($success and $schieramento->delete());
                }
                if ($success) {
                    if ($parameters['evento'] !== FALSE) {
                        $evento = new Evento();
                        $evento->setIdExternal($idFormazione);
                        $evento->setIdUtente($this->getIdUtente());
                        $evento->setIdLega($this->getUtente()->getIdLega());
                        $evento->setTipo(Evento::FORMAZIONE);
                        if ($evento->save())
                            self::commit();
                        else {
                            self::rollback();
                            return FALSE;
                        }
                    } else
                        self::commit();
                }
                else {
                    self::rollback();
                    return FALSE;
                }
                $evento = new Evento();
                $evento->setIdExternal($idFormazione);
                $evento->setIdUtente($this->getIdUtente());
                $evento->setIdLega($this->getUtente()->getIdLega());
                $evento->setTipo(Evento::FORMAZIONE);
                $evento->save();
                ConnectionFactory::getFactory()->getConnection()->commit();
            }
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            FirePHP::getInstance()->error($e->getMessage());
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

    public static function getById($id) {
        require_once(INCDBDIR . "schieramento.db.inc.php");

        $formazione = parent::getById($id);
        if ($formazione)
            $formazione->giocatori = Schieramento::getSchieramentoById($formazione->getId());
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
				WHERE formazione.idUtente = :idUtente AND formazione.idGiornata = :idGiornata";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, PDO::PARAM_INT);
        $exe->bindValue(":idGiornata", $giornata, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $formazione = $exe->fetchObject(__CLASS__);
        if ($formazione)
            $formazione->giocatori = Schieramento::getSchieramentoById($formazione->getId());
        return $formazione;
    }

    /**
     *
     * @param type $giornata
     * @param type $idLega
     * @return Formazione
     */
    public static function getFormazioneByGiornataAndLega($giornata, $idLega) {
        $q = "SELECT formazione.*
				FROM formazione INNER JOIN utente ON formazione.idUtente = utente.id
				WHERE idGiornata = :idGiornata AND idLega = :idLega";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idGiornata", $giornata, PDO::PARAM_INT);
        $exe->bindValue(":idLega", $idLega, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->fetchAll(PDO::FETCH_CLASS,__CLASS__);
    }

    /**
     *
     * @param type $idUtente
     * @return type
     */
    public static function usedJolly($idUtente) {
        $q = "SELECT jolly
				FROM formazione
				WHERE idGiornata " . ((GIORNATA <= 19) ? "<=" : ">") . " 19 AND idUtente = :idUtente AND jolly = :jolly";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, PDO::PARAM_INT);
        $exe->bindValue(":jolly", TRUE, PDO::PARAM_BOOL);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return ($exe->rowCount() == 1);
    }

    /**
     *
     * @param type $array
     * @param type $message
     * @return boolean
     */
    public function check($array) {
        require_once(INCDBDIR . 'giocatore.db.inc.php');

        $post = (object) $array;
        $formazione = array();
        $capitano = array();
        foreach ($post->titolari as $key => $val) {
            if (empty($val))
                throw new FormException("Non hai compilato correttamente tutti i campi");
            if (!in_array($val, $formazione))
                $formazione[] = $val;
            else
                throw new FormException("Giocatore doppio");
        }
        foreach ($post->panchinari as $key => $val) {
            if (!empty($val)) {
                if (!in_array($val, $formazione))
                    $formazione[] = $val;
                else
                    throw new FormException("Giocatore doppio");
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
                    else
                        throw new FormException("Giocatore doppio");
                } else
                    throw new FormException("Capitano non difensore o portiere");
            }
        }
        return TRUE;
    }

}

?>
