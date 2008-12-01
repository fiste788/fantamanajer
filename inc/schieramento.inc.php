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
	    mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function changeGioc($idFormazione,$idGiocOld,$idGiocNew)
	{
		$q = "UPDATE schieramento 
				SET idGioc = '" . $idGiocNew . "' 
				WHERE idGioc = '" . $idGiocOld . "' AND idFormazione = '" . $idFormazione . "'";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
}
?>