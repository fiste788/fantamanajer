<?php 
class Schieramento extends DbTable
{
	var $idFormazione;
	var $idPosizione;
	var $idGioc;
	var $considerato;	//0 = non ha giocato, 1 = giocato, 2 = capitano
	
	public static function getSchieramentoById($idFormazione)
	{
		$q = "SELECT * 
				FROM schieramento 
				WHERE idFormazione = '" . $idFormazione . "'
				ORDER BY idPosizione";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		FirePHP::getInstance()->log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values[] = $row;
		return $values;
	}
	
	public static function setConsiderazione($idFormazione,$idGioc,$val)
	{
		$q = "UPDATE schieramento 
				SET considerato = '" . $val . "'
				WHERE idFormazione = '" . $idFormazione . "' AND idGioc = '" . $idGioc . "'";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function changeGioc($idFormazione,$idGiocOld,$idGiocNew)
	{
		$q = "UPDATE schieramento 
				SET idGioc = '" . $idGiocNew . "' 
				WHERE idGioc = '" . $idGiocOld . "' AND idFormazione = '" . $idFormazione . "'";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function setGiocatore($idFormazione,$idGioc,$pos)
	{
		$q = "SELECT idGioc
					FROM schieramento
					WHERE idFormazione = '" . $idFormazione . "' AND idPosizione = '" . $pos . "'";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		FirePHP::getInstance()->log($q);
		$row = mysql_fetch_object($exe,__CLASS__);
		if(!empty($idGioc))
		{
			if(empty($row->idGioc))
				$q = "INSERT INTO schieramento(idFormazione,idGioc,idPosizione) 
						VALUES ('" . $idFormazione . "','" . $idGioc . "','" . $pos . "')";
			elseif($idGioc != $row->idGioc)
				$q = "UPDATE schieramento 
						SET idFormazione='" . $idFormazione . "',idGioc='" . $idGioc . "',idPosizione='" . $pos . "',considerato='0' 
						WHERE idFormazione = '" . $idFormazione . "' AND idPosizione = '" . $pos . "'";
			else
				$q = "UPDATE schieramento 
						SET considerato='0' 
						WHERE idFormazione = '" . $idFormazione . "' AND idPosizione = '" . $pos . "'";
		}
		elseif(!empty($row->idGioc))
			$q = "DELETE FROM schieramento 
						WHERE idFormazione = '" . $idFormazione . "' AND idPosizione = '" . $pos . "'";
		else
			return TRUE;
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
	}
	
	public static function unsetGiocatore($idFormazione,$pos)
	{
		$q = "DELETE FROM schieramento
					WHERE idFormazione = '" . $idFormazione . "' AND idPosizione = '" . $pos . "'";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
	}
}
?>
