<?php
class formazione
{	
	function getFormazioneById($id)
	{
		$q = "SELECT formazioni.idFormazione,idUtente,idGiornata,idGioc,idPosizione,modulo,C,VC,VVC 
				FROM formazioni INNER JOIN schieramento ON formazioni.idFormazione=schieramento.idFormazione 
				WHERE formazioni.idFormazione = '" . $id . "' ORDER BY idPosizione";
		$exe = mysql_query($q);
		$flag=0;
		while ($row = mysql_fetch_array($exe,MYSQL_ASSOC))
		{
			$elenco[$row['idPosizione']] = $row['idGioc'];
			if(!$flag)
			{
				$idFormazione = $row['idFormazione'];
				$idSquadra = $row['idUtente'];
				$idGiornata = $row['idGiornata'];
				$modulo = $row['modulo'];
				$cap['C'] = $row['C'];
				$cap['VC'] = $row['VC'];
				$cap['VVC'] = $row['VVC'];
				$flag = 1;
            }
        }
		if($flag)
		{
			$formazione['Id'] = $idFormazione;
			$formazione['IdSquadra'] = $idSquadra;
			$formazione['IdGiornata'] = $idGiornata;
			$formazione['Elenco'] = $elenco;
			$formazione['Modulo'] = $modulo;
			$formazione['Cap'] = $cap;
			return $formazione;
		}
		else
			return FALSE;
	}
	
	function caricaFormazione($formazione,$capitano,$giornata,$idSquadra)
	{
		//echo "<pre>".print_r($formazione,1)."</pre>";
		//echo "<pre>".print_r($capitano,1)."</pre>";
		$modulo = $_SESSION['modulo'];
		$campi = "";
		$valori = "";
		foreach($capitano as $key=>$val)
		{
			$campi .= ",".$key;
			$valori .= ",'".$val."'";
		}
		$q = "INSERT INTO formazioni (idUtente,idGiornata,modulo".$campi.") 
				VALUES (" . $idSquadra . ",'" . $giornata . "','" . $modulo . "'" . $valori . ")";
		mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		$q = "SELECT idFormazione 
				FROM formazioni 
				WHERE idUtente = '" . $idSquadra . "' AND idGiornata ='" . $giornata . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO(). $q ." ".MYSQL_ERROR());
		while($row = mysql_fetch_array($exe))
			$id = $row[0];
		foreach($formazione as $key=>$player)
		{
			$pos = $key+1;
			$q = "INSERT INTO schieramento(idFormazione,idGioc,idPosizione) 
					VALUES ('" . $id . "','" . $player . "','" . $pos . "')";
			$exe = mysql_query($q) or die(MYSQL_ERRNO(). $q ." ".MYSQL_ERROR());            
		}
		return $id;
	}
	
	function updateFormazione($formazione,$capitano,$giornata,$idSquadra)
	{
		$modulo = $_SESSION['modulo'];
		$str = "";
		foreach($capitano as $key=>$val)
			$str .= "," . $key . "='" . $val . "'";      
		$q = "UPDATE formazioni 
				SET Modulo = '$modulo'".$str." 
				WHERE idUtente = '" . $idSquadra . "' AND idGiornata = '" . $giornata . "'";
		mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		$q = "SELECT idFormazione 
				FROM formazioni 
				WHERE idUtente = '" . $idSquadra . "' AND idGiornata ='" . $giornata . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO(). $q ." ".MYSQL_ERROR());
		while($row = mysql_fetch_array($exe))
			$id = $row[0];
		foreach($formazione as $key=>$player)
		{
			$pos = $key+1;
			$q = "UPDATE schieramento 
					SET idFormazione='" . $id . "',idGioc='" . $player . "',idPosizione='" . $pos . "' 
					WHERE idFormazione = '" . $id . "' AND idPosizione = '" . $pos . "'";
			$exe = mysql_query($q) or die(MYSQL_ERRNO(). $q ." ".MYSQL_ERROR());
		}
		return $id;
	}
	
	function getFormazioneBySquadraAndGiornata($idUtente,$giornata)
	{
		$q = "SELECT formazioni.idFormazione,idGioc,idPosizione,modulo,C,VC,VVC 
				FROM formazioni INNER JOIN schieramento ON formazioni.idFormazione = schieramento.idFormazione 
				WHERE formazioni.idUtente = '" . $idUtente . "' AND formazioni.idGiornata = '" . $giornata . "' 
				ORDER BY idPosizione";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		$flag = 0;
		while ($row = mysql_fetch_array($exe,MYSQL_ASSOC))
		{
			$elenco[$row['idPosizione']] = $row['idGioc'];
			$idFormazione = $row['idFormazione'];
			$modulo = $row['modulo'];
			$cap['C'] = $row['C'];
			$cap['VC'] = $row['VC'];
			$cap['VVC'] = $row['VVC'];
			$flag = 1;
		}
		if($flag == 1)
		{
			$formazione['Id'] = $idFormazione;
			$formazione['Elenco'] = $elenco;
			$formazione['Modulo'] = $modulo;
			$formazione['Cap'] = $cap;
			return $formazione;
		}
		else
			return FALSE;
	}
	
	function getFormazioneExistByGiornata($giornata)
	{
		$q = "SELECT utente.idUtente,nome 
				FROM formazioni INNER JOIN utente ON formazioni.idUtente = utente.idUtente 
				WHERE idGiornata = '" . $giornata . "'";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		while ($row = mysql_fetch_row($exe))
		{
			$val[$row['0']][] = $row['0'];
			$val[$row['0']][] = $row['1'];
		}
		if (!isset($val))
			return FALSE;
		else
			return $val;
	}
}
?>
