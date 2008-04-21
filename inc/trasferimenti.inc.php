<?php 
class trasferimenti
{
	function getTrasferimentiByIdSquadra($idSquadra)
	{
		$q = "SELECT t1.Nome as NomeOld,t1.Cognome as CognomeOld,t2.Nome as NomeNew,t2.Cognome as CognomeNew FROM giocatore t1 INNER JOIN (trasferimenti INNER JOIN giocatore t2 ON trasferimenti.IdGiocNew = t2.IdGioc) ON t1.idGioc = trasferimenti.IdGiocOld WHERE trasferimenti.IdSquadra = '" . $idSquadra . "';";
		$exe = mysql_query($q);
		while($row = mysql_fetch_array($exe))
		{
			foreach($row as $key=>$val)
				$row[$key] = ucwords(mb_strtolower(utf8_encode($val),"UTF-8"));
			$values[] = $row;
		}
		if(!empty($values))
			return $values;
		else
			return FALSE;
	}
	
	function transfer($giocOld,$giocNew,$squadra)
	{
		$q = "UPDATE giocatore SET IdSquadra = '" . $squadra . "' WHERE IdGioc = '". $giocNew . "';";
		$q2 = "UPDATE giocatore SET IdSquadra = '0' WHERE IdGioc = '". $giocOld . "';";
		$result = mysql_query($q);
		$result = $result + mysql_query($q2);
		$q = "INSERT INTO trasferimenti (IdGiocOld,IdGiocNew,IdSquadra) VALUES ('" . $giocOld . "' , '" . $giocNew . "' ,'" . $squadra . "');";
		$result = $result + mysql_query($q);
		if($result)
			return TRUE;
		else
			return FALSE;
	}
}
?>