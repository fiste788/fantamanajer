<?php

namespace Fantamanajer\Models;

use Fantamanajer\Models\Table\SelectionsTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use Lib\FormException;
use PDO;
use PDOException;

class Selection extends SelectionsTable {

    public static function getSelezioneByIdSquadra($idUtente) {
        $q = "SELECT *
				FROM selezione INNER JOIN giocatore ON idGiocatoreNew = giocatore.id
				WHERE idUtente = :idUtente";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->fetchObject(__CLASS__);
    }

    public static function unsetSelezioneByidSquadra($idUtente) {
        $q = "UPDATE selezione
				SET idGiocatoreOld = NULL,idGiocatoreNew = NULL
				WHERE idUtente = :idUtente";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, PDO::PARAM_INT);
        FirePHP::getInstance()->log($q);
        return $exe->execute();
    }

    public static function checkFree($idGiocatore, $idLega) {
        $q = "SELECT idUtente
				FROM selezione
				WHERE idGiocatoreNew = :idGiocatore AND idLega = :idLega";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idGiocatore", $idGiocatore, PDO::PARAM_INT);
        $exe->bindValue(":idLega", $idLega, PDO::PARAM_INT);
        $values = $exe->fetchObject(__CLASS__);
        if ($values != FALSE)
            return $values->idUtente;
        else
            return FALSE;
    }

    /**
     * @todo Sistemare selezione
     * @param int $giocNew
     * @param int $giocOld
     * @param int $idLega
     * @param int $idUtente
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
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, PDO::PARAM_INT);
        $exe->execute();
        return $exe->fetchColumn();
    }

    public static function svuota() {
        $q = "TRUNCATE TABLE selezione";
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }
    
    /**
     * 
     * @param array $array
     * @return boolean
     * @throws FormException
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
                            /**
                             * TODO: invio mail
                             */
                            //Mail::sendEmail($squadre[$appo]->mail, $mailContent->fetch(MAILTPLDIR . 'mailGiocatoreRubato.tpl.php'), 'Giocatore rubato!');
                        } else {
                            throw new FormException('Un altra squadra inferiore di te ha già selezionato questo giocatore');
                        }
                    }
                } else
                    throw new FormException("Hai già cambiato " . $_SESSION['datiLega']->numSelezioni . " volte il tuo acquisto");
            } else
                throw new FormException('I giocatori devono avere lo stesso ruolo');
        } else
            throw new FormException('Hai raggiunto il limite di trasferimenti');
        return TRUE;
    }

    public static function doTransfertBySelezione() {
        $giornata = Giornata::getCurrentGiornata()->id;
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            $selezioni = self::getList();
            foreach ($selezioni as $selezione) {
                FirePHP::getInstance()->log($selezione);
                if(!is_null($selezione->idGiocatoreOld) && !is_null($selezione->idGiocatoreNew)) {
                    $trasferimento = new Trasferimento();
                    $trasferimento->setIdGiocatoreOld($selezione->idGiocatoreOld);
                    $trasferimento->setIdGiocatoreNew($selezione->idGiocatoreNew);
                    $trasferimento->setIdUtente($selezione->idUtente);
                    $trasferimento->setIdGiornata($giornata);
                    $trasferimento->setObbligato(!$selezione->getGiocatoreOld()->isAttivo());
                    $trasferimento->save();
                }
            }
            self::svuota();
            ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
        return TRUE;
    }

    public function save(array $parameters = array()) {
        $this->setNumSelezioni($this->getNumSelezioni() + 1);
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            FirePHP::getInstance()->log("salvo");
            parent::save($parameters);
            $evento = new Evento();
            $evento->setTipo(Evento::SELEZIONEGIOCATORE);
            $evento->setIdUtente($_SESSION['idUtente']);
            $evento->setIdLega($_SESSION['idLega']);
            $evento->save();
            ConnectionFactory::getFactory()->getConnection()->commit();
            return TRUE;
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
    }

}

