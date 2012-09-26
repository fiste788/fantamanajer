<?php

require_once(TABLEDIR . 'Giocatore.table.db.inc.php');

class Giocatore extends GiocatoreTable {

    public function save($numEvento = NULL) {
        require_once(INCDBDIR . 'evento.db.inc.php');
        if (parent::save() && !is_null($numEvento)) {
            $evento = new Evento();
            $evento->setIdExternal($this->id);
            $evento->setTipo($numEvento);
            return $evento->save();
        }
        return TRUE;
    }

    public static function getGiocatoriByIdSquadra($idUtente) {
        $q = "SELECT giocatore.id, cognome, nome, ruolo, idUtente
				FROM giocatore INNER JOIN squadra ON giocatore.id = squadra.idGiocatore
				WHERE idUtente = '" . $idUtente . "'
				ORDER BY ruolo DESC,cognome ASC";
        $exe = mysql_query($q) or self::sqlError($q);
        $giocatori = array();
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $giocatori[$row->id] = $row;
        if (isset($giocatori))
            return $giocatori;
        else
            return FALSE;
    }

    public static function getGiocatoriByIdClub($idClub) {
        $q = "SELECT giocatore.id, cognome, nome, ruolo
				FROM giocatore
				WHERE idClub = '" . $idClub . "'
				ORDER BY giocatore.ruolo DESC,giocatore.cognome ASC";
        $exe = mysql_query($q) or self::sqlError($q);
        $giocatori = array();
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $giocatori[$row->id] = $row;
        if (isset($giocatori))
            return $giocatori;
        else
            return FALSE;
    }

    public static function getGiocatoriByIdSquadraAndRuolo($idUtente, $ruolo) {
        $q = "SELECT giocatore.id, cognome, nome, ruolo, idUtente
				FROM giocatore INNER JOIN squadra ON giocatore.id = squadra.idGiocatore
				WHERE idUtente = '" . $idUtente . "' AND ruolo = '" . $ruolo . "' AND giocatore.attivo=1
				ORDER BY giocatore.id ASC";
        $exe = mysql_query($q) or self::sqlError($q);
        $giocatori = array();
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $giocatori[] = $row;
        if (isset($giocatori))
            return $giocatori;
        else
            return FALSE;
    }

    public static function getFreePlayer($ruolo, $idLega) {
        $q = "SELECT view_0_giocatoristatistiche.*
				FROM view_0_giocatoristatistiche
				WHERE id NOT IN (
						SELECT idGiocatore
						FROM squadra
						WHERE idLega = '" . $idLega . "')";
        if ($ruolo != NULL)
            $q .= " AND ruolo = '" . $ruolo . "'";
        $q .= " AND attivo = 1
				ORDER BY cognome,nome";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $giocatori[$row->id] = $row;
        return $giocatori;
    }

    public static function getGiocatoriByArray($giocatori) {
        $q = "SELECT id,cognome,nome,ruolo
				FROM giocatore
				WHERE id IN ('" . implode("','", $giocatori) . "')
				ORDER BY FIELD(id,'" . implode("','", $giocatori) . "')";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $result[] = $row;
        return $result;
    }

    public static function getGiocatoreByIdWithStats($idGioc, $idLega = NULL) {
        $q = "SELECT view_0_giocatoristatistiche.*,idLega
				FROM (SELECT *
						FROM squadra
						WHERE idLega = '" . $idLega . "') AS squad RIGHT JOIN view_0_giocatoristatistiche ON squad.idGiocatore = view_0_giocatoristatistiche.id
				WHERE view_0_giocatoristatistiche.id = '" . $idGioc . "'";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        return mysql_fetch_object($exe, __CLASS__);
    }

    public static function getVotiGiocatoriByGiornataAndSquadra($giornata, $idUtente) {
        $q = "SELECT *
				FROM view_0_formazionestatistiche
				WHERE idGiornata = '" . $giornata . "' AND idUtente = '" . $idUtente . "' ORDER BY posizione";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        $elenco = FALSE;
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $elenco[] = $row;
        return $elenco;
    }

    public static function getGiocatoriByIdClubWithStats($idClub) {
        $q = "SELECT *
				FROM giocatoristatistiche
				WHERE idClub = '" . $idClub . "' AND attivo = '1'
				ORDER BY ruolo DESC,cognome ASC";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $giocatori[] = $row;
        if (isset($giocatori))
            return $giocatori;
        else
            return FALSE;
    }

    public static function getGiocatoriByIdSquadraWithStats($idUtente) {
        $q = "SELECT *
				FROM giocatoristatistiche INNER JOIN squadra on giocatoristatistiche.id = squadra.idGiocatore
				WHERE idUtente = '" . $idUtente . "'
				ORDER BY ruolo DESC,cognome ASC";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $giocatori[] = $row;
        if (isset($giocatori))
            return $giocatori;
        else
            return FALSE;
    }

    public static function getRuoloByIdGioc($idGioc) {
        $q = "SELECT ruolo
				FROM giocatore
				WHERE id = '" . $idGioc . "'";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            return $row->ruolo;
    }

    public static function getArrayGiocatoriFromDatabase($all = FALSE) {
        $q = "SELECT giocatore.*, club.nome
				FROM giocatore LEFT JOIN club ON giocatore.idClub = club.id";
        if ($all)
            $q .= " WHERE giocatore.attivo = 1";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        $giocatori = array();
        while ($row = mysql_fetch_object($exe, __CLASS__)) {
            $row->nomeClub = strtoupper(substr($row->nomeClub, 0, 3));
            $giocatori[$row->id] = implode(";", get_object_vars($row));
        }
        return $giocatori;
    }

    public static function updateTabGiocatore($path) {
        require_once(INCDBDIR . 'club.db.inc.php');
        require_once(INCDBDIR . 'evento.db.inc.php');
        require_once(INCDIR . 'decrypt.inc.php');
        require_once(INCDIR . 'fileSystem.inc.php');

        $ruoli = array("P", "D", "C", "A");
        $giocatoriOld = self::getList();
        $giocatoriNew = fileSystem::returnArray($path, ";");
        self::startTransaction();
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
        self::commit();
        return TRUE;
    }

    public static function getGiocatoriNotSquadra($idUtente, $idLega) {
        $q = "SELECT giocatore.id, cognome, nome, ruolo, idUtente
				FROM giocatore LEFT JOIN squadra ON giocatore.id = squadra.idGiocatore
				WHERE idLega = '" . $idLega . "' AND idUtente <> '" . $idUtente . "' OR idUtente IS NULL
				ORDER BY giocatore.id ASC";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $giocatori[$row->id] = $row;
        return $giocatori;
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
				WHERE idUtente = '" . $idUtente . "' AND attivo = 0";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        $giocatori = array();
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $giocatori[$row->id] = $row;
        return $giocatori;
    }

    public static function getBestPlayerByGiornataAndRuolo($idGiornata, $ruolo) {
        $values = FALSE;
        $q = "SELECT giocatore.*,punti
				FROM giocatore INNER JOIN voto ON giocatore.id = voto.idGiocatore
				WHERE idGiornata = '" . $idGiornata . "' AND ruolo = '" . $ruolo . "'
				ORDER BY punti DESC , voto DESC
				LIMIT 0 , 5";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $values[$row->getId()] = $row;
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

    public function getVoti() {
        require_once(INCDBDIR . 'voto.db.inc.php');
        return Voto::getByGiocatore($this);
    }

}

?>
