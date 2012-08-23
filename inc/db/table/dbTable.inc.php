<?php
abstract class DbTable
{
	const TABLE_NAME = "";
	
	public static function startTransaction()
	{
		mysql_query("START TRANSACTION");
	}

	public static function commit()
	{
		mysql_query("COMMIT");
	}

	public static function rollback()
	{
		mysql_query("ROLLBACK");
	}

	protected static function sqlError($q)
	{
		ob_end_flush();
		FirePHP::getInstance()->error(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		self::rollback();
		die();
	}
	
	public static function getById($id)
	{
	    if(!is_null($id) && $id != "") {
		    $c = get_called_class();
			$q = "SELECT *
					FROM " . $c::TABLE_NAME . "
					WHERE id = '" . $id . "'";
			$exe = mysql_query($q) or self::sqlError($q);
			FirePHP::getInstance()->log($q);
			return mysql_fetch_object($exe,$c);
		} else
		    return NULL;
	}

	public static function getByIds($ids)
	{
		$keys = implode(array_filter($ids,'strlen'),',');
	    if($keys != "") {
		    $c = get_called_class();
			$q = "SELECT *
					FROM " . $c::TABLE_NAME . "
					WHERE id IN (" . $keys . ")";
			$exe = mysql_query($q) or self::sqlError($q);
			FirePHP::getInstance()->log($q);
			$values = array();
			while ($row = mysql_fetch_object($exe,$c) )
		  		$values[$row->getId()] = $row;
			return $values;
		} else
		    return NULL;
	}

	public static function getList()
	{
	    $c = get_called_class();
		$q = "SELECT *
				FROM " . $c::TABLE_NAME;
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$values = array();
		while ($row = mysql_fetch_object($exe,$c) )
		  	$values[$row->getId()] = $row;
		return $values;
	}
	
	public static function getByField($key,$value)
	{
	    $c = get_called_class();
		$q = "SELECT *
				FROM " . $c::TABLE_NAME . "
				WHERE " . $key . " = " . $value;
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
	
	public function save() {
	    $vars = array_intersect_key(get_object_vars($this),get_class_vars(get_class($this)));
	    unset($vars['id']);
		if($this->getId() == "" || is_null($this->getId())) {
			/*foreach($vars as $key=>$value)
				$values[] = self::valueToSql($vars[$key]);*/
			$q = "INSERT INTO " . $this::TABLE_NAME . " (" . implode(array_keys($vars),", ") . ")
					VALUES (" . implode(array_map("self::valueToSql",$vars),", ") . ")";
			FirePHP::getInstance()->log($q);
			mysql_query($q) or self::sqlError($q);
			return mysql_insert_id();
		} else {
            $q = "UPDATE " . $this::TABLE_NAME . " SET ";
			foreach($vars as $key=>$value)
				$values[] = $key . " = " . self::valueToSql($vars[$key]);
			$q .= implode($values,", ") . " WHERE id = " . $this->getId();
			FirePHP::getInstance()->log($q);
			mysql_query($q) or self::sqlError($q);
			return $this->getId();
		}
	}
	
	private static function valueToSql($value) {
	    if(is_null($value))
	        return "NULL";
	    $type = gettype($value);
    	if(is_string($value)) {
			if($value == '')
				return "NULL";
			else
				return "'" . mysql_real_escape_string($value) . "'";
		} elseif(is_bool($value))
			return ($value) ? 1 : 0;
		elseif(is_numeric($value))
			return $value;
		elseif(is_object($value))
		    if(is_a($value,"DateTime"))
		        return "'" . $value->format("Y-m-d H:i:s") . "'";
		    else
		    	return $value->toString();
		else
			return "'" . $value . "'";
	}
	
	public function delete() {
	    if(!is_null($this->getId())) {
	        $q = "DELETE FROM " . $this::TABLE_NAME . "
					WHERE id = '" . $this->getId() . "'";
			$exe = mysql_query($q) or self::sqlError($q);
			FirePHP::getInstance()->log($q);
			return mysql_query($q);
		} else
		    return FALSE;
	}

/*
	 * Richiama la funzione check specifica della classe e in caso ritorni false setta
	 * dall'array con valori raw senza fare il cast per mantenere le variabili non corrette
	 */
	public function validate() {
		$return = $this->check($postArray = $GLOBALS['request']->getRawData('post'),$GLOBALS['message']);
  		$this->fromArray($postArray,$return);
        return $return;
	}

	public function fromArray($array,$raw = FALSE) {
		$vars = get_object_vars($this);
		foreach($array as $key=>$value) {
			if(array_key_exists($key,$vars) && !is_null($value)) {
			    if(!$raw && method_exists($this,$methodName = 'set' . ucfirst($vars[$key])))
				    $this->$methodName($value);
                else
                    $this->$key = $value;
            }
		}
	}

	//public abstract function check($array,$message);
	
	//public abstract function __toString();
}
?>
