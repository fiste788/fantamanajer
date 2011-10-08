<?php
class DbTable
{
	const TABLE_NAME = "";
	
	protected static function sqlError($q)
	{
		ob_end_flush();	
		FirePHP::getInstance()->error(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		die();
	}
	
	protected static function startTransaction()
	{
		mysql_query("START TRANSACTION");
	}
	
	protected static function commit()
	{
		mysql_query("COMMIT");
	}
	
	protected static function rollback()
	{
		mysql_query("ROLLBACK");
	}
	
	public static function getById($id)
	{
	    $c = get_called_class();
		$q = "SELECT *
				FROM " . $c::TABLE_NAME . "
				WHERE id = '" . $id . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		return mysql_fetch_object($exe,$c);
	}

	public static function getList()
	{
	    $c = get_called_class();
		$q = "SELECT *
				FROM " . $c::TABLE_NAME;
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,$c) )
		  	$values[$row->getId()] = $row;
		return $values;
	}
	
	public static function getByField($key,$value)
	{
	    $c = get_called_class();
		$q = "SELECT *
				FROM " . $c::TABLE_NAME . "
				WHERE " . $key . ' = ' . $value;
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$count = mysql_num_rows($exe);
		if($count == 0)
		    return NULL;
		elseif ($count == 1)
			return mysql_fetch_object($exe,$c);
		else {
			while ($row = mysql_fetch_object($exe,$c) )
		  		$values[$row->getId()] = $row;
     		return $values;
  		}
	}
}
?>
