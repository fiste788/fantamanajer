<?php 
class Articolo extends DbTable
{
	var $idArticolo;
	var $title;
	var $abstract;
	var $text;
	var $insertDate;
	var $idSquadra;
	var $idGiornata;
	var $idLega;
	
	public static function addArticolo($title,$abstract,$text,$idUtente,$idGiornata,$idLega) {
		$q = "INSERT INTO articolo (title , abstract , text , insertDate , idSquadra, idGiornata, idLega) 
				VALUES ('" . $title . "' , '" . $abstract . "' , '" . $text . "' , '" . date("Y-m-d H:i:s") . "' , '" . $idUtente . "' , '" . $idGiornata . "' , '" . $idLega . "')";
		FirePHP::getInstance()->log($q);
		mysql_query($q) or self::sqlError($q);
		$q = "SELECT idArticolo 
				FROM articolo 
				WHERE title = '" . $title . "' AND abstract = '" . $abstract . "' AND text = '" . $text . "' AND idSquadra = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "' AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$data = mysql_fetch_object($exe);
		return $data->idArticolo;
	}
	
	public static function updateArticolo($idArticolo,$title,$abstract,$text,$idUtente,$idLega)
	{
		$q = "UPDATE articolo 
				SET title = '" . $title . "' , abstract = '" . $abstract . "' , text = '" . $text . "' , idSquadra = '" . $idUtente . "', idLega = '" . $idLega . "'  
				WHERE idArticolo = '" . $idArticolo . "'";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function deleteArticolo($idArticolo)
	{
		$q = "DELETE 
				FROM articolo 
				WHERE idArticolo = '" . $idArticolo . "'";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function getArticoliByGiornataAndLega($idGiornata,$idLega)
	{
		$q = "SELECT * 
				FROM articolo INNER JOIN utente ON articolo.idSquadra = utente.idUtente 
				WHERE idGiornata = '" . $idGiornata . "' AND articolo.idLega = '" . $idLega . "'"; 
		$values = FALSE;
		FirePHP::getInstance()->log($q);
		$exe = mysql_query($q) or self::sqlError($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values[$row->idArticolo] = $row;
		return $values;
	}

	public static function getLastArticoli($number)
	{
		$q = "SELECT * 
				FROM articolo INNER JOIN utente ON articolo.idSquadra = utente.idUtente 
				ORDER BY insertDate DESC
				LIMIT 0," . $number . ""; 
		$values = FALSE;
		FirePHP::getInstance()->log($q);
		$exe = mysql_query($q) or self::sqlError($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values[$row->idArticolo] = $row;
		return $values;
	}
	
	public static function getGiornateArticoliExist($idLega)
	{
		$q = "SELECT DISTINCT idGiornata 
				FROM articolo
				WHERE idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$values = FALSE;
		while($row = mysql_fetch_object($exe))
			$values[] = $row->idGiornata;
		return $values;
	}
	
	public static function getArticoloById($idArticolo)
	{
		$q = "SELECT * 
				FROM articolo 
				WHERE idArticolo = '" . $idArticolo . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		return mysql_fetch_object($exe,__CLASS__);
	}
}
?>
