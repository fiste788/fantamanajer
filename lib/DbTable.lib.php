<?php
//require_once(INCDIR . 'formException.inc.php');;
require_once(LIBDIR . 'Db.lib.php');

abstract class DbTable {

    const TABLE_NAME = "";

    /**
     * Enter description here ...
     * @param unknown_type $id
     * @return DbTable|NULL
     */
    public static function getById($id) {
        if (!is_null($id) && $id != "") {
            $c = get_called_class();
            $q = "SELECT *
					FROM " . $c::TABLE_NAME . "
					WHERE id = :id";
            $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
            $exe->execute(array(':id'=>$id));
            FirePHP::getInstance()->log($q);
            return $exe->fetchObject($c);
        } else
            return NULL;
    }

    /**
     *
     * @param type $ids
     * @return DbTable[]|DbTable|NULL
     */
    public static function getByIds($ids) {
        //$keys = implode(array_filter($ids, 'strlen'), ',');
        $keys = array();
        foreach ($ids as $id)
            if(strlen($id))
                $keys[] = ConnectionFactory::getFactory()->getConnection()->quote($id,PDO::PARAM_INT);
        $param = implode(',',$keys);
        if ($param != "") {
            $c = get_called_class();
            $q = "SELECT *
					FROM " . $c::TABLE_NAME . "
					WHERE id IN ($param)
                    ORDER BY FIELD(id,$param)";
            $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
            $exe->execute();
            $values = array();
            while ($obj = $exe->fetchObject($c))
                $values[$obj->getId()] = $obj;
            return $values;
        } else
            return NULL;
    }

    /**
     *
     * @return DbTable[]
     */
    public static function getList() {
        $c = get_called_class();
        $q = "SELECT *
				FROM " . $c::TABLE_NAME;
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject($c))
            $values[$obj->getId()] = $obj;
        return $values;
    }

    /**
     *
     * @param type $key
     * @param type $value
     * @return DbTable[]|DbTable|NULL
     */
    public static function getByField($key, $value) {
        $c = get_called_class();
        $q = "SELECT *
				FROM " . $c::TABLE_NAME . "
				WHERE $key = :value";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindParam(":value", $value);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        $count = $exe->rowCount();
        if ($count == 0)
            return NULL;
        elseif ($count == 1)
            return $exe->fetchObject($c);
        else {
            while ($obj = $exe->fetchObject($c))
                $values[$obj->getId()] = $obj;
            return $values;
        }
    }

    /**
     *
     * @param type $parameters
     * @return boolean
     */
    public function save($parameters = NULL) {
        try {
            $this->validate();
            $vars = array_intersect_key(get_object_vars($this), get_class_vars(get_class($this)));
            //unset($vars['id']);
            if ($this->getId() != "" && !is_null($this->getId()) && $this->getById($this->getId()) != FALSE) {
                $q = "UPDATE " . $this::TABLE_NAME . " SET ";
                foreach ($vars as $key => $value)
                    $values[] = $key . " = " . self::valueToSql($value);
                $q .= implode($values, ", ") . " WHERE id = " . $this->getId();
                ConnectionFactory::getFactory()->getConnection()->exec($q);
                FirePHP::getInstance()->log($q);
                return $this->getId();
            } else {
                if ($this->getId() == "" || is_null($this->getId()))
                    unset($vars['id']);
                $q = "INSERT INTO " . $this::TABLE_NAME . " (" . implode(array_keys($vars), ", ") . ")
					VALUES (" . implode(array_map("self::valueToSql", $vars), ", ") . ")";
                ConnectionFactory::getFactory()->getConnection()->exec($q);
                FirePHP::getInstance()->log($q);
                return ConnectionFactory::getFactory()->getConnection()->lastInsertId();
            }
        } catch (PDOException $e) {
            FirePHP::getInstance()->error($e->getMessage());
            throw new PDOException("Errore interno nel salvataggio dei dati");
        }
    }

    /**
     *
     * @param type $value
     * @return string
     */
    private static function valueToSql($value) {
        if (is_null($value))
            return "NULL";
        if (is_string($value)) {
            if ($value == '')
                return "NULL";
            else
                return ConnectionFactory::getFactory()->getConnection()->quote($value);
        } elseif (is_bool($value))
            return ($value) ? 1 : 0;
        elseif (is_numeric($value))
            return $value;
        elseif (is_object($value))
            if (is_a($value, "DateTime"))
                return ConnectionFactory::getFactory()->getConnection()->quote($value->format("Y-m-d H:i:s"));
            else
                return ConnectionFactory::getFactory()->getConnection()->quote($value->toString());
        else
            return ConnectionFactory::getFactory()->getConnection()->quote($value);
    }

    /**
     *
     * @return boolean
     */
    public function delete() {
        try {
            if (!is_null($this->getId())) {
                $q = "DELETE FROM " . $this::TABLE_NAME . "
					WHERE id = :id";
                $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
                $exe->bindValue(':id', $this->getId(), PDO::PARAM_INT);
                $exe->execute();
                FirePHP::getInstance()->log($q);
                return TRUE;
            } else
                return FALSE;
        } catch (PDOException $e) {
            FirePHP::getInstance()->error($e->getMessage());
            throw new PDOException("Errore interno nel salvataggio dei dati");
        }
    }

    /**
     * Richiama la funzione check specifica della classe e in caso ritorni false setta
     * dall'array con valori raw senza fare il cast per mantenere le variabili non corrette
     * @return boolean
     */
    public function validate() {
        $postArray = Request::getInstance()->getRawData('post');
        try {
            $this->check($postArray);
            $this->fromArray($postArray, FALSE);
            return TRUE;
        } catch(FormException $e) {
            $this->fromArray($postArray, TRUE);
            throw $e;
        }
    }

    /**
     *
     * @param type $array
     * @param type $raw
     */
    private function fromArray($array, $raw = FALSE) {
        $vars = get_object_vars($this);
        foreach ($array as $key => $value) {
            if (array_key_exists($key, $vars) && !is_null($value)) {
                if (!$raw && method_exists($this, $methodName = 'set' . ucfirst($vars[$key])))
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
