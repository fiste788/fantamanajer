<?php 
class selezione
{
	var $idLega;
	var $idSquadra;
	var $giocOld;
	var $giocNew;
	var $numSelezioni;
	
	function getSelezioni()
	{
		$q = "SELECT * 
				FROM selezione INNER JOIN giocatore ON giocNew = idGioc";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while($row = mysql_fetch_array($exe))
			$values[] = $row;
		if(isset($values))
			return $values;
		else
			return FALSE;
	}
	
	function getSelezioneByIdSquadra($idSquadra)
	{
		$q = "SELECT * 
				FROM selezione INNER JOIN giocatore ON giocNew = idGioc 
				WHERE idSquadra = '" . $idSquadra . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		return mysql_fetch_array($exe);
	}
	
	function unsetSelezioneByIdSquadra($idSquadra)
	{
		$q = "UPDATE selezione 
				SET giocOld = NULL,giocNew = NULL 
				WHERE idSquadra = '" . $idSquadra . "';";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function checkFree($idGioc,$idLega)
	{
		$q = "SELECT idSquadra 
				FROM selezione 
				WHERE giocNew = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$values = array();
		while($row = mysql_fetch_array($exe))
			$values[] = $row;
		if(!empty($values))
			return $values['idSquadra'];
		else
			return FALSE;
	}
	
	function updateGioc($giocNew,$giocOld,$idLega,$idSquadra)
	{
		mysql_query("START TRANSACTION");
		$q = "SELECT numSelezioni 
				FROM selezione 
				WHERE giocNew = '" . $giocNew . "' AND idLega = '" . $idLega . "' LOCK IN SHARE MODE";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		$values = array();
		while($row = mysql_fetch_array($exe))
			$values[] = $row;
		if(!empty($values))
		{
			$q = "UPDATE selezione 
					SET giocOld = '0', giocNew = NULL, numSelezioni = '" . ($values[0]['numSelezioni'] - 1) . "' 
					WHERE giocNew = '" . $giocNew . "' AND idLega = '" . $idLega . "'";
			mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		}
		$q = "SELECT numSelezioni 
				FROM selezione 
				WHERE giocNew IS NOT NULL AND idSquadra = '" . $idSquadra . "'  LOCK IN SHARE MODE";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		$values = array();
		while($row = mysql_fetch_array($exe))
			$values[] = $row;
		if(!empty($values))
		{
			$q = "UPDATE selezione 
					SET giocOld = '" . $giocOld . "', giocNew = '" . $giocNew . "',numSelezioni = '" . ($values[0]['numSelezioni']+1) . "' 
					WHERE idSquadra = '" . $idSquadra . "'";
			mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		}
		else
		{
			$q = "INSERT INTO selezione 
					VALUES ('" . $idLega . "','" . $idSquadra . "','" . $giocOld . "','" . $giocNew . "','1')";
			mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;	
		}
		if(isset($err))
		{
			mysql_query("ROLLBACK");
			die("Errore nella transazione: <br />" . $err);
		}
		else
			mysql_query("COMMIT");
	}
	
	function getNumberSelezioni($idUtente)
	{
		$q = "SELECT numSelezioni 
				FROM selezione 
				WHERE idSquadra = '" . $idUtente . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$val = NULL;
		while ($row = mysql_fetch_array($exe) )
			$val = $row['numSelezioni'];
		return $val;
	}
	
	function svuota()
	{
		$q = "TRUNCATE TABLE selezione";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
}
?>