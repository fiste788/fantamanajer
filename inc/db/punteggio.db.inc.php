<?php
class Punteggio extends DbTable
{
	var $punteggio;
	var $penalità;
	var $idGiornata;
	var $idUtente;
	var $idLega;
	
	public static function checkPunteggi($giornata)
	{
		$q = "SELECT * 
				FROM punteggio 
				WHERE idGiornata = '" . $giornata . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		if(mysql_num_rows($exe) > 0)
			return FALSE;
		else
			return TRUE;
	}

	public static function getPunteggi($idUtente,$idGiornata)
	{
		$q = "SELECT punteggio 
				FROM punteggio 
				WHERE idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$punteggio = NULL;
		if(mysql_num_rows($exe) > 0)
		{
			$punteggio = 0;
			while ($row = mysql_fetch_assoc($exe))
				$punteggio += $row['punteggio'];
		}
		return $punteggio;
	}
	
	public static function getPosClassificaGiornata($idLega)
	{
		$q = "SELECT *
				FROM punteggio 
				WHERE idLega = '" . $idLega . "' AND punteggio >= 0 ORDER BY idGiornata,punteggio DESC";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__))
			$values[$row->idGiornata][] = $row;
		if(isset($values)) {
			foreach($values as $giornata=>$pos)
				foreach($pos as $key=>$val)
					$appo[$giornata][$val->idUtente] = $key + 1;
			return $appo;
		}else
		    return null;
	}
	
	public static function getGiornateVinte($idUtente)
	{
		$q = "SELECT giornateVinte 
				FROM giornatevinte 
				WHERE idUtente = '" . $idUtente . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__))
			$values = $row->giornateVinte;
		return $values;
	}
	
	public static function getClassificaByGiornata($idLega,$idGiornata)
	{
		$q = "SELECT utente.id, nome, punteggio.*, SUM(punteggio.punteggio) AS punteggioTot, AVG(punteggio.punteggio) AS punteggioMed, MAX(punteggio.punteggio) AS punteggioMax, (SELECT MIN(punteggio.punteggio) FROM punteggio WHERE punteggio >= 0 AND idUtente = utente.id) AS punteggioMin, COALESCE(giornateVinte,0) as giornateVinte
				FROM (punteggio INNER JOIN utente ON punteggio.idUtente = utente.id) LEFT JOIN view_2_giornatevinte ON punteggio.idUtente = view_2_giornatevinte.idUtente
				WHERE punteggio.idGiornata <= '" . $idGiornata . "' AND punteggio.idLega = '" . $idLega . "'
				GROUP BY utente.id
				ORDER BY punteggioTot DESC , giornateVinte DESC";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$classifica = NULL;
		while ($row = mysql_fetch_object($exe,__CLASS__))
			$classifica[$row->id] = $row;
		return $classifica;
	}
	
