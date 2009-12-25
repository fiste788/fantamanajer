<?php
class db
{
	var $link;
	
	function __construct()
	{
		if(!isset($this->link))
		{
			$this->connect();
		}
	}
	
	private function connect()
	{
		if(DBTYPE == "mysql")
		{
			$this->link = mysql_connect(DBHOST,DBUSER,DBPASS);
			if(!$this->link)
				die(MYSQL_ERRNO()." ".MYSQL_ERROR());
			if(!mysql_select_db(DBNAME))
				die(MYSQL_ERRNO()." ".MYSQL_ERROR());
			mysql_query("SET NAMES utf8") or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: SET NAMES");
			mysql_query("SET CHARACTER SET utf8") or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: SET CHARSET");
		}
	}
	
	function __destruct()
	{
		if(isset($this->link))
			mysql_close($this->link);
	}
	
	function DbOptimize()
	{
		$q = "SHOW TABLES";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$result = "";
		while($row = mysql_fetch_row($exe)) 
			$result .= $row[0] . ',';
		$q = "OPTIMIZE TABLE ";
		$q .= $result;
		$q = substr($q,0,-1);
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);;
	}
	
	public function startTransaction()
	{
		mysql_query("START TRANSACTION");
	}
	
	public function commit()
	{
		mysql_query("COMMIT");
	}
	
	public function rollback()
	{
		mysql_query("ROLLBACK");
	}
}
?>
