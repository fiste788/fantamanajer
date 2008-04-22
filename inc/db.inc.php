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
			mysql_query("SET NAMES utf8;") or die();
			mysql_query("SET CHARACTER SET utf8;")or die();
		}
	}
	
	function DbClose()
	{
		mysql_close($this->link);
	}
}
?>
