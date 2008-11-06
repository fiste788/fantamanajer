<?php 
class leghe
{
	function getLeghe()
	{
		$q = "SELECT * 
				FROM leghe";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR() . $q);
		while ($row = mysql_fetch_array($exe) )
		  	$values[] = $row;
		 return $values; 
	}
}
?>