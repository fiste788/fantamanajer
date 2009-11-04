<?php 
class squadre
{
	var $idLega;
	var $idUtente;
	var $idGioc;
	
	function setSquadraGiocatoreByArray($idLega,$giocatori,$idUtente)
	{
		foreach($giocatori as $key => $val)
		{
			$q = "INSERT INTO squadre 
					VALUES ('" . $idLega . "','" . $idUtente . "','" . $val . "')";
			mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
			print $q.";";
		}
	}
	
	function unsetSquadraGiocatoreByIdSquadra($idUtente)
	{
		$q = "DELETE 
				FROM squadre 
				WHERE idUtente = '" . $idUtente . "'";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function updateGiocatore($giocatoreNew,$giocatoreOld,$idUtente)
	{
		$q = "UPDATE squadre 
				SET idGioc = '" . $giocatoreNew . "' 
				WHERE idGioc = '" . $giocatoreOld . "' AND idUtente = '" . $idUtente . "'";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function getSquadraByIdGioc($idGioc,$idLega)
	{
		$q = "SELECT idUtente
				FROM squadre 
				WHERE idGioc = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$values = FALSE;
		while($row = mysql_fetch_assoc($exe))
			$values = $row['idUtente'];
		return $values;
	}
	
	function setSquadraByIdGioc($idGioc,$idLega,$idUtente)
	{
		$q = "INSERT INTO squadre 
				VALUES ('" . $idLega . "','" . $idUtente . "','" . $idGioc . "')";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function unsetSquadraByIdGioc($idGioc,$idLega)
	{
		$q = "DELETE
				FROM squadre 
				WHERE idGioc = '" . $idGioc . "' AND idLega = '" . $idLega . "'";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
}
?>