	public static function getAllPunteggiByGiornata($giornata,$idLega)
	{
		$q = "SELECT utente.idUtente, idGiornata, nome, punteggio
				FROM punteggio RIGHT JOIN utente ON punteggio.idUtente = utente.idUtente 
				WHERE (idGiornata <= " . $giornata . " OR idGiornata IS NULL) AND utente.idLega = '" . $idLega . "'
				ORDER BY idGiornata";
		$exe = mysql_query($q) or self::sqlError($q);
		$i = 0;
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__))
		{
			if(isset($classifica[$row->idUtente][$row->idGiornata]))
				$classifica[$row->idUtente][$row->idGiornata] += $row->punteggio;
			else
				$classifica[$row->idUtente][$row->idGiornata] = $row->punteggio;
		}
		$somme = self::getClassificaByGiornata($idLega,$giornata);
		if(isset($somme))
		{
			foreach($somme as $key=>$val)
				$somme[$key] = $classifica[$key];
		}
		else
		{
			require_once(INCDIR . 'utente.db.inc.php');
			$utenteObj = new utente();
			
			$squadre = $utenteObj->getElencoSquadreByLega($idLega);
			foreach($squadre as $key => $val)
				$somme[$key][0] = 0;
		}
		return($somme);
	}
	
	
	public static function getGiornateWithPunt()
	{
		$q = "SELECT COUNT(DISTINCT(idGiornata)) as numGiornate
				FROM punteggio";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		while ($row = mysql_fetch_object($exe))
			return $row->numGiornate;
	}

	protected static function recurSost($ruolo,&$panch,&$cambi,$giornata)
	{
		require_once(INCDIR . 'voto.db.inc.php');
		require_once(INCDIR . 'giocatore.db.inc.php');
		
		$votoObj = new voto();
		$giocatoreObj = new giocatore();
		
		$num = count($panch);
		for($i = 0;$i < $num;$i++)
		{
			$player = $panch[$i];
			$presenza = $votoObj->getPresenzaByIdGioc($player,$giornata);
			if(($giocatoreObj->getRuoloByIdGioc($player) == $ruolo) && ($presenza))
			{
				array_splice($panch,$i,1);
				$cambi ++;
				return $player;
			}
		}
		return FALSE;
	}

	public static function calcolaPunti($giornata,$idUtente,$idLega,$percentualePunteggio = NULL)
	{
		require_once(INCDIR . 'formazione.db.inc.php');
		require_once(INCDIR . 'voto.db.inc.php');
		require_once(INCDIR . 'giocatore.db.inc.php');
		require_once(INCDIR . 'schieramento.db.inc.php');
		require_once(INCDIR . 'lega.db.inc.php');

		// Se i punti di quella squadra e giornata ci sono già, esce
		$punteggioOld = self::getPunteggi($idUtente,$giornata);
		$datiLega = Lega::getLegaById($idLega);
		if($punteggioOld == '0' || $punteggioOld == NULL)
		{
			$formazione = Formazione::getFormazioneBySquadraAndGiornata($idUtente,$giornata);
			if ($formazione != FALSE)
			{
				$cambi = 0;
				$somma = 0;
				$flag = 0;
				$idFormazione = $formazione->id;
				$eCap = $formazione->cap;
				// ottengo il capitano che ha preso voto
				foreach($eCap as $cap)
				{
					if(Voto::getPresenzaByIdGioc($cap,$giornata))
					{ 
						$flag = 1;
						break;
					}
				}
				if ($flag != 1)
					$cap = "";
				$panch = $formazione->elenco;
				$tito = array_splice($panch,0,11);
				foreach ($tito as $player)
				{
					$presenza = Voto::getPresenzaByIdGioc($player,$giornata);
					if((!$presenza) && ($cambi < 3))
					{
						$sostituto = self::recurSost(Giocatore::getRuoloByIdGioc($player),$panch,$cambi,$giornata);
						if($sostituto != 0)
							$player = $sostituto;
					}
					Schieramento::setConsiderazione($idFormazione,$player,1);
					$voto = Voto::getVotoByIdGioc($player,$giornata);
					if($player == $cap && $datiLega->capitano)
					{
						$voto *= 2;
						Schieramento::setConsiderazione($idFormazione,$cap,2);
					}
					$somma += $voto;
				}
				if($formazione->jolly == 1)
					$somma *= 2;
				if($punteggioOld == '0')
					$q = "UPDATE punteggio
							SET punteggio = '" . $somma . "' 
							WHERE idGiornata = '" . $giornata . "' AND idUtente = '" . $idUtente . "'";
				else
					$q = "INSERT INTO punteggio (idGiornata,idUtente,punteggio,idLega) 
							VALUES ('" . $giornata . "','" . $idUtente . "','" . $somma . "','" . $idLega . "')";
				if(DEBUG)
					FirePHP::getInstance(true)->log($q);
				mysql_query($q) or self::sqlError($q);
				if($percentualePunteggio != NULL)
				{
					$puntiDaTogliere = round((($somma / 100) * (100 - $percentualePunteggio)),1);
					$modulo = ($puntiDaTogliere * 10) % 5;
					$puntiDaTogliere = (($puntiDaTogliere * 10) - $modulo) / 10;
					$q = "INSERT INTO punteggio (idGiornata,idUtente,punteggio,penalità,idLega) 
							VALUES ('" . $giornata . "','" . $idUtente . "','" . - ($puntiDaTogliere) ."','Formazione non settata','" . $idLega . "')";
					mysql_query($q) or self::sqlError($q);
					if(DEBUG)
						FirePHP::getInstance(true)->log($q);
				}
				return TRUE;
			}
			else
				self::setPunteggiToZeroByGiornata($idUtente,$idLega,$giornata);
		}
	}
	
	public static function setPunteggiToZero($idUtente,$idLega)
	{
		$giornateWithPunt = self::getGiornateWithPunt();
		if(empty($giornateWithPunt))
			$giornateWithPunt = 0;
		for($i = 1; $i <= $giornateWithPunt; $i++)
		{
			$q = "INSERT INTO punteggio (punteggio,idGiornata,idUtente,idLega) 
					VALUES('0','" . $i . "','" . $idUtente . "','" . $idLega . "')";
			if(DEBUG)
				FirePHP::getInstance(true)->log($q);
			mysql_query($q) or self::sqlError($q);
		}
		return TRUE;
	}
	
	public static function setPunteggiToZeroByGiornata($idUtente,$idLega,$idGiornata)
	{
		if(self::getPunteggi($idUtente,$idGiornata) != '0')
			$q = "INSERT INTO punteggio (punteggio,idGiornata,idUtente,idLega) 
					VALUES('0','" . $idGiornata . "','" . $idUtente . "','" . $idLega . "')";
		else
			return TRUE;
		FirePHP::getInstance()->log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function getPenalitàBySquadraAndGiornata($idUtente,$idGiornata)
	{
		$q = "SELECT punteggio,penalità 
				FROM punteggio
				WHERE punteggio < 0 AND idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$values = FALSE;
		while ($row = mysql_fetch_object($exe,__CLASS__))
			$values = $row;
		return $values;
	}
	
	public static function getPenalitàByLega($idLega)
	{
		$q = "SELECT *
				FROM punteggio
				WHERE punteggio < 0 AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		$values = FALSE;
		while ($row = mysql_fetch_object($exe,__CLASS__))
			$values[$row->idUtente][$row->idGiornata] = $row->punteggio;
		return $values;
	}
	
	public static function setPenalità($punti,$motivo,$idGiornata,$idUtente,$idLega)
	{
		if($punti > 0) {
			if(self::getPenalitàBySquadraAndGiornata($idUtente,$idGiornata) != FALSE)
				$q = "UPDATE punteggio SET punteggio = '" . (-$punti) . "', penalità = '" . $motivo . "'
						WHERE idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "' AND punteggio < 0"; 
			else
				$q = "INSERT INTO punteggio (punteggio,penalità,idGiornata,idUtente,idLega) 
						VALUES('" . (-$punti) . "','" . $motivo . "','" . $idGiornata . "','" . $idUtente . "','" . $idLega . "')";
			FirePHP::getInstance()->log($q);
			return mysql_query($q) or self::sqlError($q);
		}
		else
			return TRUE;
	}
	
	public static function unsetPenalità($idUtente,$idGiornata)
	{
		$q = "DELETE FROM punteggio
				WHERE punteggio < 0 AND idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "'";
		return  mysql_query($q) or self::sqlError($q);
	}
	
	public static function unsetPunteggio($idUtente,$idGiornata)
	{
		$q = "DELETE FROM punteggio
				WHERE punteggio > 0 AND idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "'";
		return  mysql_query($q) or self::sqlError($q);
	}
}
?>
