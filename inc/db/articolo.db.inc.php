<?php 
require_once(TABLEDIR . 'Articolo.table.db.inc.php');

class Articolo extends ArticoloTable
{
	public static function getArticoliByGiornataAndLega($idGiornata,$idLega)
	{
		$q = "SELECT articolo.*,utente.username
				FROM articolo INNER JOIN utente ON articolo.idUtente = utente.id
				WHERE idGiornata = '" . $idGiornata . "' AND utente.idLega = '" . $idLega . "'";
		FirePHP::getInstance()->log($q);
		$exe = mysql_query($q) or self::sqlError($q);
		$values = array();
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values[$row->id] = $row;
		return $values;
	}

	public static function getLastArticoli($number)
	{
		$q = "SELECT * 
				FROM articolo INNER JOIN utente ON articolo.idUtente = utente.id
				ORDER BY dataCreazione DESC
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
			$values[$row->idGiornata] = $row->idGiornata;
		return $values;
	}
	
	public function check($array,$message) {
		$post = (object) $array;
		$GLOBALS['firePHP']->log($array);
    	if(empty($post->title) || empty($post->text)) {
			$message->error("Non hai compilato correttamente tutti i campi");
			return FALSE;
  		}
  		return TRUE;
	}
}
?>
