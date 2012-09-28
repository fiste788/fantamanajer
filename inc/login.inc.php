<?php 
require_once(INCDBDIR . 'utente.db.inc.php');

class Login {
	var $sessionName;
	var $sessionTimeout;
	var $remember;
	var $utente;

	public function __construct() {
		$this->sessionName = SESSION_NAME;
		$this->sessionTimeout = SESSION_TIMEOUT;
		$this->start();
		$this->remember = TRUE;
		$this->utente = NULL;
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
		session_regenerate_id (true);
		$this->setDefault();
	}

	private function setDefault() {
		if (!isset($_SESSION['logged'])) {
			$_SESSION['userid'] = 1000;
			$_SESSION['roles'] = -1;
			$_SESSION['usertype'] = 'guest';
			$_SESSION['logged'] = FALSE;
			$_SESSION['idUtente'] = FALSE;
			$_SESSION['legaView'] = 1;
		}
	}

	public function doLogin($username, $password)
	{
		$q = "SELECT * FROM utente WHERE username LIKE '" . $username . "'
				AND password = '" . $password . "'";
		$exe = mysql_query($q);
		FirePHP::getInstance()->log($q);
		if(mysql_num_rows($exe) == 1) {
			$this->utente = mysql_fetch_object($exe,"Utente");
			if($this->remember) {
				$key = self::createRandomKey();
				$this->utente->setChiave($key);
				$q = "UPDATE utente SET chiave = '" . $key . "'
						WHERE id = '" . $this->utente->id . "'";
				FirePHP::getInstance()->log($q);
				mysql_query($q);
			}
			$this->setData();
			return TRUE;
		}
		else
			return FALSE;
	}

	public function renewLogin($username, $key)
	{
		$q = "SELECT * FROM utente WHERE username LIKE '" . $username . "'
				AND chiave = '" . $key . "'";
		$exe = mysql_query($q);
		FirePHP::getInstance()->log($q);
		if(mysql_num_rows($exe) == 1) {
			$this->utente = mysql_fetch_object($exe,"Utente");
			$this->setData();
			return TRUE;
		}
		else
			return FALSE;
	}
	
	public function logout()
	{
		$q = "UPDATE utente SET chiave = NULL
				WHERE id = '" . $_SESSION['id'] . "'";
		mysql_query($q);
		session_unset();
		setcookie("auth_username", "", time() - 3600, "/", $_SERVER['HTTP_HOST']);
		setcookie("auth_key", "", time() - 3600, "/", $_SERVER['HTTP_HOST']);
		$this->setDefault();
	}
	
	private function setData() {
		$_SESSION['id'] = $this->utente->id;
		$_SESSION['username'] = $this->utente->username;
		$_SESSION['logged'] = TRUE;
		$_SESSION['roles'] = $this->utente->amministratore;
		switch($this->utente->amministratore) {
			case 1: $_SESSION['usertype'] = 'admin';break;
			case 2: $_SESSION['usertype'] = 'superadmin';break;
			default: $_SESSION['usertype'] = 'user';
		}
		$_SESSION['idLega'] = $this->utente->idLega;
		$_SESSION['legaView'] = $this->utente->idLega;
		$_SESSION['idUtente'] = $this->utente->id;
		$_SESSION['nomeSquadra'] = $this->utente->nomeSquadra;
		$_SESSION['nomeProprietario'] = $this->utente->nome . " " . $this->utente->cognome;
		$_SESSION['email'] = $this->utente->email;
		if($this->remember)
			$this->setCookie();
	}

	private function setCookie() {
		setcookie("auth_username", $this->utente->username, time() + (60 * 60 * 24 * $this->sessionTimeout), "/", $_SERVER['HTTP_HOST']);
		setcookie("auth_key", $this->utente->chiave, time() + (60 * 60 * 24 * $this->sessionTimeout), "/", $_SERVER['HTTP_HOST']);
	}

	public static function createRandomKey()
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		$i = 0;
		$key = '' ;
		while ($i <= 7)
		{
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$key = $key . $tmp;
			$i++;
		}
		return md5($key);
	}
}
?>
