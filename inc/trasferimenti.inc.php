<?php 
class trasferimenti
{
	function getTrasferimentiByIdSquadra($idSquadra)
	{
		$q = "SELECT t1.nome as NomeOld,t1.cognome as CognomeOld,t2.nome as NomeNew,t2.cognome as CognomeNew FROM giocatore t1 INNER JOIN (trasferimenti INNER JOIN giocatore t2 ON trasferimenti.idGiocNew = t2.idGioc) ON t1.idGioc = trasferimenti.idGiocOld WHERE trasferimenti.idSquadra = '" . $idSquadra . "';";
		$exe = mysql_query($q) or die(MYSQL_ERRNO(). $q ." ".MYSQL_ERROR());
		$values = array();
		while($row = mysql_fetch_array($exe))
			$values[] = $row;
		if(!empty($values))
			return $values;
		else
			return FALSE;
	}
	
	function transfer($giocOld,$giocNew,$squadra,$idLega)
	{
		$q = "INSERT INTO squadre VALUES ('" . $idLega . "','" . $squadra . "','". $giocNew . "');";
		$q2 = "DELETE FROM squadre WHERE idGioc = '". $giocOld . "';";
		$result = mysql_query($q);
		$result = $result + mysql_query($q2);
		$q = "INSERT INTO trasferimenti (idGiocOld,idGiocNew,idSquadra) VALUES ('" . $giocOld . "' , '" . $giocNew . "' ,'" . $squadra . "');";
		$result = $result + mysql_query($q);
		if($result)
			return TRUE;
		else
			return FALSE;
	}
	
	function getTrasferimentoById($id)
	{
		$q = "SELECT * FROM trasferimenti WHERE idTrasf = '" . $id . "';";
		$exe = mysql_query($q) or die(MYSQL_ERRNO(). $q ." ".MYSQL_ERROR());
		while($row = mysql_fetch_array($exe))
			return $row;
	}
}
?>
