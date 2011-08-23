<?php
class Club extends DbTable
{
	public static function getClubById($idClub)
	{		
		$q = "SELECT * 
				FROM club 
				WHERE idClub = '" . $idClub . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		return mysql_fetch_object($exe,__CLASS__); 
	}
	
	public static function getClubByIdWithStats($idClub)
	{
		$q = "SELECT *
				FROM clubstatistiche
				WHERE idClub = '" . $idClub . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		return mysql_fetch_object($exe,__CLASS__);
	}
	
	public static function getElencoClub()
	{		
		$q = "SELECT * 
				FROM club";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__) )
			$values[$row->idClub] = $row;
		return $values; 
	}

	
}
?>
