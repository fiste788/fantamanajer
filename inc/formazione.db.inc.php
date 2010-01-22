<?php
class formazione extends dbTable
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
		$q = "SELECT formazione.idFormazione,idUtente,idGiornata,idGioc,idPosizione,modulo,C,VC,VVC 
				FROM formazione INNER JOIN schieramento ON formazione.idFormazione = schieramento.idFormazione 
				WHERE formazione.idFormazione = '" . $id . "' ORDER BY idPosizione";
		$exe = mysql_query($q) or self::sqlError($q);
		$flag = FALSE;
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe))
		{
			$elenco[$row->idPosizione] = $row->idGioc;
			if(!$flag)
			{
				$idFormazione = $row->idFormazione;
				$idSquadra = $row->idUtente;
				$idGiornata = $row->idGiornata;
				$modulo = $row->modulo;
				$cap->C = $row->C;
				$cap->VC = $row->VC;
				$cap->VVC = $row->VVC;
				$flag = TRUE;
			}
		}
		if($flag)
		{
			$formazione->id = $idFormazione;
			$formazione->idSquadra = $idSquadra;
			$formazione->idGiornata = $idGiornata;
			$formazione->elenco = $elenco;
			$formazione->modulo = $modulo;
			$formazione->cap = $cap;
			return $formazione;
		}
		else
			return FALSE;
	}
	
	function caricaFormazione($formazione,$capitano,$giornata,$idSquadra,$modulo)
	{
		require_once(INCDIR . 'schieramento.db.inc.php');
		
		$schieramentoObj = new schieramento();
		
		self::startTransaction();
		$campi = "";
		$valori = "";
		foreach($capitano as $key => $val)
		{
			$campi .= "," . $key;
			if(empty($val))
				$valori .= ",NULL";
			else
				$valori .= ",'" . $val."'";
		}
		$q = "INSERT INTO formazione (idUtente,idGiornata,modulo" . $campi .") 
				VALUES (" . $idSquadra . ",'" . $giornata . "','" . $modulo . "'" . $valori . ")";
		mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		if(DEBUG)
			FB::log($q);
		$q = "SELECT idFormazione 
				FROM formazione 
				WHERE idUtente = '" . $idSquadra . "' AND idGiornata ='" . $giornata . "'";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe))
			$idFormazione = $row->idFormazione;
		foreach($formazione as $key => $player)
			$schieramentoObj->setGiocatore($idFormazione,$player,$key + 1);
		if(isset($err))
		{
			self::rollback();
			self::sqlError("Errore nella transazione: <br />" . $err);
		}
		else
			self::commit();
		return $idFormazione;
	}
	
	function updateFormazione($formazione,$capitano,$giornata,$idSquadra,$modulo)
	{
		require_once(INCDIR . 'schieramento.db.inc.php');
		
		$schieramentoObj = new schieramento();
		
		self::startTransaction();
		$str = "";
		foreach($capitano as $key => $val)
			if(empty($val))
				$str .= "," . $key . " = NULL";
			else
				$str .= "," . $key . " = '" . $val . "'";
		$q = "UPDATE formazione 
				SET modulo = '" . $modulo . "'" . $str . " 
				WHERE idUtente = '" . $idSquadra . "' AND idGiornata = '" . $giornata . "'";
		mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		if(DEBUG)
			FB::log($q);
		$q = "SELECT idFormazione 
				FROM formazione 
				WHERE idUtente = '" . $idSquadra . "' AND idGiornata ='" . $giornata . "'";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe))
			$idFormazione = $row->idFormazione;
		foreach($formazione as $key => $player)
			$schieramentoObj->setGiocatore($idFormazione,$player,$key + 1);
		if(isset($err))
		{
			self::rollback();
			self::sqlError("Errore nella transazione: <br />" . $err);
		}
		else
			self::commit();
		return $idFormazione;
	}
	
	function getFormazioneBySquadraAndGiornata($idUtente,$giornata)
	{
		$q = "SELECT formazione.idFormazione,idGioc,idPosizione,modulo,C,VC,VVC 
				FROM formazione INNER JOIN schieramento ON formazione.idFormazione = schieramento.idFormazione 
				WHERE formazione.idUtente = '" . $idUtente . "' AND formazione.idGiornata = '" . $giornata . "' 
				ORDER BY idPosizione";
		$exe = mysql_query($q) or self::sqlError($q);
		$flag = FALSE;
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe))
		{
			$elenco[$row->idPosizione] = $row->idGioc;
			$idFormazione = $row->idFormazione;
			$modulo = $row->modulo;
			$cap->C = $row->C;
			$cap->VC = $row->VC;
			$cap->VVC = $row->VVC;
			$flag = TRUE;
		}
		if($flag)
		{
			$formazione->id = $idFormazione;
			$formazione->elenco = $elenco;
			$formazione->modulo = $modulo;
			$formazione->cap = $cap;
			return $formazione;
		}
		else
			return FALSE;
	}
	
	function getFormazioneExistByGiornata($giornata,$idLega)
	{
		$q = "SELECT utente.idUtente,nome 
				FROM formazione INNER JOIN utente ON formazione.idUtente = utente.idUtente 
				WHERE idGiornata = '" . $giornata . "' AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe))
		{
			$val[$row->idUtente]->idUtente = $row->idUtente;
			$val[$row->idUtente]->nome = $row->nome;
		}
		if (!isset($val))
			return FALSE;
		else
			return $val;
	}
	
	function changeCap($idFormazione,$idGiocNew,$cap)
	{
		$q = "UPDATE formazione 
				SET " . $cap . " = '" . $idGiocNew . "'
				WHERE idFormazione = '" . $idFormazione . "'";
		if(DEBUG)
			FB::log($q);
		return mysql_query($q) or self::sqlError($q);
	}
}
?>
