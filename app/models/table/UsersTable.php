<?php

namespace Fantamanajer\Models\Table;

use Fantamanajer\Models\User;
use Fantamanajer\Models\Team;
use Lib\Database\Table;

abstract class UsersTable extends Table {

    const TABLE_NAME = "users";

    /**
     *
     * @var string
     */
    public $name;
    
    /**
     *
     * @var string
     */
    public $surname;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var boolean
     */
    public $active_email;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $password;
    
    /**
     *
     * @var string
     */
    public $login_key;

    /**
     *
     * @var int
     */
    public $admin;

    public function __construct() {
        parent::__construct();
        $this->name = is_null($this->name) ? NULL : $this->getName();
        $this->surname = is_null($this->surname) ? NULL : $this->getSurname();
        $this->email = is_null($this->email) ? NULL : $this->getEmail();
        $this->active_email = is_null($this->active_email) ? NULL : $this->isActiveEmail();
        $this->username = is_null($this->username) ? NULL : $this->getUsername();
        $this->password = is_null($this->password) ? NULL : $this->getPassword();
        $this->login_key = is_null($this->login_key) ? NULL : $this->getLoginKey();
        $this->admin = is_null($this->admin) ? NULL : $this->getAdmin();
    }

    /**
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * 
     * @return string
     */
    public function getSurname() {
        return $this->surname;
    }

    /**
     * 
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * 
     * @return boolean
     */
    public function isActiveEmail() {
        return (boolean) $this->active_email;
    }

    /**
     * 
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * 
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * 
     * @return string
     */
    public function getLoginKey() {
        return $this->login_key;
    }

    /**
     * 
     * @return int
     */
    public function getAdmin() {
        return (int) $this->admin;
    }

    /**
     * 
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * 
     * @param string $surname
     */
    public function setSurname($surname) {
        $this->surname = $surname;
    }

    /**
     * 
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * 
     * @param boolean $active_email
     */
    public function setActiveEmail($active_email) {
        $this->active_email = (boolean) $active_email;
    }

    /**
     * 
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * 
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * 
     * @param string$login_key
     */
    public function setLoginKey($login_key) {
        $this->login_key = $login_key;
    }

    /**
     * 
     * @param int $admin
     */
    public function setAdmin($admin) {
        $this->admin = (int) $admin;
    }
    
    public function getTeam() {
        return Team::getByField('user_id',$this->getId());
    }
        
    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getUsername();
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return User[]|User|null
     */
    public static function getByField($key, $value) {
        return parent::getByField($key, $value);
    }

    /**
     *
     * @param int $id
     * @return User
     */
    public static function getById($id) {
        return parent::getById($id);
    }

    /**
     *
     * @param int[] $ids
     * @return User[]|null
     */
    public static function getByIds(array $ids) {
        return parent::getByIds($ids);
    }

    /**
     *
     * @return User[]
     */
    public static function getList() {
        return parent::getList();
    }

}

 
