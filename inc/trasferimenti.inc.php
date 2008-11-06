<?php 
class trasferimenti
{
	function getTrasferimentiByIdSquadra($idSquadra)
	{
		$q = "SELECT t1.nome as NomeOld,t1.cognome as CognomeOld,t2.nome as NomeNew,t2.cognome as CognomeNew 
				FROM giocatore t1 INNER JOIN (trasferimenti INNER JOIN giocatore t2 ON trasferimenti.idGiocNew = t2.idGioc) ON t1.idGioc = trasferimenti.idGiocOld 
				WHERE trasferimenti.idSquadra = '" . $idSquadra . "'";
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
		require_once(INCDIR.'squadre.inc.php');
		$squadreObj = new squadre();
		$squadraOld = $squadreObj->getSquadraByIdGioc($giocNew,$idLega);
		if($squadraOld == FALSE)
		{
			$q = "INSERT INTO squadre 
					VALUES ('" . $idLega . "','" . $squadra . "','". $giocNew . "')";
			$q2 = "DELETE 
					FROM squadre 
					WHERE idGioc = '". $giocOld . "' AND idLega = '" . $idLega . "'";
		}
		else
		{
			$q = "UPDATE squadre 
					SET idUtente = '" . $squadra . "' 
					WHERE idGioc = '". $giocNew . "' AND idLega = '" . $idLega . "'";
			$q2 = "UPDATE squadre 
					SET idUtente = '" . $squadraOld . "' 
					WHERE idGioc = '". $giocOld . "' AND idLega = '" . $idLega . "'";
		}
		$result = mysql_query($q);
		$result = $result + mysql_query($q2);
		$q = "INSERT INTO trasferimenti (idGiocOld,idGiocNew,idSquadra,idGiornata) 
				VALUES ('" . $giocOld . "' , '" . $giocNew . "' ,'" . $squadra . "','" . GIORNATA . "')";
		$result = $result + mysql_query($q);
		if($squadraOld != FALSE)
		{
			$q = "INSERT INTO trasferimenti (idGiocOld,idGiocNew,idSquadra,idGiornata) 
				VALUES ('" . $giocNew . "' , '" . $giocOld . "' ,'" . $squadraOld . "','" . GIORNATA . "')";
			$result = $result + mysql_query($q);
		}
		if($result)
			return TRUE;
		else
			return FALSE;
	}
	
	function getTrasferimentoById($id)
	{
		$q = "SELECT * 
				FROM trasferimenti 
				WHERE idTrasf = '" . $id . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO(). $q ." ".MYSQL_ERROR());
		while($row = mysql_fetch_array($exe))
			return $row;
	}
}
?>