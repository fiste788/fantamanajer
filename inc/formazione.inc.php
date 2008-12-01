<?php
class formazione
{
	var $idFormazione;
	var $idGiornata;
	var $idUtente;
	var $modulo;
	var $C;
	var $VC;
	var $VVC;
	
	function getFormazioneById($id)
	{
		$q = "SELECT formazioni.idFormazione,idUtente,idGiornata,idGioc,idPosizione,modulo,C,VC,VVC 
				FROM formazioni INNER JOIN schieramento ON formazioni.idFormazione = schieramento.idFormazione 
				WHERE formazioni.idFormazione = '" . $id . "' ORDER BY idPosizione";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$flag = FALSE;
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
				$flag = TRUE;
            }
        }
		if($flag)
		{
			$formazione['id'] = $idFormazione;
			$formazione['idSquadra'] = $idSquadra;
			$formazione['idGiornata'] = $idGiornata;
			$formazione['elenco'] = $elenco;
			$formazione['modulo'] = $modulo;
			$formazione['cap'] = $cap;
			return $formazione;
		}
		else
			return FALSE;
	}
	
	function caricaFormazione($formazione,$capitano,$giornata,$idSquadra,$modulo)
	{
		$campi = "";
		$valori = "";
		foreach($capitano as $key => $val)
		{
			$campi .= "," . $key;
			$valori .= ",'" . $val."'";
		}
		$q = "INSERT INTO formazioni (idUtente,idGiornata,modulo" . $campi .") 
				VALUES (" . $idSquadra . ",'" . $giornata . "','" . $modulo . "'" . $valori . ")";
		mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		$q = "SELECT idFormazione 
				FROM formazioni 
				WHERE idUtente = '" . $idSquadra . "' AND idGiornata ='" . $giornata . "'";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		while($row = mysql_fetch_array($exe))
			$id = $row['idFormazione'];
		foreach($formazione as $key => $player)
		{
			$pos = $key+1;
			$q = "INSERT INTO schieramento(idFormazione,idGioc,idPosizione) 
					VALUES ('" . $id . "','" . $player . "','" . $pos . "')";
			$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;          
		}
		if(isset($err))
		{
			mysql_query("ROLLBACK");
			die("Errore nella transazione: <br />" . $err);
		}
		else
			mysql_query("COMMIT");
		return $id;
	}
	
	function updateFormazione($formazione,$capitano,$giornata,$idSquadra,$modulo)
	{
		mysql_query("START TRANSACTION");
		$str = "";
		foreach($capitano as $key => $val)
			$str .= "," . $key . "='" . $val . "'";      
		$q = "UPDATE formazioni 
				SET Modulo = '$modulo'".$str." 
				WHERE idUtente = '" . $idSquadra . "' AND idGiornata = '" . $giornata . "'";
		mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		$q = "SELECT idFormazione 
				FROM formazioni 
				WHERE idUtente = '" . $idSquadra . "' AND idGiornata ='" . $giornata . "'";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		while($row = mysql_fetch_array($exe))
			$id = $row['idFormazione'];
		foreach($formazione as $key => $player)
		{
			$pos = $key + 1;
			$q = "UPDATE schieramento 
					SET idFormazione='" . $id . "',idGioc='" . $player . "',idPosizione='" . $pos . "' 
					WHERE idFormazione = '" . $id . "' AND idPosizione = '" . $pos . "'";
			$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		}
		if(isset($err))
		{
			mysql_query("ROLLBACK");
			die("Errore nella transazione: <br />" . $err);
		}
		else
			mysql_query("COMMIT");
		return $id;
	}
	
	function getFormazioneBySquadraAndGiornata($idUtente,$giornata)
	{
		$q = "SELECT formazioni.idFormazione,idGioc,idPosizione,modulo,C,VC,VVC 
				FROM formazioni INNER JOIN schieramento ON formazioni.idFormazione = schieramento.idFormazione 
				WHERE formazioni.idUtente = '" . $idUtente . "' AND formazioni.idGiornata = '" . $giornata . "' 
				ORDER BY idPosizione";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$flag = FALSE;
		while ($row = mysql_fetch_array($exe,MYSQL_ASSOC))
		{
			$elenco[$row['idPosizione']] = $row['idGioc'];
			$idFormazione = $row['idFormazione'];
			$modulo = $row['modulo'];
			$cap['C'] = $row['C'];
			$cap['VC'] = $row['VC'];
			$cap['VVC'] = $row['VVC'];
			$flag = TRUE;
		}
		if($flag)
		{
			$formazione['id'] = $idFormazione;
			$formazione['elenco'] = $elenco;
			$formazione['modulo'] = $modulo;
			$formazione['cap'] = $cap;
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
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe))
		{
			$val[$row['idUtente']]['idUtente'] = $row['idUtente'];
			$val[$row['idUtente']]['nome'] = $row['nome'];
		}
		if (!isset($val))
			return FALSE;
		else
			return $val;
	}
	
	function changeCap($idFormazione,$idGiocNew,$cap)
	{
		$q = "UPDATE formazioni 
				SET " . $cap . " = '" . $idGiocNew . "'
				WHERE idFormazione = '" . $idFormazione . "'";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
}
?>