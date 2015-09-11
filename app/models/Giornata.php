<?php

namespace Fantamanajer\Models;

use DateTime;
use Fantamanajer\Lib\FileSystem;
use Fantamanajer\Models\Table\GiornataTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use PDO;
use PDOException;
use Symfony\Component\DomCrawler\Crawler;

class Giornata extends GiornataTable {

    /**
     *
     * @var bool
     */
    private $_stagioneFinita;

    /**
     *
     * @return bool
     */
    public function isStagioneFinita() {
        return (bool) $this->_stagioneFinita;
    }

    /**
     *
     * @param bool $stagioneFinita
     */
    public function setStagioneFinita($stagioneFinita) {
        $this->_stagioneFinita = (bool) $stagioneFinita;
    }


    /**
     *
     * @return Giornata
     */
    public static function getCurrentGiornata() {
        $minuti = isset($_SESSION['datiLega']) ? $_SESSION['datiLega']->minFormazione : 0;
        $q = "SELECT MIN(id) as id, data
				FROM giornata
				WHERE NOW() < data - INTERVAL :minuti MINUTE";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":minuti", $minuti, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $valore = $exe->fetchObject(__CLASS__);
        $valore->setStagioneFinita($valore->getId() > (self::getNumberGiornate() - 1));
        return $valore;
    }

    public static function isWeeklyScriptDay(Giornata $giornata = NULL) {
        $giornata = !is_null($giornata) ? $giornata : self::getCurrentGiornata();
        $now = new DateTime();
        $now->modify("-1 day");
        $previous = self::getById($giornata->getId() - 1);
        return ($previous->getData() < $now && $now->format("H") > 17);
    }

    public static function isDoTransertDay(Giornata $giornata = NULL) {
        $giornata = !is_null($giornata) ? $giornata : self::getCurrentGiornata();
        $now = new DateTime();
        return ($giornata->getData()->format("Y-m-d") === $now->format("Y-m-d"));
    }

    public static function isSendMailDay(Giornata $giornata = NULL) {
        $giornata = !is_null($giornata) ? $giornata : self::getCurrentGiornata();
        $now = new DateTime();
        $difference = abs($giornata->getData()->getTimestamp() - $now->getTimestamp()) / 60;
        return $difference < 15;
    }

    public static function checkDay($day, $type = 'dataInizio', $offset = 1) {
        $q = "SELECT MIN(id) as id, data
				FROM giornata
				WHERE NOW() < data";
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        $value = $exe->fetch(PDO::FETCH_ASSOC);
        if ($value != FALSE) {
            $array = explode(" ", $value[$type]);
            $data = explode("-", $array[0]);
            $dataConfronto = date("Y-m-d", mktime(0, 0, 0, $data[1], $data[2] + ($offset), $data[0]));
            if ($day == $dataConfronto)
                return $value['id'];
            else
                return FALSE;
        } else
            return FALSE;
    }

    public static function getNumberGiornate() {
        $q = "SELECT COUNT(id) as numeroGiornate
				FROM giornata";
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        return $exe->fetchColumn();
    }

    /**
     * 
     * @return DateTime
     */
    public static function getTargetCountdown() {
        $minuti = isset($_SESSION['datiLega']) ? $_SESSION['datiLega']->minFormazione : 0;
        $q = "SELECT MIN(data) - INTERVAL :minuti MINUTE as data
				FROM giornata
				WHERE NOW() < data";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":minuti", $minuti, PDO::PARAM_INT);
        $exe->execute();
        return $exe->fetchObject(__CLASS__)->getData();
    }

    /**
     * 
     * @return boolean
     * @throws PDOException
     */
    public static function updateCalendario() {
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            $giornate = self::getList();
            $flag = TRUE;
            foreach ($giornate as $giornata) {
                if($flag && $giornata->getId() < 39) {
                    $flag &= $giornata->updateOrario() != "";
                }
            }
            
            if($flag) {
                ConnectionFactory::getFactory()->getConnection()->commit();
            } else {
                ConnectionFactory::getFactory()->getConnection()->roolBack();
            }
        } catch (PDOException $e) {
            ConnectionFactory::getFactory()->getConnection()->rollBack();
            throw $e;
        }
        return TRUE;
    }

    public function updateOrario() {
        $data = self::scaricaOrarioGiornata($this->getId());
        if($data != FALSE) {
            $this->setData($data);
            return $this->save();
        } else {
            return FALSE;
        }
    }

    /*public static function updateCalendario() {
        require_once(INCDIR . 'fileSystem.inc.php');

        $calendario[1]['dataInizio'] = "2012-08-01 00:00:00";
        for ($giornata = 1; $giornata <= 38; $giornata++) {
            $appo = self::getArrayOrari($giornata);
            $calendario[$giornata] = array_merge($calendario[$giornata], $appo[$giornata]);
            $calendario[$giornata + 1] = array_merge($calendario[$giornata], $appo[$giornata + 1]);
        }
        $calendario[39]['dataFine'] = "2013-07-31 23:59:59";
        return self::updateGiornate($calendario);
    }*/

    public static function getTimeDiff($t1, $t2 = NULL) {
        $t2 = (is_null($t2)) ? date("H:i:s") : $t2;
        $a1 = explode(":", $t1);
        $a2 = explode(":", $t2);
        $time1 = (($a1[0] * 60 * 60) + ($a1[1] * 60) + ($a1[2]));
        $time2 = (($a2[0] * 60 * 60) + ($a2[1] * 60) + ($a2[2]));
        $diff = abs($time1 - $time2);
        return $diff;
    }
    
    /**
     * 
     * @param int $giornata
     * @return \DateTime
     */
    public static function scaricaOrarioGiornata($giornata) {
        $content = FileSystem::contenutoCurl("http://www.legaseriea.it/it/serie-a-tim/campionato-classifica?p_p_id=BDC_tabellone_partite_giornata_WAR_LegaCalcioBDC&p_p_lifecycle=0&p_p_state=normal&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=1&_BDC_tabellone_partite_giornata_WAR_LegaCalcioBDC_numeroGiornata=$giornata");
        if ($content != "") {
            $crawler = new Crawler();
            $crawler->addContent($content);
            $box = $crawler->filter(".chart_box")->first()->filter(".description p")->first()->filter("strong");
            $data = $box->text();
            if($data != "") {
                return \DateTime::createFromFormat("!d/m/Y H:i",$data);
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
    
    public function __toString() {
        echo $this->id;
    }

}

 