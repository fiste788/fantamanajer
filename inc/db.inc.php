<?php

/*
db.inc.php:
A class to interact with database.

Biblion

Last modify: 31-02-2007 14:26
Modify Log:


To Do:
*/

class db
{
	var $link;
	/*
	DbConnect is  method that give a connection to a database.
	
	Files needed: noone
	
	*/
	
	function DbConnect()
	{
		if(DBTYPE == "mysql")
		{
			$this->link = mysql_connect(DBHOST,DBUSER,DBPASS);
			if(!$this->link)
			{	
				echo MYSQL_ERRNO()." ".MYSQL_ERROR();
			}
			mysql_select_db(DBNAME);
			mysql_query("SET NAMES utf8") or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: SET NAMES");
			mysql_query("SET CHARACTER SET utf8") or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: SET CHARSET");
		}
	}
	
	function DbClose()
	{
		mysql_close($this->link);
	}
	
	function DbOptimize()
	{
		$q = "SHOW TABLES";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$result = "";
		while($row = mysql_fetch_row($exe)) 
			$result .= $row[0].',';
		$q = "OPTIMIZE TABLE ";
		$q .= $result;
		$q = substr($q,0,-1);
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);;
	}
	
	function startTransaction()
	{
		mysql_query("START TRANSACTION");
	}
	
	function commit()
	{
		mysql_query("COMMIT");
	}
	
	function rollback()
	{
		mysql_query("ROLLBACK");
	}
}
?>
