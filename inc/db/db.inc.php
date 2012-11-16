<?php

class ConnectionFactory {

    /**
     *
     * @var ConnectionFactory
     */
    private static $factory;

    /**
     *
     * @var PDO
     */
    private $db;

    /**
     *
     * @return ConnectionFactory
     */
    public static function getFactory() {
        if (!self::$factory)
            self::$factory = new ConnectionFactory();
        return self::$factory;
    }

    /**
     *
     * @return \PDO
     */
    public function getConnection() {
        if (!$this->db) {
            $now = new DateTime();
            $mins = $now->getOffset() / 60;
            $sgn = ($mins < 0 ? -1 : 1);
            $mins = abs($mins);
            $hrs = floor($mins / 60);
            $mins -= $hrs * 60;
            $offset = sprintf('%+d:%02d', $hrs*$sgn, $mins);
            $this->db = new PDO('mysql:host=' . DBHOST . ';dbname=' . DBNAME . ';charset=UTF-8', DBUSER, DBPASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
            $this->db->setAttribute(PDO::ATTR_PERSISTENT, TRUE);
            $this->db->exec("SET CHARACTER SET utf8");
            $this->db->exec("SET NAMES utf8");
            $this->db->exec("SET TIME_ZONE = '" . $offset . "'");
        }
        return $this->db;
    }
}

class myPDO extends PDO {

    public function query($statement) {
        FirePHP::getInstance()->log($statement);
        return parent::query($statement);
    }

    public function exec($statement) {
        FirePHP::getInstance()->log($statement);
        return parent::exec($statement);
    }
}

?>
