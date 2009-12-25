<?php 
class schieramento
{
	var $idFormazione;
	var $idPosizione;
	var $idGioc;
	var $considerazione;	//0 = non ha giocato, 1 = giocato, 2 = capitano
	
	function setConsiderazione($idFormazione,$idGioc,$val)
	{
		$q = "UPDATE schieramento 
				SET considerato = '" . $val . "'
				WHERE idFormazione = '" . $idFormazione . "' AND idGioc = '" . $idGioc . "'";
		if(DEBUG)
			echo $q . "<br />";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function changeGioc($idFormazione,$idGiocOld,$idGiocNew)
	{
		$q = "UPDATE schieramento 
				SET idGioc = '" . $idGiocNew . "' 
				WHERE idGioc = '" . $idGiocOld . "' AND idFormazione = '" . $idFormazione . "'";
		if(DEBUG)
			echo $q . "<br />";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function setGiocatore($idFormazione,$idGioc,$pos)
	{
		$q = "SELECT idGioc
					FROM schieramento
					WHERE idFormazione = '" . $idFormazione . "' AND idPosizione = '" . $pos . "'";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		if(DEBUG)
			echo $q . "<br />";
		$row = mysql_fetch_assoc($exe);
		if(!empty($idGioc))
		{
			if(empty($row['idGioc']))
				$q = "INSERT INTO schieramento(idFormazione,idGioc,idPosizione) 
						VALUES ('" . $idFormazione . "','" . $idGioc . "','" . $pos . "')";
			elseif($idGioc != $row['idGioc'])
				$q = "UPDATE schieramento 
						SET idFormazione='" . $idFormazione . "',idGioc='" . $idGioc . "',idPosizione='" . $pos . "' 
						WHERE idFormazione = '" . $idFormazione . "' AND idPosizione = '" . $pos . "'";
			else
				return TRUE;
		}
		elseif(!empty($row['idGioc']))
			$q = "DELETE from schieramento 
						WHERE idFormazione = '" . $idFormazione . "' AND idPosizione = '" . $pos . "'";
		else
			return TRUE;
		if(DEBUG)
			echo $q . "<br />";
		return mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
	}
}
?>
