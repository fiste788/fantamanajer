<?php

namespace Fantamanajer\Database;

include 'Table.inc.php';

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

class NestablePDO extends \PDO {

    // Database drivers that support SAVEPOINTs.
    protected static $savepointTransactions = array("pgsql", "mysql");
    // The current transaction level.
    protected $transLevel = 0;

    protected function nestable() {
        return in_array($this->getAttribute(PDO::ATTR_DRIVER_NAME), self::$savepointTransactions);
    }

    public function beginTransaction() {
        if ($this->transLevel == 0 || !$this->nestable())
            parent::beginTransaction();
        else
            $this->exec("SAVEPOINT LEVEL{$this->transLevel}");
        $this->transLevel++;
    }

    public function commit() {
        $this->transLevel--;

        if ($this->transLevel == 0 || !$this->nestable())
            parent::commit();
        else
            $this->exec("RELEASE SAVEPOINT LEVEL{$this->transLevel}");
    }

    public function rollBack() {
        $this->transLevel--;

        if ($this->transLevel == 0 || !$this->nestable())
            parent::rollBack();
        else
            $this->exec("ROLLBACK TO SAVEPOINT LEVEL{$this->transLevel}");
    }

}

?>
