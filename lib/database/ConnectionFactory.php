<?php

namespace Lib\Database;

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
     * @return \MyPDO
     */
    public function getConnection() {
        if (!$this->db) {
            $now = new \DateTime();
            $mins = $now->getOffset() / 60;
            $sgn = ($mins < 0 ? -1 : 1);
            $mins = abs($mins);
            $hrs = floor($mins / 60);
            $mins -= $hrs * 60;
            $offset = sprintf('%+d:%02d', $hrs * $sgn, $mins);
            $this->db = new NestablePDO('mysql:host=' . DBHOST . ';dbname=' . DBNAME . ';charset=utf8;', DBUSER, DBPASS);
            $this->db->setAttribute(NestablePDO::ATTR_ERRMODE, NestablePDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(NestablePDO::ATTR_EMULATE_PREPARES, FALSE);
            $this->db->setAttribute(NestablePDO::ATTR_PERSISTENT, TRUE);
            $this->db->exec("SET CHARACTER SET utf8");
            $this->db->exec("SET NAMES utf8");
            $this->db->exec("SET TIME_ZONE = '" . $offset . "'");
        }
        return $this->db;
    }

}

 
