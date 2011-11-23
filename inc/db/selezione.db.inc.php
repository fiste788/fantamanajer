<?php 
require_once(TABLEDIR . 'Selezione.table.db.inc.php');

class Selezione extends SelezioneTable
{
	public static function getSelezioni()
	{
		$q = "SELECT * 
				FROM selezione INNER JOIN giocatore ON giocNew = idGioc";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values[] = $row;
		if(isset($values))
			return $values;
		else
			return FALSE;
	}
	
	public static function getSelezioneByidSquadra($idUtente)
	{
		$q = "SELECT * 
				FROM selezione INNER JOIN giocatore ON giocNew = idGioc 
				WHERE idUtente = '" . $idUtente . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		return mysql_fetch_object($exe,__CLASS__);
	}
	
	public static function unsetSelezioneByidSquadra($idUtente)
	{
		$q = "UPDATE selezione 
				SET giocOld = NULL,giocNew = NULL 
				WHERE idUtente = '" . $idUtente . "';";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function checkFree($idGioc,$idLega)
	{
		$q = "SELECT idUtente
				FROM selezione 
				WHERE giocNew = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$values = array();
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values = $row;
		if(!empty($values))
			return $values->idUtente;
		else
			return FALSE;
	}
	
	public static function updateGioc($giocNew,$giocOld,$idLega,$idUtente)
	{
		self::startTransaction();
		$q = "SELECT numSelezioni 
				FROM selezione 
				WHERE giocNew = '" . $giocNew . "' AND idLega = '" . $idLega . "' LOCK IN SHARE MODE";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		FirePHP::getInstance()->log($q);
		$values = array();
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values = $row;
		if(!empty($values))
		{
			$q = "UPDATE selezione 
					SET giocOld = '0', giocNew = NULL, numSelezioni = '" . ($values->numSelezioni - 1) . "' 
					WHERE giocNew = '" . $giocNew . "' AND idLega = '" . $idLega . "'";
			if(DEBUG)
				FirePHP::getInstance(true)->log($q);
			mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		}
		$q = "SELECT numSelezioni 
				FROM selezione 
				WHERE giocNew IS NOT NULL AND idUtente = '" . $idUtente . "'  LOCK IN SHARE MODE";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		FirePHP::getInstance()->log($q);
		$values = array();
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values = $row;
		if(!empty($values))
		{
			$q = "UPDATE selezione 
					SET giocOld = '" . $giocOld . "', giocNew = '" . $giocNew . "',numSelezioni = '" . ($values->numSelezioni + 1) . "' 
					WHERE idUtente = '" . $idUtente . "'";
			if(DEBUG)
				FirePHP::getInstance(true)->log($q);
			mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		}
		else
		{
			$q = "INSERT INTO selezione 
					VALUES ('" . $idLega . "','" . $idUtente . "','" . $giocOld . "','" . $giocNew . "','1')";
			if(DEBUG)
				FirePHP::getInstance(true)->log($q);
			mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;	
		}
		if(isset($err))
		{
			self::rollback();
			self::sqlError("Errore nella transazione: <br />" . $err);
		}
		else
			self::commit();
	}
	
	public static function getNumberSelezioni($idUtente)
	{
		$q = "SELECT numSelezioni 
				FROM selezione 
				WHERE idUtente = '" . $idUtente . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		$val = NULL;
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__) )
			$val = $row->numSelezioni;
		return $val;
	}
	
	public static function svuota()
	{
		$q = "TRUNCATE TABLE selezione";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
}
?>
