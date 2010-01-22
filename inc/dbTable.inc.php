<?php
class dbTable
{
	protected static function sqlError($q)
	{
		ob_end_flush();	
		FB::error(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
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
}
?>
