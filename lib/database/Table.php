<?php

namespace Lib\Database;

abstract class Table implements \Lib\Form {

    const TABLE_NAME = "";

    /**
     *
     * @var array
     */
    private $originalValues = NULL;

    /**
     *
     * @var int
     */
    public $id;

    public function __construct() {
        $this->originalValues = get_object_vars($this);
        $this->id = is_null($this->id) ? NULL : $this->getId();
        $this->getFromPost();
    }

    public function __clone() {
        $this->id = NULL;
    }

    private function getFromPost($raw = TRUE) {
        $calledClass = explode("\\",get_called_class());
        $classe = strtolower(array_pop($calledClass));
        $postArray = \Lib\Request::getRequest()->getPostParams();
        if(isset($postArray[$classe]) && is_array($postArray[$classe])) {
            $this->fromArray($postArray[$classe], $raw);
        }
    }

    /**
     * Setter: id
     * @param Int $id
     * @return void
     */
    public function setId($id) {
        $this->id = (int) $id;
    }

    /**
     * Getter: id
     * @return Int
     */
    public function getId() {
        return (int) $this->id;
    }

    /**
     * @param int $id
     * @return Table|NULL
     */
    public static function getById($id) {
        if (!is_null($id) && $id != "") {
            $c = get_called_class();
            $q = "SELECT *
					FROM " . $c::TABLE_NAME . "
					WHERE id = :id";
            $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
            $exe->execute(array(':id' => $id));
            \FirePHP::getInstance()->log($q);
            $result = $exe->fetchObject($c);
            return ($result) ? $result : NULL;
        } else
            return NULL;
    }

    /**
     *
     * @param int[] $ids
     * @return Table[]|Table|NULL
     */
    public static function getByIds(array $ids) {
        //$keys = implode(array_filter($ids, 'strlen'), ',');
        $keys = array();
        foreach ($ids as $id) {
            if (strlen($id)) {
                $keys[] = ConnectionFactory::getFactory()->getConnection()->quote($id, \PDO::PARAM_INT);
            }
        }
        $param = implode(',', $keys);
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
     * @return Table[]
     */
    public static function getList() {
        $c = get_called_class();
        $q = "SELECT *
				FROM " . $c::TABLE_NAME;
        $exe = ConnectionFactory::getFactory()->getConnection()->query($q);
        \FirePHP::getInstance()->log($q);
        $values = array();
        while ($obj = $exe->fetchObject($c))
            $values[$obj->getId()] = $obj;
        return $values;
    }

    /**
     *
     * @param string $key
     * @param type $value
     * @return Table[]|Table|NULL
     */
    public static function getByField($key, $value) {
        $c = get_called_class();
        $q = "SELECT *
				FROM " . $c::TABLE_NAME . "
				WHERE $key = :value";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindParam(":value", $value);
        $exe->execute();
        \FirePHP::getInstance()->log($q);
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
     * @param array $parameters
     * @return boolean
     */
    public function save(array $parameters = array()) {
        try {
            $this->check($parameters);
        } catch(\Lib\FormException $e) {
            $this->getFromPost(TRUE);
            throw $e;
        }
        $vars = array_intersect_key(get_object_vars($this), get_class_vars(get_class($this)));
        unset($vars['originalValues']);
        if ($this->getId() != "" && !is_null($this->getId()) && $this->getById($this->getId()) != FALSE) {
            $values = array();
            foreach ($vars as $key => $value) {
                $currentVal = self::valueToSql($value);
                if($currentVal != self::valueToSql($this->originalValues[$key])) {
                    $values[] = $key . " = " . $currentVal;
                }
            }

            if(!empty($values)) {
                $q = "UPDATE " . $this::TABLE_NAME . "
                        SET " . implode($values, ", ") . " WHERE id = " . $this->getId();
                ConnectionFactory::getFactory()->getConnection()->exec($q);
                \FirePHP::getInstance()->log($q);
            }
            return $this->getId();
        } else {
            if ($this->getId() == "" || is_null($this->getId())) {
                unset($vars['id']);
            } else {
                $id = $this->getId();
            }
            $q = "INSERT INTO " . $this::TABLE_NAME . " (" . implode(array_keys($vars), ", ") . ")
					VALUES (" . implode(array_map("self::valueToSql", $vars), ", ") . ")";
            ConnectionFactory::getFactory()->getConnection()->exec($q);
            \FirePHP::getInstance()->log($q);
            $this->setId(isset($id) ? $id : ConnectionFactory::getFactory()->getConnection()->lastInsertId());
            return $this->getId();
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
        if (!is_null($this->getId())) {
            $q = "DELETE FROM " . $this::TABLE_NAME . "
					WHERE id = :id";
            $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
            $exe->bindValue(':id', $this->getId(), \PDO::PARAM_INT);
            $exe->execute();
            \FirePHP::getInstance()->log($q);
            return TRUE;
        } else
            return FALSE;
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
        } catch (FormException $e) {
            $this->fromArray($postArray, TRUE);
            throw $e;
        }
    }

    /**
     *
     * @param array $array
     * @param boolean $raw
     */
    private function fromArray(array $array, $raw = FALSE) {
        $vars = get_object_vars($this);
        foreach ($array as $key => $value) {
            if (array_key_exists($key, $vars) && !is_null($value) && strlen($value) > 0) {
                if (!$raw && method_exists($this, $methodName = 'set' . ucfirst($key))) {
                    $this->$methodName($value);
                } else {
                    
                    $this->$key = $value;
                }
            }
        }
    }

    public function getOriginalValues(string $field) {
        return isset($this->originalValues[$field]) ? $this->originalValues[$field] : NULL;
    }

    public function check(array $array = array()) {
        return TRUE;
    }

    //public abstract function __toString();
}

 
