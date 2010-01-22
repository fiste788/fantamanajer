<?php 
class utente extends dbTable
{
	var $idSquadra;
	var $nome;
	var $cognome;
	var $nomeProp;
	var $mail;
	var $abilitaMail;
	var $username;
	var $amministratore;
	var $idLega;
	
	function login($username, $password)
	{
		$q = "SELECT username, password FROM utente WHERE username LIKE '" . $username . "';";
		$exe = mysql_query($q) or self::sqlError($q);
		$valore  = mysql_fetch_assoc($exe);
		if($valore['password'] == md5($password))
			return TRUE;
		else
			return FALSE;
	}
	
	function logout()
	{
		foreach($_SESSION as $key => $val)
			unset($_SESSION[$key]);
	}
	
	function getElencoSquadre()
	{		
		$q = "SELECT utente.*,giornateVinte 
				FROM utente LEFT JOIN giornatevinte on utente.idUtente = giornatevinte.idUtente
				WHERE idLega = '" . $_SESSION['idLega'] . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe) )
			$values[$row->idUtente] = $row;
		return $values; 
	}
	
	function getAllSquadre()
	{		
		$q = "SELECT * 
				FROM utente";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe) )
			$values[$row->idUtente] = $row;
		return $values; 
	}
	
	function getElencoSquadreByLega($idLega)
	{		
		$q = "SELECT utente.*,giornateVinte 
				FROM utente LEFT JOIN giornatevinte on utente.idUtente = giornatevinte.idUtente
				WHERE idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe))
			$values[$row->idUtente] = $row;
		if(isset($values))
			return $values;
		else
			return FALSE; 
	}
	
	function getElencoSquadreByLegaOptions($idLega)
	{		
		$q = "SELECT idUtente,nome 
				FROM utente 
				WHERE idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe))
			$values[$row->idUtente] = $row->nome;
		if(isset($values))
			return $values;
		else
			return FALSE; 
	}
	
	function getSquadraById($idUtente)
	{		
		$q = "SELECT * 
				FROM squadrastatistiche 
				WHERE idUtente = '" . $idUtente . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		return mysql_fetch_object($exe); 
	}
	
	function changeData($nomeSquadra,$nome,$cognome,$email,$abilitaMail,$password,$amministratore,$idUtente)
	{
		$q = "UPDATE utente SET nome = '" . $nomeSquadra . "', 
				cognome = '" . $cognome . "', 
				nomeProp = '" . $nome . "', 
				mail = '" . $email . "', 
				abilitaMail = '" . $abilitaMail . "', 
				amministratore = '" . $amministratore . "'";
		if(!empty($password))
			$q .= ", password = '" . $password . "'";
		$q .= " WHERE idUtente = '" . $idUtente . "'";
		if(DEBUG)
			FB::log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	function getAllEmail()
	{
		$q = "SELECT mail,idUtente,idLega 
				FROM utente";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe) )
			$values[$row->idLega][$row->idUtente] = $row->mail;
		return $values; 
	}
	
	function getAllEmailAbilitate()
	{
		$q = "SELECT mail,idUtente,idLega 
				FROM utente
				WHERE abilitaMail <> 0";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe) )
			$values[$row->idLega][$row->idUtente] = $row->mail;
		return $values; 
	}
	
	function getAllEmailByLega($idLega)
	{
		$q = "SELECT mail,idUtente 
				FROM utente
				WHERE idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe) )
			$values[$row->idUtente] = $row->mail;
		return $values; 
	}
	
	function getAllEmailAbilitateByLega($idLega)
	{
		$q = "SELECT mail,idUtente
				FROM utente
				WHERE abilitaMail <> 0 AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe) )
			$values[$row->idUtente] = $row->mail;
		return $values; 
	}
	
	function addSquadra($username,$nomeSquadra,$nome,$cognome,$admin,$password,$email,$idLega)
	{
		require_once(INCDIR . 'punteggio.db.inc.php');
		$punteggioObj = new punteggio();
		$q = "INSERT INTO utente (nome,username,nomeProp,cognome,password,mail,amministratore,idLega) 
				VALUES ('" . $nomeSquadra . "','" . $username . "','" . $nome . "','" . $cognome . "','" . md5($password) . "','" . $email . "','" . $admin . "','" . $idLega . "')";
		mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		$q = "SELECT idUtente 
				FROM utente 
				WHERE nome = '" . $nomeSquadra . "' AND username = '" . $username . "' AND mail = '" . $email . "' AND amministratore = '" . $admin . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe) )
			$val = $row->idUtente;
		$punteggioObj->setPunteggiToZero($val,$idLega);
		return $val;
	}
	
	function deleteSquadra($idUtente)
	{
		$q = "DELETE 
				FROM utente 
				WHERE idUtente = '" . $idUtente . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		if(mysql_affected_rows() == 0)
			return FALSE;
		else
			return TRUE;
	}
	
	function getLegaByIdSquadra($idUtente)
	{
		$q = "SELECT idLega 
				FROM utente 
				WHERE idUtente = '" . $idUtente . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		$val = -1;
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe) )
			$val = $row->idLega;
		return $val;
	}
	
	function getSquadraByUsername($username,$idUtente)
	{
		$q = "SELECT * 
				FROM utente 
				WHERE username LIKE '" . $username . "' AND idUtente <> '" . $idUtente . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		$val = FALSE;
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe) )
			$val = $row;
		return $val;
	}
	
	function getSquadraByNome($nome,$idUtente)
	{
		$q = "SELECT * 
				FROM utente 
				WHERE nome LIKE '" . $nome . "' AND idUtente <> '" . $idUtente . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		$val = FALSE;
		while ($row = mysql_fetch_object($exe) )
			$val = $row;
		return $val;
	}
}
?>
