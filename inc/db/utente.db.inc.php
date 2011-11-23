<?php 
require_once(TABLEDIR . 'Utente.table.db.inc.php');

class Utente extends UtenteTable
{
	public static function login($username, $password)
	{
		$q = "SELECT * FROM utente WHERE username LIKE '" . $username . "'
				AND password = '" . $password . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		if(mysql_num_rows($exe) == 1)
			return TRUE;
		else
			return FALSE;
	}
	
	public static function logout()
	{
		session_unset();
	}
	
	public static function getAllEmail()
	{
		$q = "SELECT mail,id,idLega,nomeProp,cognome
				FROM utente";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__) )
			$values[$row->idLega][$row->id] = array($row->mail=>$row->cognome . ' ' . $row->nomeProp);
		return $values; 
	}
	
	public static function getAllEmailAbilitate()
	{
		$q = "SELECT mail,id,idLega,nomeProp,cognome
				FROM utente
				WHERE abilitaMail <> 0";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__) )
			$values[$row->idLega][$row->id] = array($row->mail=>$row->cognome . ' ' . $row->nomeProp);
		return $values; 
	}
	
	public static function getAllEmailByLega($idLega)
	{
		$q = "SELECT mail,id,nomeProp,cognome
				FROM utente
				WHERE idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__) )
			$values[$row->id] = array($row->mail=>$row->cognome . ' ' . $row->nomeProp);
		return $values; 
	}
	
	public static function getAllEmailAbilitateByLega($idLega)
	{
		$q = "SELECT mail,id,nomeProp,cognome
				FROM utente
				WHERE abilitaMail <> 0 AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__) )
			$values[$row->id] = array($row->mail=>$row->cognome . ' ' . $row->nomeProp);
		return $values; 
	}

    public static function getSquadraByUsername($username,$idUtente)
	{
		$q = "SELECT * 
				FROM utente 
				WHERE username LIKE '" . $username . "' AND id <> '" . $idUtente . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		$val = FALSE;
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__) )
			$val = $row;
		return $val;
	}
	
	public static function getSquadraByNome($nome,$idUtente)
	{
		$q = "SELECT * 
				FROM utente 
				WHERE nome LIKE '" . $nome . "' AND id <> '" . $idUtente . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$val = FALSE;
		while ($row = mysql_fetch_object($exe,__CLASS__) )
			$val = $row;
		return $val;
	}
	
	public static function createRandomPassword() 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		$i = 0;
		$pass = '' ;
		while ($i <= 7) 
		{
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}
	
	public function check($array,$message) {
        require_once(INCDIR . 'mail.inc.php');
        
		$post = (object) $array;
		foreach($_POST as $key=>$val)
		{
			if($key != "passwordnew" && $key != "passwordnewrepeat" && empty($val)) {
				$message->error("Non hai compilato tutti i campi");
				return FALSE;
			}
		}
		if(!empty($post->passwordnew) && !empty($post->passwordnewrepeat))
		{
			if($post->passwordnew == $post->passwordnewrepeat)
			{
				if(strlen($post->passwordnew) < 6) {
					$message->error("La password deve essere lunga almeno 6 caratteri");
					return FALSE;
				}
			}
			else {
				$message->error("Le 2 password non corrispondono");
				return FALSE;
			}
		}
		if(!Mail::checkEmailAddress($post->mail)) {
			$message->error("Mail non corretta");
			return FALSE;
		}
		if(isset($post->nomeSquadra) && Utente::getSquadraByNome($post->nomeSquadra,$filterSquadra) != FALSE) {
			$message->error("Il nome della squadra è già presente");
			return FALSE;
		}
		return TRUE;
	}
}
?>
