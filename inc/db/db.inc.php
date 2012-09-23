<?php

class Db {

    public $link;

    function __construct() {
        if (!isset($this->link))
            $this->connect();
    }

    private function connect() {
        if (DBTYPE == "mysql") {
            $this->link = mysql_connect(DBHOST, DBUSER, DBPASS);
            if (!$this->link)
                die(MYSQL_ERRNO() . " " . MYSQL_ERROR());
            if (!mysql_select_db(DBNAME, $this->link))
                die(MYSQL_ERRNO() . " " . MYSQL_ERROR());
            $now = new DateTime();
			$mins = $now->getOffset() / 60;
			$sgn = ($mins < 0 ? -1 : 1);
    		$mins = abs($mins);
    		$hrs = floor($mins / 60);
    		$mins -= $hrs * 60;
			$offset = sprintf('%+d:%02d', $hrs*$sgn, $mins);
            mysql_query("SET TIME_ZONE = '" . $offset . "'", $this->link) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: SET NAMES");
            mysql_query("SET NAMES utf8", $this->link) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: SET NAMES");
            mysql_query("SET CHARACTER SET utf8", $this->link) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: SET CHARSET");
        }
    }

    function __destruct() {
        if (isset($this->link))
            mysql_close($this->link);
    }

    public static function dbOptimize() {
        $q1 = "SHOW TABLES";
        $exe = mysql_query($q1) or self::sqlError($q1);
        $result = array();
        while ($row = mysql_fetch_row($exe))
            $result[] = $row[0];
        $q2 = "OPTIMIZE TABLE ";
        $q2 .= implode($result, ",");
        return mysql_query($q2) or self::sqlError($q2);
    }

}

?>
