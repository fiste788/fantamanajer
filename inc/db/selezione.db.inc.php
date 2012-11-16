<?php

require_once(TABLEDIR . 'Selezione.table.db.inc.php');

class Selezione extends SelezioneTable {

    public static function getSelezioneByIdSquadra($idUtente) {
        $q = "SELECT *
				FROM selezione INNER JOIN giocatore ON idGiocatoreNew = giocatore.id
				WHERE idUtente = '" . $idUtente . "'";
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        return $exe->fetchObject(__CLASS__);
    }

    public static function unsetSelezioneByidSquadra($idUtente) {
        $q = "UPDATE selezione
				SET idGiocatoreOld = NULL,idGiocatoreNew = NULL
				WHERE idUtente = '" . $idUtente . "';";
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }

    public static function checkFree($idGiocatore, $idLega) {
        $q = "SELECT idUtente
				FROM selezione
				WHERE idGiocatoreNew = '" . $idGiocatore . "' AND idLega = '" . $idLega . "'";
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        $exe->fetchObject(__CLASS__);
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
            FirePHP::getInstance()->error($e->getMessage());
            return FALSE;
        }
    }

    public static function getNumberSelezioni($idUtente) {
        $q = "SELECT numSelezioni
				FROM selezione
				WHERE idUtente = '" . $idUtente . "'";
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        return $exe->fetchColumn();
    }

    public static function svuota() {
        $q = "TRUNCATE TABLE selezione";
        return (ConnectionFactory::getFactory()->getConnection()->exec($q) != FALSE);
    }

    public function check($array, $message) {
        require_once(INCDBDIR . 'giocatore.db.inc.php');
        require_once(INCDBDIR . 'punteggio.db.inc.php');
        require_once(INCDBDIR . 'utente.db.inc.php');
        require_once(INCDBDIR . 'trasferimento.db.inc.php');

        $post = (object) $array;
        $numTrasferimenti = count(Trasferimento::getByField('idUtente', $_SESSION['idUtente']));
        if ($numTrasferimenti < $_SESSION['datiLega']->numTrasferimenti) {
            $giocatoreNew = Giocatore::getById($post->idGiocatoreNew);
            $giocatoreOld = Giocatore::getById($post->idGiocatoreOld);
            if ($giocatoreOld->ruolo == $giocatoreNew->ruolo) {
                $numSelezioni = self::getNumberSelezioni($_SESSION['idUtente']);
                if ($numSelezioni > $_SESSION['datiLega']->numSelezioni) {

                    $message->warning('Hai già cambiato ' . $_SESSION['datiLega']->numSelezioni . ' volte il tuo acquisto');
                    return FALSE;
                }
            } else {
                $message->error('I giocatori devono avere lo stesso ruolo');
                return FALSE;
            }
        } else {
            $message->error('Hai raggiunto il limite di trasferimenti');
            return FALSE;
        }
        return TRUE;
    }

    public static function doTransfertBySelezione() {
        require_once(INCDBDIR . 'trasferimento.db.inc.php');

        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            $selezioni = self::getList();
            foreach ($selezioni as $val) {
                $trasferimento = new Trasferimento();
                $trasferimento->setIdGiocatoreOld($val->idGiocatoreOld);
                $trasferimento->setIdGiocatoreNew($val->idGiocatoreNew);
                $trasferimento->setIdUtente($val->idUtente);
                $trasferimento->setIdGiornata(GIORNATA);
                $trasferimento->setObbligato(($val->getGiocatoreOld()->isAttivo()) ? '0' : '1');
                $trasferimento->save();
            }
            Selezione::svuota();
            ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            FirePHP::getInstance()->error($e->getMessage());
            return FALSE;
        }
        return TRUE;
    }

}

?>
