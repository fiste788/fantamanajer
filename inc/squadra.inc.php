<?php 
class squadra
{
	var $idSquadra;
	var $nome;
	var $cognome;
	var $nomeProp;
	var $mail;
	var $username;
	
	function squadra()
	{ 
		$this->IdSquadra = NULL;
		$this->nome = NULL;
		$this->nomeProp = NULL;
		$this->cognome = NULL;
		$this->mail = NULL;
		$this->username = NULL;
	}
	
	function getElencoSquadre()
	{		
		$q="SELECT * FROM squadra";
		 $exe=mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		 while ($row = mysql_fetch_row($exe) )
		 {
		  	$values[] = $row;
		 }
		 return $values; 
	}
	
	function getSquadraById($idSquadra)
	{		
		$q="SELECT * FROM squadra WHERE IdSquadra = '" . $idSquadra . "'";
		$exe=mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		$values = mysql_fetch_array($exe);
		return $values; 
	}
	
	function changeData($data,$id)
	{
		$q = "UPDATE squadra SET ";
		foreach($data as $key=>$val)
		{
			if(!empty($val))
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
		}
		$q = substr($q,0,-1);
		$q .= " WHERE IdSquadra = '" . $id . "';";
		if(mysql_query($q))
			return 2;
		else
			return 3;
	}
	
	function getAllEmail()
	{
		$q = "SELECT mail,IdSquadra FROM squadra";
		$exe = mysql_query($q);
		while ($row = mysql_fetch_row($exe) )
		 {
		  	$values[$row[1]] = $row[0];
		 }
		 return $values; 
	}
	
	function getNumberTransfert($squadra)
	{
		$q = "SELECT numTrasferimenti FROM squadra WHERE idSquadra = '".$squadra."';";
		$exe=mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		while ($row = mysql_fetch_row($exe) )
			$val = $row[0];
		return $val;
	}
	
	function increaseNumberTransfert($squadra)
	{
		$q = "SELECT numTrasferimenti FROM squadra WHERE idSquadra = '".$squadra."';";
		$exe=mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		while ($row = mysql_fetch_row($exe) )
			$val = $row[0];
		$val++;
		$q2 = "UPDATE squadra SET numTrasferimenti = '". $val ."' WHERE idSquadra = '".$squadra."';";
		$exe=mysql_query($q2) or die("Query non valida: ".$q2 . mysql_error());
	}
	
	function decreaseNumberTransfert($squadra)
	{
		$q = "SELECT numTrasferimenti FROM squadra WHERE idSquadra = '".$squadra."';";
		$exe=mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		while ($row = mysql_fetch_row($exe) )
			$val = $row[0];
		$val--;
		$q2 = "UPDATE squadra SET numTrasferimenti = '". $val  ."' WHERE idSquadra = '".$squadra."';";
		$exe=mysql_query($q2) or die("Query non valida: ".$q2 . mysql_error());
	}
}
?>
