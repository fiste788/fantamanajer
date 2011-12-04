<?php
require_once(TABLEDIR . 'Formazione.table.db.inc.php');

class Formazione extends FormazioneTable
{
	public static function getFormazioneById($id)
	{
		$q = "SELECT formazione.idFormazione,idUtente,idGiornata,idGioc,idPosizione,modulo,C,VC,VVC 
				FROM formazione INNER JOIN schieramento ON formazione.idFormazione = schieramento.idFormazione 
				WHERE formazione.idFormazione = '" . $id . "' ORDER BY idPosizione";
		$exe = mysql_query($q) or self::sqlError($q);
		$flag = FALSE;
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__))
		{
			$elenco[$row->idPosizione] = $row->idGioc;
			if(!$flag)
			{
				$idFormazione = $row->idFormazione;
				$idUtente = $row->idUtente;
				$idGiornata = $row->idGiornata;
				$modulo = $row->modulo;
				$cap->C = $row->C;
				$cap->VC = $row->VC;
				$cap->VVC = $row->VVC;
				$jolly = $row->jolly;
				$flag = TRUE;
			}
		}
		if($flag)
		{
			$formazione->id = $idFormazione;
			$formazione->idUtente = $idUtente;
			$formazione->idGiornata = $idGiornata;
			$formazione->elenco = $elenco;
			$formazione->modulo = $modulo;
			$formazione->cap = $cap;
			$formazione->jolly = $jolly;
			return $formazione;
		}
		else
			return FALSE;
	}
	
	public static function caricaFormazione($formazione,$capitano,$giornata,$idUtente,$modulo,$jolly)
	{
		require_once(INCDIR . 'schieramento.db.inc.php');
		
		self::startTransaction();
		$campi = "";
		$valori = "";
		if($capitano != NULL) {
			foreach($capitano as $key => $val)
			{
				$campi .= "," . $key;
				if(empty($val))
					$valori .= ",NULL";
				else
					$valori .= ",'" . $val."'";
			}
		}
		if($jolly)
			$jolly = "'1'";
		else
			$jolly = "NULL";
		$q = "INSERT INTO formazione (idUtente,idGiornata,modulo" . $campi .",jolly) 
				VALUES (" . $idUtente . ",'" . $giornata . "','" . $modulo . "'" . $valori . "," . $jolly . ")";
		mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		FirePHP::getInstance()->log($q);
		$q = "SELECT idFormazione 
				FROM formazione 
				WHERE idUtente = '" . $idUtente . "' AND idGiornata ='" . $giornata . "'";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		FirePHP::getInstance()->log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$idFormazione = $row->idFormazione;
		foreach($formazione as $key => $player)
			Schieramento::setGiocatore($idFormazione,$player,$key + 1);
		for ($i = $key + 2; $i <= 18 ; $i++)
			Schieramento::unsetGiocatore($idFormazione,$i);
		if(isset($err))
		{
			self::rollback();
			self::sqlError("Errore nella transazione: <br />" . $err);
		}
		else
			self::commit();
		return $idFormazione;
	}
	
	public static function updateFormazione($formazione,$capitano,$giornata,$idUtente,$modulo,$jolly)
	{
		require_once(INCDIR . 'schieramento.db.inc.php');
		
		self::startTransaction();
		$str = "";
		foreach($capitano as $key => $val)
			if(empty($val))
				$str .= "," . $key . " = NULL";
			else
				$str .= "," . $key . " = '" . $val . "'";
		if($jolly)
			$jolly = "'1'";
		else
			$jolly = "NULL";
		$q = "UPDATE formazione 
				SET modulo = '" . $modulo . "'" . $str . " , jolly = " . $jolly . " 
				WHERE idUtente = '" . $idUtente . "' AND idGiornata = '" . $giornata . "'";
		mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		FirePHP::getInstance()->log($q);
		$q = "SELECT idFormazione 
				FROM formazione 
				WHERE idUtente = '" . $idUtente . "' AND idGiornata ='" . $giornata . "'";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		FirePHP::getInstance()->log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$idFormazione = $row->idFormazione;
		foreach($formazione as $key => $player)
			Schieramento::setGiocatore($idFormazione,$player,$key + 1);
		for ($i = $key + 2; $i <= 18 ; $i++)
			Schieramento::unsetGiocatore($idFormazione,$i);
		if(isset($err))
		{
			self::rollback();
			self::sqlError("Errore nella transazione: <br />" . $err);
		}
		else
			self::commit();
		return $idFormazione;
	}
	
	public static function getFormazioneBySquadraAndGiornata($idUtente,$giornata)
	{
        require_once(INCDBDIR . "schieramento.db.inc.php");

		$q = "SELECT *
				FROM formazione 
				WHERE formazione.idUtente = '" . $idUtente . "' AND formazione.idGiornata = '" . $giornata . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		$formazione = mysql_fetch_object($exe,__CLASS__);
        if(!empty($formazione))
            $formazione->giocatori = Schieramento::getSchieramentoById($formazione->getId());
    	return $formazione;
	}
	
	public static function getFormazioneByGiornataAndLega($giornata,$idLega)
	{
		$q = "SELECT formazione.*
				FROM formazione INNER JOIN utente ON formazione.idUtente = utente.id
				WHERE idGiornata = '" . $giornata . "' AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$values = array();
		while ($row = mysql_fetch_object($exe,__CLASS__)) {
			$values[] = $row->idUtente;
		}
		return $values;
	}
	
	public static function changeCap($idFormazione,$idGiocNew,$cap)
	{
		$q = "UPDATE formazione 
				SET " . $cap . " = '" . $idGiocNew . "'
				WHERE idFormazione = '" . $idFormazione . "'";
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function usedJolly($idUtente) 
	{
		$q = "SELECT jolly 
				FROM formazione
				WHERE idGiornata " . ((GIORNATA <= 19) ? "<=" : ">") . " 19 AND idUtente = '" . $idUtente . "' AND jolly = '1'";
		FirePHP::getInstance()->log($q);
		$exe = mysql_query($q) or self::sqlError($q);
		return (mysql_num_rows($exe) == 1);
	}

	/*public function save() {
		if(!is_null($this->id))
			$schieramenti = Schieramento::getByField('idFormazione',$this->id);
			foreach($schieramenti as $key=>$schieramento) {
				$schieramento->id
			}
	}*/

	public function check($array,$message) {
		require_once(INCDBDIR . 'giocatore.db.inc.php');
		$GLOBALS['firePHP']->log($_POST);
		$post = (object) $array;
		$formazione = array();
		$capitano = array();
		foreach($array['gioc'] as $key=>$val) {
			if(empty($val)) {
				$message->error("Non hai compilato correttamente tutti i campi");
				return FALSE;
			}
   			if(!in_array($val,$formazione))
				$formazione[] = $val;
			else {
				$message->error("Giocatore doppio");
				return FALSE;
			}
		}
		foreach($array['panch'] as $key=>$val) {
			if(!empty($val)) {
				if(!in_array($val,$formazione))
					$formazione[] = $val;
				else {
					$message->error("Giocatore doppio");
					return FALSE;
				}
			}
		}/*
		foreach($post['cap'] as $key=>$val) {
			if(!empty($val)) {
				$giocatore = Giocatore::getById($val);
				if($giocatore->ruolo == 'P' || $giocatore->ruolo == 'D') {
					if(!in_array($val,$capitano))
						$capitano[$key] = $val;
					else {
						$message->error("Capitano doppio");
						return FALSE;
					}
				} else {
					$message->error("Capitano non difensore o portiere");
					return FALSE;
				}
			}
		}*/
		return TRUE;
	}
}
?>
