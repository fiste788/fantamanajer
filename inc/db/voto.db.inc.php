<?php

require_once(TABLEDIR . 'Voto.table.db.inc.php');

class Voto extends VotoTable {

    /**
     *
     * @param type $idGiocatore
     * @param type $idGiornata
     * @return Voto
     */
    public static function getByGiocatoreAndGiornata($idGiocatore, $idGiornata) {
        $q = "SELECT *
				FROM voto
				WHERE idGiocatore = '" . $idGiocatore . "' AND idGiornata = '" . $idGiornata . "'";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            return $row;
    }

    /**
     *
     * @param Giocatore $giocatore
     * @return Voto[]
     */
    public static function getByGiocatore($giocatore) {
        $q = "SELECT *
				FROM voto
				WHERE idGiocatore = '" . $giocatore->getId() . "' AND valutato = 1
                ORDER BY idGiornata ASC";
        $exe = mysql_query($q) or self::sqlError($q);
        $values = array();
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            $values[$row->idGiornata] = $row;
        return $values;
    }

    public static function getPresenzaByIdGioc($idGioc, $giornata) {
        $q = "SELECT valutato
				FROM voto
				WHERE idGiocatore = '" . $idGioc . "' AND idGiornata='" . $giornata . "'";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            return $row->valutato;
    }

    public static function getMedieVoto($idGioc) {
        $q = "SELECT AVG(voto) as mediaPunti,AVG(punti) as mediaVoti,count(voto) as presenze
				FROM voto
				WHERE idGiocatore = '" . $idGioc . "' AND punti <> 0 AND voto <> 0
				GROUP BY idGiocatore";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe, __CLASS__))
            return $row;
    }

    public static function recuperaVoti($giorn) {
        require_once(INCDIR . 'fileSystem.inc.php');

        $percorso = "./docs/voti/csv/Giornata" . str_pad($giorn, 2, "0", STR_PAD_LEFT) . ".csv";
        if (FileSystem::scaricaVotiCsv($giorn)) {
            if (self::checkVotiExist($giorn))
                return TRUE;
            $q = "INSERT INTO voto(idGiocatore,idGiornata,punti,voto,gol,assist,rigori,ammonizioni,espulsioni) VALUES ";
            $content = file($percorso);
            if ($content != FALSE) {
                foreach ($content as $player) {
                    $pezzi = explode(";", $player);
                    if ($pezzi[2] == "P")
                        $pezzi[6] = -$pezzi[6];
                    $voti[] = "('" . $pezzi[0] . "','" . $giorn . "','" . $pezzi[4] . "','" . $pezzi[10] . "','" . $pezzi[5] . "','" . $pezzi[9] . "','" . $pezzi[6] . "','" . $pezzi[7] . "','" . $pezzi[8] . "')";
                }
                $q .= implode(',', $voti);
                return mysql_query($q) or self::sqlError($q);
            }
            else
                return FALSE;
        }
        else
            return FALSE;
    }

    public static function checkVotiExist($giornata) {
        $values = array();
        $q = "SELECT DISTINCT(idGiornata)
				FROM voto";
        $exe = mysql_query($q) or self::sqlError($q);
        FirePHP::getInstance()->log($q);
        while ($row = mysql_fetch_object($exe))
            $values[] = $row->idGiornata;
        return in_array($giornata, $values);
    }

    public static function importVoti($path, $giornata) {
        require_once(INCDIR . 'fileSystem.inc.php');

        $players = fileSystem::returnArray($path, ";");  //true per intestazione
        foreach ($players as $id => $stats) {
            $valutato = $stats[6]; //1=valutato,0=senzavoto
            $punti = $stats[7];
            $voto = $stats[10];
            $gol = $stats[11];
            $golsub = $stats[12];
            $golvit = $stats[13];
            $golpar = $stats[14];
            $assist = $stats[15];
            $ammonizioni = $stats[16];
            $espulsioni = $stats[17];
            $rigorisegn = $stats[18];
            $rigorisub = $stats[19];
            $presenza = $stats[23];
            $titolare = $stats[24];
            $quotazione = $stats[27];
            $rows[] = "('" . $id . "','" . $giornata . "','" . $valutato . "','" . $punti . "','" . $voto . "','" . $gol . "','" . $golsub . "','" . $golvit . "','" . $golpar . "','" . $assist . "','" . $ammonizioni . "','" . $espulsioni . "','" . $rigorisegn . "','" . $rigorisub . "','" . $presenza . "','" . $titolare . "','" . $quotazione . "')";
        }
        $q = "INSERT INTO voto (idGiocatore,idGiornata,valutato,punti,voto,gol,golSubiti,golVittoria,golPareggio,assist,ammonizioni,espulsioni,rigoriSegnati,rigoriSubiti,presente,titolare,quotazione) VALUES ";
        $q .= implode(',', $rows);
        mysql_query($q) or self::sqlError($q);
    }

}

?>
