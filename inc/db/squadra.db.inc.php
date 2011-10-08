<?php 
class Squadra extends DbTable
{
	var $idLega;
	var $idUtente;
	var $idGioc;
	
	public static function setSquadraGiocatoreByArray($idLega,$giocatori,$idUtente)
	{
		$q = "INSERT INTO squadra VALUES ";
		foreach($giocatori as $key => $val)
			$row[] = "('" . $idLega . "','" . $idUtente . "','" . $val . "')";
		$q .= implode(',',$row);
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function unsetSquadraGiocatoreByIdSquadra($idUtente)
	{
		$q = "DELETE 
				FROM squadra 
				WHERE idUtente = '" . $idUtente . "'";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function updateGiocatore($giocatoreNew,$giocatoreOld,$idUtente)
	{
		$q = "UPDATE squadra 
				SET idGioc = '" . $giocatoreNew . "' 
				WHERE idGioc = '" . $giocatoreOld . "' AND idUtente = '" . $idUtente . "'";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function updateGiocatoreSquadra($idGioc,$idLega,$idUtente)
	{
		$q = "UPDATE squadra 
				SET idUtente = '" . $idUtente . "' 
				WHERE idGioc = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function getSquadraByIdGioc($idGioc,$idLega)
	{
		$q = "SELECT idUtente
				FROM squadra 
				WHERE idGioc = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		$values = FALSE;
		FirePHP::getInstance()->log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values = $row->idUtente;
		return $values;
	}
	
	public static function setSquadraByIdGioc($idGioc,$idLega,$idUtente)
	{
		$q = "INSERT INTO squadra 
				VALUES ('" . $idLega . "','" . $idUtente . "','" . $idGioc . "')";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function unsetSquadraByIdGioc($idGioc,$idLega)
	{
		$q = "DELETE
				FROM squadra 
				WHERE idGioc = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
}
?>
