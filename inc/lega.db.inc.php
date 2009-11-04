<?php 
class lega
{
	var $idLega;
	var $nomeLega;
	var $capitano;
	var $numTrasferimenti;
	var $numSelezioni;
	var $minFormazione;
	
	function getLeghe()
	{
		$q = "SELECT * 
				FROM lega";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_assoc($exe) )
		  	$values[] = $row;
		return $values; 
	}
	
	function getLegaById($idLega)
	{
		$q = "SELECT * 
				FROM lega
				WHERE idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		return mysql_fetch_assoc($exe);
	}
	
	function updateImpostazioni($impostazioni)
	{
		$q = "UPDATE lega SET ";
		foreach ($impostazioni as $key=>$val)
			$q .= $key . " = '" . $val . "',";
		$q = substr($q,0,-1);
		$q .= " WHERE idLega = '" . $_SESSION['idLega'] . "'";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function getDefaultValue()
	{
		$q = "SHOW COLUMNS
				FROM lega";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while($row = mysql_fetch_assoc($exe))
			$result[$row['Field']] = $row['Default'];
		return $result;
	}
}
?>
