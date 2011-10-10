<?php
require_once(TABLEDIR . 'Giornata.table.db.inc.php');

class Giornata extends GiornataTable
{
	public static function getGiornataByDate()
	{
		$minuti = isset($_SESSION['datiLega']) ? $_SESSION['datiLega']->minFormazione : 0;
		$q = "SELECT id
				FROM giornata 
				WHERE NOW() BETWEEN dataInizio AND dataFine - INTERVAL " . $minuti . " MINUTE";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$valore = mysql_fetch_assoc($exe);
		if (!empty($valore))
			$valore['partiteInCorso'] = FALSE;
		else
		{
			$q = "SELECT MIN( id -1 ) as idGiornata
				FROM giornata
				WHERE NOW() < dataFine - INTERVAL " . $minuti . " MINUTE";
			$exe = mysql_query($q) or self::sqlError($q);
			FirePHP::getInstance()->log($q);
			$valore = mysql_fetch_assoc($exe);
			$valore['partiteInCorso'] = TRUE;
		}
		$valore['stagioneFinita'] = $valore['id'] > (self::getNumberGiornate() - 1);
		return $valore;
	}
	
	public static function checkDay($day,$type = 'dataInizio',$offset = 1)
	{
		$q = "SELECT dataInizio,dataFine,id
				FROM giornata 
				WHERE '" . $day . "' BETWEEN dataInizio AND dataFine";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$value = mysql_fetch_assoc($exe);
		if(!empty($value))
		{
			$array = explode(" ",$value[$type]);
			$data = explode("-",$array[0]);
			$dataConfronto = date ("Y-m-d", mktime(0,0,0,$data[1],$data[2] + ($offset),$data[0]));
			if($day == $dataConfronto)
				return $value['idGiornata'];
			else
				return FALSE;
		}
		else
			return FALSE;
	}
	
	public static function getDataByGiornata($giornata)
	{
		$q = "SELECT * 
				FROM giornata 
				WHERE idGiornata = '" . $giornata . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		return mysql_fetch_object($exe,__CLASS__);
	}
	
	public static function getNumberGiornate()
	{
		$q = "SELECT COUNT(id) as numeroGiornate
				FROM giornata";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$values = mysql_fetch_object($exe);
		return $values->numeroGiornate;
	}
	
	public static function getAllGiornate()
	{
		$q = "SELECT * 
				FROM giornata";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$giornate[$row->idGiornata] = $row;
		return $giornate;
	}
	
	public static function getTargetCountdown()
	{
		$minuti = isset($_SESSION['datiLega']) ? $_SESSION['datiLega']->minFormazione : 0;
		$q = "SELECT MAX(dataFine) - INTERVAL " . $minuti . " MINUTE as dataFine
				FROM giornata
				WHERE NOW() > dataInizio";
		FirePHP::getInstance()->log($q);
		$exe = mysql_query($q) or self::sqlError($q);
		$values = mysql_fetch_object($exe,__CLASS__);
		return $values->dataFine;
	}
	
	public static function updateGiornate($giornate)
	{
		$bool = TRUE;
		foreach($giornate as $key => $val)
		{
			foreach($val as $key2 => $val2)
			{
				$q = "UPDATE giornata SET " . $key2 . " = '" . $val2 . "' WHERE idGiornata = '" . $key . "'";
				FirePHP::getInstance()->log($q);
				$bool *= mysql_query($q) or self::sqlError($q);
			}
		}
		return $bool;
	}
	
	private static function getArrayOrari($giornata) 
	{
		require_once(INCDIR . 'fileSystem.inc.php');
		$orari = FileSystem::scaricaOrariGiornata($giornata);
		$calendario[$giornata]['dataFine'] = date('Y-m-d H:i:s',$orari['inizioPartite']);
		$calendario[$giornata + 1]['dataInizio'] = date('Y-m-d H:i:s',$orari['finePartite'] + (2 * 3600));
		return $calendario;
	}
	
	public static function updateOrariGiornata($giornata = GIORNATA)
	{
		return self::updateGiornate(self::getArrayOrari($giornata));
	} 
	
	public static function updateCalendario()
	{
		require_once(INCDIR . 'fileSystem.inc.php');

		$calendario[1]['dataInizio'] = "2010-08-01 00:00:00";
		for($giornata = 1;$giornata <= 38;$giornata++)
		{
			$appo = self::getArrayOrari($giornata);
			$calendario[$giornata] = array_merge($calendario[$giornata],$appo[$giornata]);
			$calendario[$giornata + 1] = array_merge($calendario[$giornata],$appo[$giornata + 1]);
		}
		$calendario[39]['dataFine'] = "2011-07-31 23:59:59";
		return self::updateGiornate($calendario);
	} 
	
	public static function getTimeDiff($t1,$t2)
	{
	    $t2 = ($t2) || date("H:i:s");
		$a1 = explode(":",$t1);
		$a2 = explode(":",$t2);
		$time1 = (($a1[0] * 60 * 60) + ($a1[1] * 60) + ($a1[2]));
		$time2 = (($a2[0] * 60 * 60) + ($a2[1] * 60) + ($a2[2]));
		$diff = abs($time1 - $time2);
		return $diff;
	}
}
?>
