<?php
class Db
{
	public $link;
	
	function __construct()
	{
		if(!isset($this->link))
			$this->connect();
	}
	
	private function connect()
	{
		if(DBTYPE == "mysql")
		{
			$this->link = mysql_connect(DBHOST,DBUSER,DBPASS);
			if(!$this->link)
				die(MYSQL_ERRNO()." ".MYSQL_ERROR());
			if(!mysql_select_db(DBNAME,$this->link))
				die(MYSQL_ERRNO()." ".MYSQL_ERROR());
			mysql_query("SET NAMES utf8",$this->link) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: SET NAMES");
			mysql_query("SET CHARACTER SET utf8",$this->link) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: SET CHARSET");
		}
	}
	
	function __destruct()
	{
		if(isset($this->link))
			mysql_close($this->link);
	}
	
	public static function dbOptimize()
	{
		$q = "SHOW TABLES";
		$exe = mysql_query($q) or self::sqlError($q);
		$result = "";
		while($row = mysql_fetch_row($exe)) 
			$result .= $row[0] . ',';
		$q = "OPTIMIZE TABLE ";
		$q .= $result;
		$q = substr($q,0,-1);
		return mysql_query($q) or self::sqlError($q);;
	}
}
?>
