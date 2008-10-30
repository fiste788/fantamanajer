<?php 
class squadre
{
	function setSquadraGiocatoreByArray($idLega,$giocatori,$idUtente)
	{
		foreach($giocatori as $key => $val)
		{
			$q = "INSERT INTO squadre VALUES ('" . $idLega . "','" . $idUtente . "','" . $val . "');";
			$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		}
	}
	
	function unsetSquadraGiocatoreByIdSquadra($idUtente)
	{
		$q = "DELETE FROM squadre WHERE idUtente = '" . $idUtente . "';";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
	}
}
?>
