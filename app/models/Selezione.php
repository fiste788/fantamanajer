<?php

namespace Fantamanajer\Models;

use Lib\Database as Db;

class Selezione extends Table\SelezioneTable {

    public static function getSelezioneByIdSquadra($idUtente) {
        $q = "SELECT *
				FROM selezione INNER JOIN giocatore ON idGiocatoreNew = giocatore.id
				WHERE idUtente = :idUtente";
        $exe = Db\ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, \PDO::PARAM_INT);
        $exe->execute();
        \FirePHP::getInstance()->log($q);
        return $exe->fetchObject(__CLASS__);
    }

    public static function unsetSelezioneByidSquadra($idUtente) {
        $q = "UPDATE selezione
				SET idGiocatoreOld = NULL,idGiocatoreNew = NULL
				WHERE idUtente = :idUtente";
        $exe = Db\ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, \PDO::PARAM_INT);
        \FirePHP::getInstance()->log($q);
        return $exe->execute();
    }

    public static function checkFree($idGiocatore, $idLega) {
        $q = "SELECT idUtente
				FROM selezione
				WHERE idGiocatoreNew = :idGiocatore AND idLega = :idLega";
        $exe = Db\ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idGiocatore", $idGiocatore, \PDO::PARAM_INT);
        $exe->bindValue(":idLega", $idLega, \PDO::PARAM_INT);
        $values = $exe->fetchObject(__CLASS__);
        if ($values != FALSE)
            return $values->idUtente;
        else
            return FALSE;
    }

    /**
     * @todo Sistemare selezione
     * @param type $giocNew
     * @param type $giocOld
     * @param type $idLega
     * @param type $idUtente
     */
    public static function updateGioc($giocNew, $giocOld, $idLega, $idUtente) {
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            $q = "SELECT numSelezioni
				FROM selezione
				WHERE giocNew = '" . $giocNew . "' AND idLega = '" . $idLega . "' LOCK IN SHARE MODE";
            $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
            $values = $exe->fetchObject(__CLASS__);
            if ($values != FALSE) {
                $q = "UPDATE selezione
					SET giocOld = '0', giocNew = NULL, numSelezioni = '" . ($values->numSelezioni - 1) . "'
					WHERE giocNew = '" . $giocNew . "' AND idLega = '" . $idLega . "'";
                ConnectionFactory::getFactory()->getConnection()->exec($q);
            }
            $q = "SELECT numSelezioni
				FROM selezione
				WHERE giocNew IS NOT NULL AND idUtente = '" . $idUtente . "'  LOCK IN SHARE MODE";
            $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
            $values = $exe->fetchObject(__CLASS__);
            if ($values != FALSE) {
                $q = "UPDATE selezione
					SET giocOld = '" . $giocOld . "', giocNew = '" . $giocNew . "',numSelezioni = '" . ($values2->numSelezioni + 1) . "'
					WHERE idUtente = '" . $idUtente . "'";
                ConnectionFactory::getFactory()->getConnection()->exec($q);
            } else {
                $q = "INSERT INTO selezione
					VALUES ('" . $idLega . "','" . $idUtente . "','" . $giocOld . "','" . $giocNew . "','1')";
                ConnectionFactory::getFactory()->getConnection()->exec($q);
            }
            ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
        return TRUE;
    }

    public static function getNumberSelezioni($idUtente) {
        $q = "SELECT numSelezioni
				FROM selezione
				WHERE idUtente = :idUtente";
        $exe = Db\ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, \PDO::PARAM_INT);
        $exe->execute();
        return $exe->fetchColumn();
    }

    public static function svuota() {
        $q = "TRUNCATE TABLE selezione";
        return (Db\ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }
    
    /**
     * 
     * @param array $array
     * @return boolean
     * @throws \Lib\FormException
     */

    public function check(array $array) {
        $numTrasferimenti = count(Trasferimento::getByField('idUtente', $_SESSION['idUtente']));
        if ($numTrasferimenti < $_SESSION['datiLega']->numTrasferimenti) {
            $giocatoreNew = $this->getGiocatoreNew();
            $giocatoreOld = $this->getGiocatoreOld();
            if ($giocatoreOld->ruolo == $giocatoreNew->ruolo) {
                $numSelezioni = self::getNumberSelezioni($_SESSION['idUtente']);
                if ($numSelezioni <= $_SESSION['datiLega']->numSelezioni) {
                    $squadraOld = self::checkFree($this->getIdGiocatoreNew(), $_SESSION['idLega']);
                    if ($squadraOld != FALSE && $squadraOld != $_SESSION['idUtente']) {
                        $posizioni = Punteggio::getPosClassifica($_SESSION['idLega']);
                        if ($posizioni[$_SESSION['idUtente']] < $posizioni[$squadraOld]) {
                            //Selezione::updateGioc($acquisto,$lasciato,$_SESSION['idLega'],$_SESSION['idUtente']);
                            $mailContent->assign('giocatore', $selezione->getGiocatoreNew()->nome . ' ' . $selezione->getGiocatoreNew()->cognome);
                            $appo = $squadre[$acquistoDett->idSquadraAcquisto];
                            Mail::sendEmail($squadre[$appo]->mail, $mailContent->fetch(MAILTPLDIR . 'mailGiocatoreRubato.tpl.php'), 'Giocatore rubato!');
                        } else {
                            throw new \Lib\FormException('Un altra squadra inferiore di te ha già selezionato questo giocatore');
                        }
                    }
                } else
                    throw new \Lib\FormException("Hai già cambiato " . $_SESSION['datiLega']->numSelezioni . " volte il tuo acquisto");
            } else
                throw new \Lib\FormException('I giocatori devono avere lo stesso ruolo');
        } else
            throw new \Lib\FormException('Hai raggiunto il limite di trasferimenti');
        return TRUE;
    }

    public static function doTransfertBySelezione() {
        try {
            Db\ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            $selezioni = self::getList();
            foreach ($selezioni as $val) {
                $trasferimento = new Trasferimento();
                $trasferimento->setIdGiocatoreOld($val->idGiocatoreOld);
                $trasferimento->setIdGiocatoreNew($val->idGiocatoreNew);
                $trasferimento->setIdUtente($val->idUtente);
                $trasferimento->setIdGiornata(GIORNATA);
                $trasferimento->setObbligato(!$val->getGiocatoreOld()->isAttivo());
                $trasferimento->save();
            }
            self::svuota();
            Db\ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            Db\ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
        return TRUE;
    }

    public function save(array $parameters = array()) {
        $this->setNumSelezioni($this->getNumSelezioni() + 1);
        try {
            Db\ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            \FirePHP::getInstance()->log("salvo");
            parent::save($parameters);
            $evento = new Evento();
            $evento->setTipo(Evento::SELEZIONEGIOCATORE);
            $evento->setIdUtente($_SESSION['idUtente']);
            $evento->setIdLega($_SESSION['idLega']);
            $evento->save();
            Db\ConnectionFactory::getFactory()->getConnection()->commit();
            return TRUE;
        } catch (PDOException $e) {
            Db\ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
    }

}

