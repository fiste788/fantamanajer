<?php 
class schieramento
{		
	function setConsiderazione($idForm,$idGioc)
	{
	    $q = "UPDATE schieramento 
				SET considerato=considerato+1 
				WHERE idFormazione='".$idForm."' AND idGioc='".$idGioc."'";
	    mysql_query($q) or die("Query non valida: ".$q. mysql_error());
	}
}
?>