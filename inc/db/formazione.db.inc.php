<?php
require_once(TABLEDIR . 'Formazione.table.db.inc.php');

class Formazione extends FormazioneTable
{
	public function save($parameters = NULL) {
		require_once(INCDBDIR . "schieramento.db.inc.php");

		$titolari = $parameters['titolari'];
		$panchinari = $parameters['panchinari'];
		$success = TRUE;
		$giocatoriIds = array();
		$modulo = array('P'=>0,'D'=>0,'C'=>0,'A'=>0);
		$giocatoriIds = array_merge($giocatoriIds,$titolari,$panchinari);
		$giocatori = Giocatore::getByIds($giocatoriIds);
		foreach($titolari as $key=>$titolare)
			$modulo[$giocatori[$titolare]->ruolo] += 1;
		$this->setModulo(implode($modulo,'-'));
		self::startTransaction();
		if(($idFormazione = parent::save()) != FALSE) {
			$schieramenti = Schieramento::getSchieramentoById($idFormazione);
			foreach($giocatoriIds as $posizione=>$idGiocatore) {
				$schieramento = isset($schieramenti[$posizione]) ? $schieramenti[$posizione] : new Schieramento();
				if(!is_null($idGiocatore) && !empty($idGiocatore)) {
					if($schieramento->idGiocatore != $idGiocatore) {
						$schieramento->setIdFormazione($idFormazione);
						$schieramento->setPosizione($posizione + 1);
						$schieramento->setIdGiocatore($idGiocatore);
						$schieramento->setConsiderato(0);
						$success = ($success and $schieramento->save());
					}
				} else
					$success = ($success and $schieramento->delete());
			}
			if($success)
				self::commit();
			else {
				self::rollback();
				return FALSE;
			}
		} else {
			self::rollback();
			return FALSE;
		}
		return TRUE;
	}

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
		FirePHP::getInstance()->log($_SESSION);
		return (mysql_num_rows($exe) == 1);
		
	}


	public function check($array,$message) {
		require_once(INCDBDIR . 'giocatore.db.inc.php');
		
		$post = (object) $array;
		$formazione = array();
		$capitano = array();
		foreach($array['titolari'] as $key=>$val) {
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
		foreach($array['panchinari'] as $key=>$val) {
			if(!empty($val)) {
				if(!in_array($val,$formazione))
					$formazione[] = $val;
				else {
					$message->error("Giocatore doppio");
					return FALSE;
				}
			}
		}
		$cap = array();
		$cap[] = $array['C'];
		$cap[] = $array['VC'];
		$cap[] = $array['VVC'];
		foreach($cap as $key=>$val) {
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
		}
		return TRUE;
	}
}
?>
