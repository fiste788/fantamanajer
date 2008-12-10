<?php 
class leghe
{
	var $idLega;
	var $nomeLega;
	
	function getLeghe()
	{
		$q = "SELECT * 
				FROM leghe";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe) )
		  	$values[] = $row;
		return $values; 
	}
	
	function getLegaById($idLega)
	{
		$q = "SELECT * 
				FROM leghe
				WHERE idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		return mysql_fetch_array($exe);	  	 
	}
}
?>