<?php

namespace Fantamanajer\Models;

use DateTime;
use Fantamanajer\Lib\FileSystem;
use Fantamanajer\Models\Table\MatchdaysTable;
use FirePHP;
use Lib\Database\ConnectionFactory;
use PDO;
use PDOException;
use Symfony\Component\DomCrawler\Crawler;

class Matchday extends MatchdaysTable {

    /**
     *
     * @var boolean
     */
    private $_isSeasonEnded;

    /**
     *
     * @return boolean
     */
    public function isSeasonEnded() {
        return (boolean) $this->_isSeasonEnded;
    }

    /**
     *
     * @param boolean $isSeasonEnded
     */
    public function setSeasonEnded($isSeasonEnded) {
        $this->_isSeasonEnded = (boolean) $isSeasonEnded;
    }

    /**
     *
     * @return Matchday
     */
    public static function getCurrent() {
        $minutes = isset($_SESSION['championship_data']) ? $_SESSION['championship_data']->minute_lineup : 0;
        $q = "SELECT MIN(id) as id, date, number, season_id
		FROM " . self::TABLE_NAME . "
		WHERE NOW() < date - INTERVAL :minutes MINUTE";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":minutes", $minutes, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $value = $exe->fetchObject(__CLASS__);
        $value->setSeasonEnded($value->getNumber() > (self::getNumberOfMatchday($value->getSeason()) - 1));
        return $value;
    }

    public static function isWeeklyScriptDay(Giornata $giornata = NULL) {
        $giornata = !is_null($giornata) ? $giornata : self::getCurrent();
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

    /**
     * 
     * @param Season $season
     * @return int
     */
    public static function getNumberOfMatchday($season) {
        $q = "SELECT COUNT(id) as number
		FROM " . self::TABLE_NAME .
                " WHERE season_id = :season_id";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":season_id", $season->getId(), PDO::PARAM_INT);
        $exe->execute();
        return $exe->fetchColumn();
    }

    /**
     * 
     * @return DateTime
     */
    public static function getTargetCountdown() {
        $minuti = isset($_SESSION['league_data']) ? $_SESSION['league_data']->minute_lineup : 0;
        $q = "SELECT MIN(date) - INTERVAL :minutes MINUTE as date
				FROM " . self::TABLE_NAME .
                " WHERE NOW() < date";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":minutes", $minuti, PDO::PARAM_INT);
        $exe->execute();
        return $exe->fetchObject(__CLASS__)->getDate();
    }

    /**
     * 
     * @return boolean
     * @throws PDOException
     */
    public static function updateCalendar() {
        try {
            ConnectionFactory::getFactory()->getConnection()->beginTransaction();
            $matchdays = self::getList();
            $flag = TRUE;
            foreach ($matchdays as $matchday) {
                if ($flag && $matchday->getNumber() < 39) {
                    $flag &= $matchday->updateOrario() != "";
                }
            }

            if ($flag) {
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

    public function updateSchedule() {
        $date = self::downloadMatchdaySchedule($this->getNumber());
        if ($date != FALSE) {
            $this->setDate($date);
            return $this->save();
        } else {
            return FALSE;
        }
    }

    /* public static function updateCalendario() {
      require_once(INCDIR . 'fileSystem.inc.php');

      $calendario[1]['dataInizio'] = "2012-08-01 00:00:00";
      for ($giornata = 1; $giornata <= 38; $giornata++) {
      $appo = self::getArrayOrari($giornata);
      $calendario[$giornata] = array_merge($calendario[$giornata], $appo[$giornata]);
      $calendario[$giornata + 1] = array_merge($calendario[$giornata], $appo[$giornata + 1]);
      }
      $calendario[39]['dataFine'] = "2013-07-31 23:59:59";
      return self::updateGiornate($calendario);
      } */

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
     * @param int $matchdayNumber
     * @return \DateTime
     */
    public static function downloadMatchdaySchedule($matchdayNumber) {
        //$content = FileSystem::contenutoCurl("http://www.legaseriea.it/it/serie-a-tim/campionato-classifica?p_p_id=BDC_tabellone_partite_giornata_WAR_LegaCalcioBDC&p_p_lifecycle=0&p_p_state=normal&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=1&_BDC_tabellone_partite_giornata_WAR_LegaCalcioBDC_numeroGiornata=$giornata");
        $content = FileSystem::contenutoCurl("http://www.legaseriea.it/it/serie-a-tim/calendario-e-risultati/2015-16/UNICO/UNI/$giornata");
        if ($content != "") {
            $crawler = new Crawler();
            $crawler->addContent($content);
            $box = $crawler->filter(".datipartita")->first()->filter("p")->first()->filter("span");
            $date = $box->text();
            if ($date != "") {
                return \DateTime::createFromFormat("!d/m/Y H:i", $date);
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
