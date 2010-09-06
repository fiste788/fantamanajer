<?php
class Giornata extends DbTable
{
	var $idGiornata;
	var $dataInizio;
	var $dataFine;
	
	public static function getGiornataByDate()
	{
		$minuti = isset($_SESSION['datiLega']) ? $_SESSION['datiLega']->minFormazione : 0;
		$q = "SELECT idGiornata 
				FROM giornata 
				WHERE NOW() BETWEEN dataInizio AND dataFine - INTERVAL " . $minuti . " MINUTE";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		$valore = mysql_fetch_assoc($exe);
		if (!empty($valore))
			$valore['partiteInCorso'] = FALSE;
		else
		{
			$q = "SELECT MIN( idGiornata -1 ) as idGiornata
				FROM giornata
				WHERE NOW() < dataFine - INTERVAL " . $minuti . " MINUTE";
			$exe = mysql_query($q) or self::sqlError($q);
			if(DEBUG)
				FB::log($q);
			$valore = mysql_fetch_assoc($exe);
			$valore['partiteInCorso'] = TRUE;
		}
		$valore['stagioneFinita'] = $valore['idGiornata'] > (self::getNumberGiornate() - 1) ? TRUE : FALSE;
		return $valore;
	}
	
	public static function checkDay($day)
	{
		$q = "SELECT dataInizio,idGiornata 
				FROM giornata 
				WHERE '" . $day . "' BETWEEN dataInizio AND dataFine";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		$value = mysql_fetch_assoc($exe);
		if(!empty($value))
		{
			$array = explode(" ",$value['dataInizio']);
			$data = explode("-",$array[0]);
			$data2dayAfter = date ("Y-m-d", mktime(0,0,0,$data[1],$data[2] + 1,$data[0]));
			if($day == $data2dayAfter)
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
		if(DEBUG)
			FB::log($q);
		return mysql_fetch_object($exe,__CLASS__);
	}
	
	public static function getNumberGiornate()
	{
		$q = "SELECT COUNT(idGiornata) as numeroGiornate
				FROM giornata";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		$values = mysql_fetch_object($exe);
		return $values->numeroGiornate;
	}
	
	public static function getAllGiornate()
	{
		$q = "SELECT * 
				FROM giornata";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
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
		if(DEBUG)
			FB::log($q);
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
				if(DEBUG)
					FB::log($q);
				$bool *= mysql_query($q) or self::sqlError($q);
			}
		}
		return $bool;
	}
	
	public static function updateOrariGiornata()
	{
    require_once(INCDIR . 'fileSystem.inc.php');
    $giornata=GIORNATA;
    $orari=FileSystem::scaricaOrariGiornata($giornata); 
    $calendario[$giornata]['dataFine']=date('Y-m-d H:i:s',$orari['inizioPartite']);
    $calendario[$giornata+1]['dataInizio']=date('Y-m-d H:i:s',$orari['finePartite']+2*3600);
    self::updateGiornate($calendario);
  }
	
  public static function updateCalendario()
  {
    require_once(INCDIR . 'fileSystem.inc.php');
    $calendario[1]['dataInizio']="2010-08-01 00:00:00";
    for($giornata=1;$giornata<=38;$giornata++)
    {
      $orari=FileSystem::scaricaOrariGiornata($giornata); 
      $calendario[$giornata]['dataFine']=date('Y-m-d H:i:s',$orari['inizioPartite']);
      $calendario[$giornata+1]['dataInizio']=date('Y-m-d H:i:s',$orari['finePartite']+2*3600);
    }
    $calendario[39]['dataFine']="2011-07-31 23:59:59";
    self::updateGiornate($calendario);
  } 
}
?>
