<?php 
class utente
{
	var $idSquadra;
	var $nome;
	var $cognome;
	var $nomeProp;
	var $mail;
	var $username;
	var $amministratore;
	
	function utente()
	{ 
		$this->idUtente = NULL;
		$this->nome = NULL;
		$this->nomeProp = NULL;
		$this->cognome = NULL;
		$this->mail = NULL;
		$this->username = NULL;
		$this->amministratore = NULL;
	}
	
	function getElencoSquadre()
	{		
		$q = "SELECT * 
				FROM utente 
				WHERE idLega = '" . $_SESSION['idLega'] . "'";
		 $exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		 while ($row = mysql_fetch_array($exe) )
		  	$values[$row['idUtente']] = $row;
		 return $values; 
	}
	
	function getElencoSquadreByLega($idLega)
	{		
		$q = "SELECT * 
				FROM utente
				WHERE idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		while ($row = mysql_fetch_array($exe) )
			$values[$row['idUtente']] = $row;
		return $values; 
	}
	
	function getSquadraById($idUtente)
	{		
		$q = "SELECT * 
				FROM utente 
				WHERE idUtente = '" . $idUtente . "'";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		$values = mysql_fetch_array($exe);
		return $values; 
	}
	
	function changeData($data,$id)
	{
		$q = "UPDATE utente SET ";
		if(!isset($data['amministratore']))
			$data['amministratore'] = '0';
		else
			$data['amministratore'] = '1';
		foreach($data as $key=>$val)
		{
			if($key == 'passwordnew')
			{
				$key = 'password';
				$q .= $key . " = '" . md5(trim($val)) . "',";
			} 
			else
			{
				if($key == 'usernamenew')
					$key = 'username';
				$q .= $key . " = '" . trim($val) . "',";
			} 
		}
		$q = substr($q,0,-1);
		$q .= " WHERE idUtente = '" . $id . "'";
		if(mysql_query($q))
			return 2;
		else
			return 3;
	}
	
	function getAllEmail()
	{
		$q = "SELECT mail,idUtente 
				FROM utente";
		$exe = mysql_query($q);
		while ($row = mysql_fetch_row($exe) )
			$values[$row[1]] = $row[0];
		return $values; 
	}
	
	function addSquadra($username,$name,$admin,$password,$email,$idLega)
	{
		$q = "INSERT INTO utente (nome,username,password,mail,amministratore,idLega) 
				VALUES ('" . $name . "','" . $username . "','" . md5($password) . "','" . $email . "','" . $admin . "','" . $idLega . "')";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		$q = "SELECT idUtente 
				FROM utente 
				WHERE nome = '" . $name . "' AND username = '" . $username . "' AND mail = '" . $email . "' AND amministratore = '" . $admin . "'";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		while ($row = mysql_fetch_row($exe) )
			$val = $row[0];
		return $val;
	}
	
	function deleteSquadra($idUtente)
	{
		$q = "DELETE 
				FROM utente 
				WHERE idUtente = '" . $idUtente . "'";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
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
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		$val = -1;
		while ($row = mysql_fetch_row($exe) )
			$val = $row[0];
		return $val;
	}
}
?>