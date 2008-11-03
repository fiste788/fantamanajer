<?php 
class squadre
{
	function setSquadraGiocatoreByArray($idLega,$giocatori,$idUtente)
	{
		foreach($giocatori as $key => $val)
		{
			$q = "INSERT INTO squadre VALUES ('" . $idLega . "','" . $idUtente . "','" . $val . "');";
			mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
			return true;
		}
	}
	
	function unsetSquadraGiocatoreByIdSquadra($idUtente)
	{
		$q = "DELETE FROM squadre WHERE idUtente = '" . $idUtente . "';";
		mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		return true;
	}
	
	function updateGiocatore($giocatoreNew,$giocatoreOld,$idUtente)
	{
		$q = "UPDATE squadre SET idGioc = '" . $giocatoreNew . "' 
				WHERE idGioc = '" . $giocatoreOld . "' AND idUtente = '" . $idUtente . "';";
		mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		return true;
	}
}
?>
