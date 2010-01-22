<?php 
class schieramento extends dbTable
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
			FB::log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	function changeGioc($idFormazione,$idGiocOld,$idGiocNew)
	{
		$q = "UPDATE schieramento 
				SET idGioc = '" . $idGiocNew . "' 
				WHERE idGioc = '" . $idGiocOld . "' AND idFormazione = '" . $idFormazione . "'";
		if(DEBUG)
			FB::log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	function setGiocatore($idFormazione,$idGioc,$pos)
	{
		$q = "SELECT idGioc
					FROM schieramento
					WHERE idFormazione = '" . $idFormazione . "' AND idPosizione = '" . $pos . "'";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		if(DEBUG)
			FB::log($q);
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
			FB::log($q);
		return mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
	}
}
?>
