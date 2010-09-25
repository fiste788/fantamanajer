<?php 
class Lega extends DbTable
{
	var $idLega;
	var $nomeLega;
	var $capitano;
	var $numTrasferimenti;
	var $numSelezioni;
	var $minFormazione;
	var $premi;
	var $punteggioFormazioneDimenticata;
	var $jolly;
	
	public static function getLeghe()
	{
		$q = "SELECT * 
				FROM lega";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__) )
		  	$values[] = $row;
		return $values; 
	}
	
	public static function getLegaById($idLega)
	{
		$q = "SELECT * 
				FROM lega
				WHERE idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		return mysql_fetch_object($exe,__CLASS__);
	}
	
	public static function updateImpostazioni($impostazioni)
	{
		$q = "UPDATE lega SET ";
		foreach ($impostazioni as $key=>$val)
			$q .= $key . " = '" . $val . "',";
		$q = substr($q,0,-1);
		$q .= " WHERE idLega = '" . $_SESSION['idLega'] . "'";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function getDefaultValue()
	{
		$q = "SHOW COLUMNS
				FROM lega";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$result[$row->Field] = $row->Default;
		return $result;
	}
}
?>
