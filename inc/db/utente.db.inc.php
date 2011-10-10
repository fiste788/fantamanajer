<?php 
require_once(TABLEDIR . 'Utente.table.db.inc.php');

class Utente extends UtenteTable
{
	public static function login($username, $password)
	{
		$q = "SELECT username, password FROM utente WHERE username LIKE '" . $username . "';";
		$exe = mysql_query($q) or self::sqlError($q);
		$valore  = mysql_fetch_assoc($exe);
		if($valore['password'] == md5($password))
			return TRUE;
		else
			return FALSE;
	}
	
	public static function logout()
	{
		foreach($_SESSION as $key => $val)
			unset($_SESSION[$key]);
	}
	
	public static function getElencoSquadre()
	{		
		$q = "SELECT utente.*,giornateVinte 
				FROM utente LEFT JOIN giornatevinte on utente.id = giornatevinte.idUtente
				WHERE idLega = '" . $_SESSION['idLega'] . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__) )
			$values[$row->id] = $row;
		return $values; 
	}
	
	public static function getAllSquadre()
	{		
		$q = "SELECT * 
				FROM utente";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__) )
			$values[$row->id] = $row;
		return $values; 
	}
	
	public static function getElencoSquadreByLega($idLega)
	{		
		$q = "SELECT utente.*,giornateVinte 
				FROM utente LEFT JOIN giornatevinte on utente.id = giornatevinte.idUtente
				WHERE idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__))
			$values[$row->id] = $row;
		if(isset($values))
			return $values;
		else
			return FALSE; 
	}
	
	public static function getElencoSquadreByLegaOptions($idLega)
	{		
		$q = "SELECT id,nome
				FROM utente 
				WHERE idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__))
			$values[$row->id] = $row->nome;
		if(isset($values))
			return $values;
		else
			return FALSE; 
	}
	
	public static function getSquadraById($idUtente)
	{		
		$q = "SELECT * 
				FROM squadrastatistiche 
				WHERE idUtente = '" . $idUtente . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		return mysql_fetch_object($exe,__CLASS__); 
	}
	
	public static function changeData($nomeSquadra,$nome,$cognome,$email,$abilitaMail,$password,$amministratore,$idUtente)
	{
		$q = "UPDATE utente SET nome = '" . $nomeSquadra . "', 
				cognome = '" . $cognome . "', 
				nomeProp = '" . $nome . "', 
				mail = '" . $email . "', 
				abilitaMail = '" . $abilitaMail . "', 
				amministratore = '" . $amministratore . "'";
		if(!empty($password))
			$q .= ", password = '" . md5($password) . "'";
		$q .= " WHERE id = '" . $idUtente . "'";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
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
	
	public static function addSquadra($username,$nomeSquadra,$nome,$cognome,$admin,$password,$email,$idLega)
	{
		require_once(INCDIR . 'punteggio.db.inc.php');

		$q = "INSERT INTO utente (nome,username,nomeProp,cognome,password,mail,amministratore,idLega) 
				VALUES ('" . $nomeSquadra . "','" . $username . "','" . $nome . "','" . $cognome . "','" . md5($password) . "','" . $email . "','" . $admin . "','" . $idLega . "')";
		mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$q = "SELECT id
				FROM utente 
				WHERE nome = '" . $nomeSquadra . "' AND username = '" . $username . "' AND mail = '" . $email . "' AND amministratore = '" . $admin . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__) )
			$val = $row->id;
		Punteggio::setPunteggiToZero($val,$idLega);
		return $val;
	}
	
	public static function deleteSquadra($idUtente)
	{
		$q = "DELETE 
				FROM utente 
				WHERE id = '" . $idUtente . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		if(mysql_affected_rows() == 0)
			return FALSE;
		else
			return TRUE;
	}
	
	public static function getLegaByIdSquadra($idUtente)
	{
		$q = "SELECT idLega 
				FROM utente 
				WHERE id = '" . $idUtente . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		$val = -1;
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__) )
			$val = $row->idLega;
		return $val;
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
	
	public static function getUtentiByIdLega($idLega)
	{
		$q = "SELECT *
				FROM utente
				WHERE idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__) )
		  	$values[$row->getId()] = $row;
		return $values;
	}
}
?>
