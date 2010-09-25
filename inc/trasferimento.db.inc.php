<?php 
class Trasferimento extends DbTable
{
	var $idTrasf;
	var $idGiocOld;
	var $idGiocNew;
	var $idSquadra;
	var $idGiornata;
	var $obbligato;
	
	public static function getTrasferimentiByIdSquadra($idSquadra,$idGiornata = 0)
	{
		$q = "SELECT idGiocOld,t1.nome as nomeOld,t1.cognome as cognomeOld,idGiocNew,t2.nome as nomeNew,t2.cognome as cognomeNew, idGiornata, obbligato 
				FROM giocatore t1 INNER JOIN (trasferimento INNER JOIN giocatore t2 ON trasferimento.idGiocNew = t2.idGioc) ON t1.idGioc = trasferimento.idGiocOld 
				WHERE trasferimento.idSquadra = '" . $idSquadra . "' AND idGiornata > '" . $idGiornata . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$values = FALSE;
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values[] = $row;
		return $values;
	}
	
	function transfer($giocOld,$giocNew,$squadra,$idLega)
	{
		require_once(INCDIR . 'squadra.db.inc.php');
		require_once(INCDIR . 'formazione.db.inc.php');
		require_once(INCDIR . 'schieramento.db.inc.php');
		require_once(INCDIR . 'evento.db.inc.php');
		require_once(INCDIR . 'giocatore.db.inc.php');
		
		$squadraOld = Squadra::getSquadraByIdGioc($giocNew,$idLega);
		self::startTransaction();
		if($squadraOld == FALSE)
		{
			Squadra::setSquadraByIdGioc($giocNew,$idLega,$squadra);
			Squadra::unsetSquadraByIdGioc($giocOld,$idLega);
		}
		else
		{
			Squadra::updateGiocatoreSquadra($giocNew,$idLega,$squadra);
			Squadra::updateGiocatoreSquadra($giocOld,$idLega,$squadraOld);
		}
		$q = "INSERT INTO trasferimento (idGiocOld,idGiocNew,idSquadra,idGiornata,obbligato) 
				VALUES ('" . $giocOld . "' , '" . $giocNew . "' ,'" . $squadra . "','" . GIORNATA . "','" . Giocatore::checkOutLista($giocOld) . "')";
		mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		FirePHP::getInstance()->log($q);
		$q = "SELECT idTrasf 
						FROM trasferimento
						WHERE idGiocOld = '" . $giocOld . "' AND idGiocNew = '" . $giocNew . "' AND idGiornata = '" . GIORNATA . "' AND idSquadra = '" . $squadra ."'";
		$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		FirePHP::getInstance()->log($q);
		$idTrasferimento = mysql_fetch_object($exe,__CLASS__);
		Evento::addEvento('4',$squadra,$idLega,$idTrasferimento->idTrasf);
		$formazione = Formazione::getFormazioneBySquadraAndGiornata($squadra,GIORNATA);
		if($formazione != FALSE)
		{
			if(in_array($giocOld,$formazione->elenco))
				Schieramento::changeGioc($formazione->id,$giocOld,$giocNew);
			if(in_array($giocOld,$formazione->cap))
				Formazione::changeCap($formazione->id,$giocNew,array_search($giocOld,$formazione->cap));
		}
		if($squadraOld != FALSE)
		{
			$formazioneOld = Formazione::getFormazioneBySquadraAndGiornata($squadraOld,GIORNATA);
			if($formazioneOld != FALSE)
			{
				if(in_array($giocNew,$formazioneOld->elenco))
					Schieramento::changeGioc($formazioneOld->id,$giocNew,$giocOld);
				if(in_array($giocNew,$formazioneOld->cap))
					Formazione::changeCap($formazioneOld->id,$giocOld,array_search($giocNew,$formazioneOld->cap));
			}
			$q = "INSERT INTO trasferimento (idGiocOld,idGiocNew,idSquadra,idGiornata,obbligato) 
					VALUES ('" . $giocNew . "' , '" . $giocOld . "' ,'" . $squadraOld . "','" . GIORNATA . "','" . Giocatore::checkOutLista($giocNew) . "')";
			mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
			if(DEBUG)
				FirePHP::getInstance(true)->log($q);
			$q = "SELECT idTrasf 
						FROM trasferimento
						WHERE idGiocOld = '" . $giocNew . "' AND idGiocNew = '" . $giocOld . "' AND idGiornata = '" . GIORNATA . "' AND idSquadra = '" . $squadraOld ."'";
			$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
			if(DEBUG)
				FirePHP::getInstance(true)->log($q);
			$idTrasferimento = mysql_fetch_object($exe,__CLASS__);
			Evento::addEvento('4',$squadraOld,$idLega,$idTrasferimento->idTrasf);
		}
		if(isset($err))
		{
			self::rollback();
			self::sqlError("Errore nella transazione: <br />" . $err);
		}
		else
			self::commit();
	}
	
	public static function doTransfertBySelezione()
	{
		require_once(INCDIR.'selezione.db.inc.php');
		require_once(INCDIR.'squadra.db.inc.php');
		require_once(INCDIR.'evento.db.inc.php');
		require_once(INCDIR.'formazione.db.inc.php');
		require_once(INCDIR.'schieramento.db.inc.php');
		require_once(INCDIR.'giocatore.db.inc.php');
		
		$selezioni = Selezione::getSelezioni();
		if($selezioni != FALSE)
		{
			foreach($selezioni as $key => $val)
			{
				self::startTransaction();
				Squadra::unsetSquadraByIdGioc($val->giocOld,$val->idLega);
				Squadra::setSquadraByIdGioc($val->giocNew,$val->idLega,$val->idSquadra);
				$q = "INSERT INTO trasferimento (idGiocOld,idGiocNew,idSquadra,idGiornata,obbligato) 
				VALUES ('" . $val->giocOld . "' , '" . $val->giocNew . "' ,'" . $val->idSquadra . "','" . GIORNATA . "','" . Giocatore::checkOutLista($val->giocOld) . "')";
				mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
				if(DEBUG)
					FirePHP::getInstance(true)->log($q);
				$formazione = Formazione::getFormazioneBySquadraAndGiornata($val->idSquadra,GIORNATA);
				if($formazione != FALSE)
				{
					if(in_array($val->giocOld,$formazione->elenco))
						Schieramento::changeGioc($formazione->id,$val->giocOld,$val->giocNew);
					if(in_array($val->giocOld,get_object_vars($formazione->cap)))
						Formazione::changeCap($formazione->id,$val->giocNew,array_search($val->giocOld,$formazione->cap));
				}
				$q = "SELECT idTrasf 
						FROM trasferimento
						WHERE idGiocOld = '" . $val->giocOld . "' AND idGiocNew = '" . $val->giocNew . "' AND idGiornata = '" . GIORNATA . "' AND idSquadra = '" . $val->idSquadra . "'";
				$exe = mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
				if(DEBUG)
					FirePHP::getInstance(true)->log($q);
				$idTrasferimento = mysql_fetch_object($exe,__CLASS__);
				Evento::addEvento('4',$val->idSquadra,$val->idLega,$idTrasferimento->idTrasf);
				if(isset($err))
				{
					self::rollback();
					self::sqlError("Errore nella transazione: <br />" . $err);
				}
				else
					self::commit();
			}
			Selezione::svuota();
		}
		return TRUE;
	}
	
	public static function getTrasferimentoById($id)
	{
		$q = "SELECT * 
				FROM trasferimento 
				WHERE idTrasf = '" . $id . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			return $row;
	}
}
?>
