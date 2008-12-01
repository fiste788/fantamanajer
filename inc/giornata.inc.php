<?php
class giornata 
{
	var $idGiornata;
	var $dataInizio;
	var $dataFine;
	var $dataCenterInizio;	//inizio della giornata in cui non si può settare la formazione
	var $dataCeterFine;	//fine della giornata in cui non si può settare la formazione
	
	function getIdGiornataByDate()
	{
		$q = "SELECT idGiornata 
				FROM giornate 
				WHERE '" . date("Y-m-d H:i:s") . "' BETWEEN dataInizio AND dataFine";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$valore = mysql_fetch_row($exe);
		return $valore[0];
	}
	
	function checkDay($day)
	{
		$q = "SELECT dataInizio,idGiornata 
				FROM giornate 
				WHERE '" . $day . "' BETWEEN dataInizio AND dataFine";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$value = mysql_fetch_row($exe);
		if(!empty($value))
		{
			$array = explode(" ",$value[0]);
			$data = explode("-",$array[0]);
			$data2dayAfter = date ("Y-m-d", mktime(0,0,0,$data[1],$data[2]+2,$data[0]));
			if($day == $data2dayAfter)
				return $value[1];
			else
				return FALSE;
		}
		else
			return FALSE;
	}
	
	function getIdGiornataByDateSecondary()
	{
		$q = "SELECT idGiornata 
				FROM giornate 
				WHERE '" . date("Y-m-d H:i:s") . "' BETWEEN dataCenterInizio AND dataCenterFine";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$valore = mysql_fetch_row($exe);
		return $valore[0];
	}
	
	function getDataByGiornata($giorn)
	{
		$q = "SELECT * 
				FROM giornate 
				WHERE idGiornata = '" . $giorn . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$valore = mysql_fetch_row($exe);
		return $valore;
	}
	
	function getNumberGiornate()
	{
		$q = "SELECT COUNT(idGiornata) 
				FROM giornate";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$valore = mysql_fetch_row($exe);
		return $valore[0];
	}
}
?>
