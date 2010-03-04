<?php 
class Selezione extends DbTable
{
	var $idLega;
	var $idSquadra;
	var $giocOld;
	var $giocNew;
	var $numSelezioni;
	
	public static function getSelezioni()
	{
		$q = "SELECT * 
				FROM selezione INNER JOIN giocatore ON giocNew = idGioc";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values[] = $row;
		if(isset($values))
			return $values;
		else
			return FALSE;
	}
	
	public static function getSelezioneByIdSquadra($idSquadra)
	{
		$q = "SELECT * 
				FROM selezione INNER JOIN giocatore ON giocNew = idGioc 
				WHERE idSquadra = '" . $idSquadra . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		return mysql_fetch_object($exe,__CLASS__);
	}
	
	public static function unsetSelezioneByIdSquadra($idSquadra)
	{
		$q = "UPDATE selezione 
				SET giocOld = NULL,giocNew = NULL 
				WHERE idSquadra = '" . $idSquadra . "';";
		if(DEBUG)
			FB::log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function checkFree($idGioc,$idLega)
	{
		$q = "SELECT idSquadra 
				FROM selezione 
				WHERE giocNew = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		$values = array();
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values = $row;
		if(!empty($values))
			return $values->idSquadra;
		else
			return FALSE;
	}
	
	public static function updateGioc($giocNew,$giocOld,$idLega,$idSquadra)
	{
		self::startTransaction();
		$q = "SELECT numSelezioni 
				FROM selezione 
				WHERE giocNew = '" . $giocNew . "' AND idLega = '" . $idLega . "' LOCK IN SHARE MODE";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		if(DEBUG)
			FB::log($q);
		$values = array();
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values = $row;
		if(!empty($values))
		{
			$q = "UPDATE selezione 
					SET giocOld = '0', giocNew = NULL, numSelezioni = '" . ($values->numSelezioni - 1) . "' 
					WHERE giocNew = '" . $giocNew . "' AND idLega = '" . $idLega . "'";
			if(DEBUG)
				FB::log($q);
			mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		}
		$q = "SELECT numSelezioni 
				FROM selezione 
				WHERE giocNew IS NOT NULL AND idSquadra = '" . $idSquadra . "'  LOCK IN SHARE MODE";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		if(DEBUG)
			FB::log($q);
		$values = array();
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values = $row;
		if(!empty($values))
		{
			$q = "UPDATE selezione 
					SET giocOld = '" . $giocOld . "', giocNew = '" . $giocNew . "',numSelezioni = '" . ($values->numSelezioni + 1) . "' 
					WHERE idSquadra = '" . $idSquadra . "'";
			if(DEBUG)
				FB::log($q);
			mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		}
		else
		{
			$q = "INSERT INTO selezione 
					VALUES ('" . $idLega . "','" . $idSquadra . "','" . $giocOld . "','" . $giocNew . "','1')";
			if(DEBUG)
				FB::log($q);
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
				WHERE idSquadra = '" . $idUtente . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		$val = NULL;
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__) )
			$val = $row->numSelezioni;
		return $val;
	}
	
	public static function svuota()
	{
		$q = "TRUNCATE TABLE selezione";
		if(DEBUG)
			FB::log($q);
		return mysql_query($q) or self::sqlError($q);
	}
}
?>
