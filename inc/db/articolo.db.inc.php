<?php 
require_once(TABLEDIR . 'Articolo.table.db.inc.php');

class Articolo extends ArticoloTable
{
	public static function addArticolo($title,$abstract,$text,$idUtente,$idGiornata,$idLega) {
		$q = "INSERT INTO articolo (title , abstract , text , insertDate , idUtente, idGiornata, idLega)
				VALUES ('" . $title . "' , '" . $abstract . "' , '" . $text . "' , '" . date("Y-m-d H:i:s") . "' , '" . $idUtente . "' , '" . $idGiornata . "' , '" . $idLega . "')";
		FirePHP::getInstance()->log($q);
		mysql_query($q) or self::sqlError($q);
		$q = "SELECT id
				FROM articolo 
				WHERE title = '" . $title . "' AND abstract = '" . $abstract . "' AND text = '" . $text . "' AND idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "' AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$data = mysql_fetch_object($exe);
		return $data->id;
	}
	
	public static function updateArticolo($idArticolo,$title,$abstract,$text,$idUtente,$idLega)
	{
		$q = "UPDATE articolo 
				SET title = '" . $title . "' , abstract = '" . $abstract . "' , text = '" . $text . "' , idUtente = '" . $idUtente . "', idLega = '" . $idLega . "'
				WHERE id = '" . $idArticolo . "'";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function deleteArticolo($idArticolo)
	{
		$q = "DELETE 
				FROM articolo 
				WHERE id = '" . $idArticolo . "'";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function getArticoliByGiornataAndLega($idGiornata,$idLega)
	{
		$q = "SELECT * 
				FROM articolo INNER JOIN utente ON articolo.idUtente = utente.id
				WHERE idGiornata = '" . $idGiornata . "' AND articolo.idLega = '" . $idLega . "'"; 
		$values = FALSE;
		FirePHP::getInstance()->log($q);
		$exe = mysql_query($q) or self::sqlError($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values[$row->id] = $row;
		return $values;
	}
	
	public static function getArticoliByIdUtente($idUtente)
	{
		$q = "SELECT *
				FROM articolo
				WHERE idUtente = '" . $idUtente . "'";
		$values = FALSE;
		FirePHP::getInstance()->log($q);
		$exe = mysql_query($q) or self::sqlError($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values[$row->id] = $row;
		return $values;
	}

	public static function getLastArticoli($number)
	{
		$q = "SELECT * 
				FROM articolo INNER JOIN utente ON articolo.idUtente = utente.id
				ORDER BY insertDate DESC
				LIMIT 0," . $number . ""; 
		$values = FALSE;
		FirePHP::getInstance()->log($q);
		$exe = mysql_query($q) or self::sqlError($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values[$row->id] = $row;
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
				WHERE id = '" . $idArticolo . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		return mysql_fetch_object($exe,__CLASS__);
	}
}
?>
