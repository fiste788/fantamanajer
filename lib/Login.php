<?php

namespace Lib;

use Fantamanajer\Models\User;
use Lib\Database\ConnectionFactory;
use PDO;

class Login {

    var $sessionName;
    var $sessionTimeout;
    var $remember;
    
    /**
     *
     * @var User 
     */
    var $user;

    public function __construct() {
        $this->sessionName = SESSION_NAME;
        $this->sessionTimeout = SESSION_TIMEOUT;
        $this->start();
        $this->remember = TRUE;
        $this->user = NULL;
    }

    public function start() {
        @session_name($this->sessionName);
        if (!isset($_COOKIE[$this->sessionName])) {
            if (session_start() !== TRUE) {
                setcookie($this->sessionName, '', 1);
                die('sessionError');
            }
        }
        else
            @session_start();
        //session_regenerate_id(true);
        $this->setDefault();
    }

    private function setDefault() {
        if (!isset($_SESSION['logged'])) {
            $_SESSION['logged'] = FALSE;
            $_SESSION['roles'] = -1;
            $_SESSION['user_type'] = 'guest';
            $_SESSION['logged'] = FALSE;
            $_SESSION['user_id'] = FALSE;
            $_SESSION['league_view'] = 1;
        }
    }

    public function doLogin($email, $password) {
        $q = "SELECT * FROM users WHERE email LIKE :email
		AND password = :password";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(':email', $email, PDO::PARAM_STR);
        $exe->bindValue(':password', $password, PDO::PARAM_STR);
        $exe->execute();
        if ($exe->rowCount() == 1) {
            $this->user = $exe->fetchObject(User::class);
            if ($this->remember) {
                $key = self::createRandomKey();
                $this->user->setLoginKey($key);
                $q = "UPDATE users SET login_key = :login_key
			WHERE id = :id";
                $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
                $exe->bindValue(':login_key', $key, PDO::PARAM_STR);
                $exe->bindValue(':id', $this->user->id, PDO::PARAM_INT);
                $exe->execute();
            }
            $this->setData();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function renewLogin($email, $key) {
        $q = "SELECT * FROM users WHERE email LIKE :email
				AND login_key = :login_key";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(':email', $email, PDO::PARAM_STR);
        $exe->bindValue(':login_key', $key, PDO::PARAM_STR);
        $exe->execute();
        if ($exe->rowCount() == 1) {
            $this->user = $exe->fetchObject(User::class);
            $this->setData();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function logout() {
        $q = "UPDATE users SET login_key = NULL
		WHERE id = :id";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
        $exe->execute();
        session_unset();
        setcookie("auth_username", "", time() - 3600, "/", $_SERVER['HTTP_HOST']);
        setcookie("auth_key", "", time() - 3600, "/", $_SERVER['HTTP_HOST']);
        $this->setDefault();
    }

    private function setData() {
        $_SESSION['id'] = $this->user->id;
        $_SESSION['username'] = $this->user->username;
        $_SESSION['team'] = $this->user->getTeam();
        $_SESSION['logged'] = TRUE;
        $_SESSION['roles'] = $this->user->admin;
        switch ($this->user->admin) {
            case 1: $_SESSION['user_type'] = 'admin';
                break;
            case 2: $_SESSION['user_type'] = 'superadmin';
                break;
            default: $_SESSION['user_type'] = 'user';
        }
        $_SESSION['user_id'] = $this->user->id;
        $_SESSION['name'] = $this->user->name . " " . $this->user->surname;
        $_SESSION['email'] = $this->user->email;
        if ($this->remember) {
            $this->setCookie();
        }
    }

    private function setCookie() {
        setcookie("auth_username", $this->user->username, time() + (60 * 60 * 24 * $this->sessionTimeout), "/", $_SERVER['HTTP_HOST']);
        setcookie("auth_key", $this->user->login_key, time() + (60 * 60 * 24 * $this->sessionTimeout), "/", $_SERVER['HTTP_HOST']);
    }

    public static function createRandomKey() {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $i = 0;
        $key = '';
        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $key = $key . $tmp;
            $i++;
        }
        return md5($key);
    }

}

 
