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
}
?>
