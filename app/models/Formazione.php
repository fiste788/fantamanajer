<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\FormazioneTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use Lib\FormException;
use PDO;
use PDOException;

class Formazione extends FormazioneTable {
    
    public function duplicate($giornata) {
        $giocatori = Schieramento::getSchieramentoById($this->getId());
        $giocatoriAppo = array();
        foreach ($giocatori as $giocatore) {
            $giocatoriAppo[] = $giocatore->idGiocatore;
        }
        $titolari = array_splice($giocatoriAppo, 0, 11);
        $this->id = NULL;
        $this->setIdGiornata($giornata);
        $this->save(array('titolari' => $titolari, 'panchinari' => $giocatoriAppo, 'evento' => FALSE));
    }

    public function save(array $parameters = NULL) {
        $giocatoriIds = array();
        if (is_null($parameters)) {
            return FALSE;
        } else {
            $titolari = $parameters['titolari'];
            $panchinari = $parameters['panchinari'];
            $giocatoriIds = !empty($panchinari) ? array_merge($titolari, $panchinari) : $titolari;
            $modulo = $this->calcModulo($titolari);
            $this->setModulo($modulo);
            if($this->idCapitano == 0)
                $this->idCapitano = NULL;
            if($this->idVCapitano == 0)
                $this->idVCapitano = NULL;
            if($this->idVVCapitano == 0)
                $this->idVVCapitano = NULL;
        }

        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            $id = $this->id;
            parent::save($parameters);
            if (!empty($giocatoriIds)) {
                $success = TRUE;
                $schieramenti = $this->getSchieramenti($giocatoriIds);
                foreach ($schieramenti as $schieramento) {
                    if ($schieramento->idGiocatore != NULL) {
                        $success = $success and $schieramento->save();
                    } else {
                        $success = ($success and $schieramento->delete());
                    }
                }
                if ($success) {
                    if (is_null($id) && (!isset($parameters['evento']) || (isset($parameters['evento']) && $parameters['evento'] !== FALSE))) {
                        $evento = new Evento();
                        $evento->setIdExternal($this->getId());
                        $evento->setIdUtente($this->getIdUtente());
                        $evento->setIdLega($this->getUtente()->getIdLega());
                        $evento->setTipo(Evento::FORMAZIONE);
                        $evento->save();    
                    }
                    ConnectionFactory::getFactory()->getConnection()->commit();
                } else {
                    ConnectionFactory::getFactory()->getConnection()->rollback();
                    return FALSE;
                }
            } else {
                ConnectionFactory::getFactory()->getConnection()->rollback();
                return FALSE;
            }
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
        return TRUE;
    }

    public function getSchieramenti($giocatoriIds) {
        $schieramenti = Schieramento::getSchieramentoById($this->getId());

        foreach ($giocatoriIds as $posizione => $idGiocatore) {
            $schieramento = isset($schieramenti[$posizione]) ? $schieramenti[$posizione] : new Schieramento();
            if (!is_null($idGiocatore) && !empty($idGiocatore)) {
                if ($schieramento->idGiocatore != $idGiocatore) {
                    $schieramento->setIdFormazione($this->getId());
                    $schieramento->setPosizione($posizione + 1);
                    $schieramento->setIdGiocatore($idGiocatore);
                    $schieramento->setConsiderato(0);
                }
            } else
                $schieramento->idGiocatore = NULL;
            $schieramenti[$posizione] = $schieramento;
        }
        return $schieramenti;
    }

    public function calcModulo($titolari) {
        $modulo = array('P' => 0, 'D' => 0, 'C' => 0, 'A' => 0);
        $giocatori = Giocatore::getByIds($titolari);
        foreach ($titolari as $titolare)
            if ($titolare != '')
                $modulo[$giocatori[$titolare]->ruolo] += 1;
        return implode($modulo, "-");
    }

    /**
     *
     * @param int $idUtente
     * @param int $giornata
     * @return Formazione | null
     */
    public static function getLastFormazione($idUtente, $giornata) {
        $i = 0;
        $formazione = self::getFormazioneBySquadraAndGiornata($idUtente, $giornata - $i);
        while ($formazione == NULL && $i < $giornata) {
            $formazione = self::getFormazioneBySquadraAndGiornata($idUtente, $giornata - $i);
            $i++;
        }
        return $formazione;
    }

    public static function getById($id) {
        $formazione = parent::getById($id);
        if ($formazione)
            $formazione->giocatori = Schieramento::getSchieramentoById($formazione->getId());
        return $formazione;
    }

    /**
     *
     * @param int $idUtente
     * @param int $giornata
     * @return Formazione
     */
    public static function getFormazioneBySquadraAndGiornata($idUtente, $giornata) {
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
        return ($formazione) ? $formazione : NULL;
    }

    /**
     *
     * @param int $giornata
     * @param int $idLega
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
        return $exe->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    }

    /**
     *
     * @param int $idUtente
     * @return bool
     */
    public static function usedJolly($idUtente, $giornata) {
        $q = "SELECT jolly
				FROM formazione
				WHERE idGiornata " . (($giornata <= 19) ? "<=" : ">") . " 19 AND idUtente = :idUtente AND jolly = :jolly";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, PDO::PARAM_INT);
        $exe->bindValue(":jolly", TRUE, PDO::PARAM_BOOL);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return ($exe->rowCount() == 1);
    }

    /**
     *
     * @param array $array
     * @return boolean
     */
    public function check(array $array) {
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
        $cap[] = $this->idCapitano;
        $cap[] = $this->idVCapitano;
        $cap[] = $this->idVVCapitano;
        foreach ($cap as $key => $val) {
            if (!empty($val) && !is_null($val)) {
                $giocatore = Giocatore::getById($val);
                if(in_array($giocatore->getId(),$post->titolari)) {
                    if ($giocatore->ruolo == 'P' || $giocatore->ruolo == 'D') {
                        if (!in_array($val, $capitano))
                            $capitano[$key] = $val;
                        else
                            throw new FormException("Giocatore doppio");
                    } else
                        throw new FormException("Capitano non difensore o portiere");
                } else
                    throw new FormException("Capitano non titolare");
            }
        }
        return TRUE;
    }

}

