<?php

require_once(TABLEDIR . 'Giocatore.table.db.inc.php');

class Giocatore extends GiocatoreTable {

    public function save($numEvento = NULL) {
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            parent::save();
            if (!is_null($numEvento)) {
                require_once(INCDBDIR . 'evento.db.inc.php');
                $evento = new Evento();
                $evento->setIdExternal($this->getId());
                $evento->setTipo($numEvento);
                $evento->save();
            }
            ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            FirePHP::getInstance()->error($e);
            return FALSE;
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
        while ($obj = $exe->fetchObject(__CLASS__))
            $values[$obj->getId()] = $obj;
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

    public static function getFreePlayer($ruolo, $idLega) {
        $q = "SELECT view_0_giocatoristatistiche.*
				FROM view_0_giocatoristatistiche
				WHERE id NOT IN (
						SELECT idGiocatore
						FROM squadra
						WHERE idLega = :idLega)";
        if ($ruolo != NULL)
            $q .= " AND ruolo = :ruolo";
        $q .= " AND attivo = :attivo
				ORDER BY cognome,nome";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idLega", $idLega, PDO::PARAM_INT);
        $exe->bindValue(":ruolo", $ruolo);
        $exe->bindValue(":attivo", TRUE, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__))
            $values[$obj->getId()] = $obj;
        return $values;
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

    public static function getVotiGiocatoriByGiornataAndSquadra($giornata, $idUtente) {
        $q = "SELECT *
				FROM view_0_formazionestatistiche
				WHERE idGiornata = :idGiornata AND idUtente = :idUtente ORDER BY posizione";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idGiornata", $giornata, PDO::PARAM_INT);
        $exe->bindValue(":idUtente", $idUtente, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $elenco = $exe->fetchAll(PDO::FETCH_CLASS, __CLASS__);
        return $elenco;
    }

    public static function updateTabGiocatore($path) {
        require_once(INCDBDIR . 'club.db.inc.php');
        require_once(INCDBDIR . 'evento.db.inc.php');
        require_once(INCDIR . 'decrypt.inc.php');
        require_once(INCDIR . 'fileSystem.inc.php');

        $ruoli = array("P", "D", "C", "A");
        $giocatoriOld = self::getList();
        $giocatoriNew = fileSystem::returnArray($path, ";");
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            foreach ($giocatoriNew as $id => $giocatoreNew) {
                if (array_key_exists($id, $giocatoriOld)) {
                    $clubNew = Club::getByField('nome', ucwords(strtolower(trim($giocatoreNew[3], '"'))));
                    if ($giocatoriOld[$id]->getIdClub() != $clubNew->getId()) {
                        $giocatoriOld[$id]->setClub($clubNew);
                        $giocatoriOld[$id]->setAttivo(TRUE);
                        $giocatoriOld[$id]->save(EVENTO::CAMBIOCLUB);
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
                    $giocatoreOld->save(EVENTO::NUOVOGIOCATORE);
                }
            }
            foreach ($giocatoriOld as $id => $giocatoreOld) {
                if (!array_key_exists($id, $giocatoriNew) && $giocatoreOld->isAttivo()) {
                    $giocatoreOld->setAttivo(FALSE);
                    $giocatoreOld->save(EVENTO::RIMOSSOGIOCATORE);
                }
            }
            ConnectionFactory::getFactory()->getConnection()->commit();
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            FirePHP::getInstance()->error($e->getMessage());
            return FALSE;
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
        while ($obj = $exe->fetchObject(__CLASS__))
            $values[$obj->getId()] = $obj;
        return $values;
    }

    public static function getGiocatoriBySquadraAndGiornata($idUtente, $idGiornata) {
        require_once(INCDBDIR . 'trasferimento.db.inc.php');

        $giocatori = self::getGiocatoriByIdSquadra($idUtente);
        $trasferimenti = Trasferimento::getTrasferimentiByIdSquadra($idUtente, $idGiornata);
        if (!empty($trasferimenti)) {
            $sort_arr = array();
            foreach ($trasferimenti as $uniqid => $row)
                foreach ($row as $key => $value)
                    $sort_arr[$key][$uniqid] = $value;
            array_multisort($sort_arr['idGiornata'], SORT_DESC, $trasferimenti);
            foreach ($trasferimenti as $key => $val)
                foreach ($giocatori as $key2 => $val2)
                    if ($val2->id == $val->idGiocatoreNew)
                        $giocatori[$key2] = self::getById($val->idGiocatoreOld);
            $sort_arr2 = array();
            foreach ($giocatori as $uniqid => $row)
                foreach ($row as $key => $value)
                    $sort_arr2[$key][$uniqid] = $value;
            array_multisort($sort_arr['cognome'], SORT_ASC, $giocatori);
        }
        $giocatoriByRuolo = array();
        foreach ($giocatori as $key => $val)
            $giocatoriByRuolo[$val->ruolo][] = $val;
        return $giocatoriByRuolo;
    }

    public static function getGiocatoriInattiviByIdUtente($idUtente) {
        $q = "SELECT giocatore.id, cognome, nome, ruolo
				FROM giocatore INNER JOIN squadra ON giocatore.id = squadra.idGiocatore
				WHERE idUtente = :idUtente AND attivo = :attivo";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idUtente", $idUtente, PDO::PARAM_INT);
        $exe->bindValue(":attivo", TRUE, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__))
            $values[$obj->getId()] = $obj;
        return $values;
    }

    public static function getBestPlayerByGiornataAndRuolo($idGiornata, $ruolo) {
        $q = "SELECT giocatore.*,punti
				FROM giocatore INNER JOIN voto ON giocatore.id = voto.idGiocatore
				WHERE idGiornata = :idGiornata AND ruolo = :ruolo
				ORDER BY punti DESC , voto DESC
				LIMIT 0 , 5";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idGiornata", $idGiornata, PDO::PARAM_INT);
        $exe->bindValue(":ruolo", $ruolo);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject(__CLASS__))
            $values[$obj->getId()] = $obj;
        return $values;
    }

    function getFoto() {
        require_once(INCDIR . 'fileSystem.inc.php');
        $gioc = self::getList();
        foreach ($gioc as $val) {
            if (!file_exists(PLAYERSDIR . "new/" . $val->id . ".jpg")) {
                $url = "http://www.gazzetta.it/img/calcio/figurine_panini/" . (($val->nome != NULL) ? str_replace(" ", "_", strtoupper($val->nome)) : "") . "_" . str_replace(" ", "_", strtoupper($val->cognome)) . ".jpg";
                echo (($val->nome != NULL) ? str_replace(" ", "_", strtoupper($val->nome)) : "") . "_" . str_replace(" ", "_", strtoupper($val->cognome));
                //flush();
                //FirePHP::getInstance()->log($url);
                $fileContents = FileSystem::contenutoCurl($url);

                if (stripos($fileContents, "gazzetta") == FALSE) {
                    $newImg = imagecreatefromstring($fileContents);
                    imagejpeg($newImg, PLAYERSDIR . "new/" . $val->id . ".jpg", 100);
                }
            }
        }
    }

    /**
     *
     * @param type $giornata
     * @return Voto
     */
    public function getVotoByGiornata($giornata) {
        require_once(INCDBDIR . 'voto.db.inc.php');
        return Voto::getByGiocatoreAndGiornata($this->getId(), $giornata);
    }

    /**
     *
     * @return Voto[]
     */
    public function getVoti() {
        require_once(INCDBDIR . 'voto.db.inc.php');
        return Voto::getByGiocatore($this);
    }

}

?>
