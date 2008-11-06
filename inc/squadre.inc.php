<?php 
class squadre
{
	function setSquadraGiocatoreByArray($idLega,$giocatori,$idUtente)
	{
		foreach($giocatori as $key => $val)
		{
			$q = "INSERT INTO squadre 
					VALUES ('" . $idLega . "','" . $idUtente . "','" . $val . "');";
			return mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		}
	}
	
	function unsetSquadraGiocatoreByIdSquadra($idUtente)
	{
		$q = "DELETE 
				FROM squadre 
				WHERE idUtente = '" . $idUtente . "';";
		return mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
	}
	
	function updateGiocatore($giocatoreNew,$giocatoreOld,$idUtente)
	{
		$q = "UPDATE squadre 
				SET idGioc = '" . $giocatoreNew . "' 
				WHERE idGioc = '" . $giocatoreOld . "' AND idUtente = '" . $idUtente . "';";
		return mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
	}
	
	function getSquadraByIdGioc($idGioc,$idLega)
	{
		$q = "SELECT idUtente
				FROM squadre 
				WHERE idGioc = '" . $idGioc . "' AND idLega = '" . $idLega . "';";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		$values = FALSE;
		while($row = mysql_fetch_array($exe))
			$values = $row['idUtente'];
		return $values;
	}
}
?>