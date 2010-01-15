<?php
class giornata extends dbTable
{
	var $idGiornata;
	var $dataInizio;
	var $dataFine;
	
	function getGiornataByDate()
	{
		$minuti = isset($_SESSION['datiLega']) ? $_SESSION['datiLega']->minFormazione : 0;
		$q = "SELECT idGiornata 
				FROM giornata 
				WHERE NOW() BETWEEN dataInizio AND dataFine - INTERVAL " . $minuti . " MINUTE";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			echo $q . " (" . __FUNCTION__ . " at line " . __LINE__ . ")<br />";
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
				echo $q . " (" . __FUNCTION__ . " at line " . __LINE__ . ")<br />";
			$valore = mysql_fetch_assoc($exe);
			$valore['partiteInCorso'] = TRUE;
		}
		$valore['stagioneFinita'] = $valore['idGiornata'] > ($this->getNumberGiornate() - 1) ? TRUE : FALSE;
		return $valore;
	}
	
	function checkDay($day)
	{
		$q = "SELECT dataInizio,idGiornata 
				FROM giornata 
				WHERE '" . $day . "' BETWEEN dataInizio AND dataFine";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			echo $q . " (" . __FUNCTION__ . " at line " . __LINE__ . ")<br />";
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
	
	function getDataByGiornata($giornata)
	{
		$q = "SELECT * 
				FROM giornata 
				WHERE idGiornata = '" . $giornata . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			echo $q . " (" . __FUNCTION__ . " at line " . __LINE__ . ")<br />";
		return mysql_fetch_object($exe);
	}
	
	function getNumberGiornate()
	{
		$q = "SELECT COUNT(idGiornata) as numeroGiornate
				FROM giornata";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			echo $q . " (" . __FUNCTION__ . " at line " . __LINE__ . ")<br />";
		$values = mysql_fetch_object($exe);
		return $values->numeroGiornate;
	}
	
	function getAllGiornate()
	{
		$q = "SELECT * 
				FROM giornata";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			echo $q . " (" . __FUNCTION__ . " at line " . __LINE__ . ")<br />";
		while($row = mysql_fetch_object($exe))
			$giornate[$row->idGiornata] = $row;
		return $giornate;
	}
	
	function getTargetCountdown()
	{
		$minuti = isset($_SESSION['datiLega']) ? $_SESSION['datiLega']->minFormazione : 0;
		$q = "SELECT MAX(dataFine) - INTERVAL " . $minuti . " MINUTE as dataFine
				FROM giornata
				WHERE NOW() > dataInizio";
		if(DEBUG)
			echo $q . " (" . __FUNCTION__ . " at line " . __LINE__ . ")<br />";
		$exe = mysql_query($q) or self::sqlError($q);
		$values = mysql_fetch_object($exe);
		return $values->dataFine;
	}
	
	function updateGiornate($giornate)
	{
		$bool = TRUE;
		foreach($giornate as $key => $val)
		{
			foreach($val as $key2 => $val2)
			{
				$q = "UPDATE giornata SET " . $key2 . " = '" . $val2 . "' WHERE idGiornata = '" . $key . "'";
				if(DEBUG)
					echo $q . " (" . __FUNCTION__ . " at line " . __LINE__ . ")<br />";
				$bool *= mysql_query($q) or self::sqlError($q);
			}
		}
		return $bool;
	}
}
?>
