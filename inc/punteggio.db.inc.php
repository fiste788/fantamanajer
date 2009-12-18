<?php
class punteggio
{
	var $punteggio;
	var $penalità;
	var $idGiornata;
	var $idUtente;
	var $idLega;
	
	function checkPunteggi($giornata)
	{
		$q = "SELECT * 
				FROM punteggio 
				WHERE idGiornata = '" . $giornata . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		if(mysql_num_rows($exe) > 0)
			return FALSE;
		else
			return TRUE;
	}

	function getPunteggi($idUtente,$idGiornata)
	{
		$q = "SELECT punteggio 
				FROM punteggio 
				WHERE idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		$punteggio = NULL;
		if(mysql_num_rows($exe) > 0)
		{
			$punteggio = 0;
			while ($row = mysql_fetch_assoc($exe))
				$punteggio += $row['punteggio'];
		}
		return $punteggio;
	}
	
	function getPosClassifica($idLega)
	{
		$classifica = $this->getClassificaByGiornata($idLega,GIORNATA);
		$pos = array();
		$i = 1;
		foreach($classifica as $key => $val)
		{
			$pos[$val->idUtente] = $i;
			$i++;
		}
		return $pos;
	}
	
	function getGiornateVinte($idUtente)
	{
		$q = "SELECT giornateVinte 
				FROM giornatevinte 
				WHERE idUtente = '" . $idUtente . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while ($row = mysql_fetch_object($exe))
			$values = $row->giornateVinte;
		return $values;
	}
	
	function getClassificaByGiornata($idLega,$idGiornata)
	{
		$q = "SELECT utente.idUtente, nome, SUM(punteggio.punteggio) AS punteggioTot, AVG(punteggio.punteggio) AS punteggioMed, MAX(punteggio.punteggio) AS punteggioMax, (SELECT MIN(punteggio.punteggio) FROM punteggio WHERE punteggio >= 0 AND idUtente = utente.idUtente) AS punteggioMin, COALESCE(giornateVinte,0) as giornateVinte
				FROM (punteggio INNER JOIN utente ON punteggio.idUtente = utente.idUtente) LEFT JOIN giornatevinte ON punteggio.idUtente = giornatevinte.idUtente
				WHERE punteggio.idGiornata <= '" . $idGiornata . "' AND punteggio.idLega = '" . $idLega . "'
				GROUP BY idUtente
				ORDER BY punteggioTot DESC , giornateVinte DESC";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		$classifica = NULL;
		while ($row = mysql_fetch_object($exe))
			$classifica[$row->idUtente] = $row;
		return $classifica;
	}
	
	function getAllPunteggiByGiornata($giornata,$idLega)
	{
		$q = "SELECT utente.idUtente, idGiornata, nome, punteggio
				FROM punteggio RIGHT JOIN utente ON punteggio.idUtente = utente.idUtente 
				WHERE (idGiornata <= " . $giornata . " OR idGiornata IS NULL) AND utente.idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$i = 0;
		if(DEBUG)
			echo $q . "<br />";
		while ($row = mysql_fetch_object($exe))
		{
			if(isset($classifica[$row->idUtente][$row->idGiornata]))
				$classifica[$row->idUtente][$row->idGiornata] += $row->punteggio;
			else
				$classifica[$row->idUtente][$row->idGiornata] = $row->punteggio;
		}
		$somme = $this->getClassificaByGiornata($idLega,$giornata);
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
	
	
	function getGiornateWithPunt()
	{
		$q = "SELECT COUNT(DISTINCT(idGiornata)) as numGiornate
				FROM punteggio";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while ($row = mysql_fetch_object($exe))
			return $row->numGiornate;
	}

	function recurSost($ruolo,&$panch,&$cambi,$giornata)
	{
		require_once(INCDIR . 'voto.db.inc.php');
		require_once(INCDIR . 'giocatore.db.inc.php');
		
		$votoObj = new voto();
		$giocatoreObj = new giocatore();
		
		$num = count($panch);
		for($i = 0;$i < $num;$i++)
		{
			$player = $panch[$i];
			$presenza = $votoObj->getPresenzabyIdGioc($player,$giornata);
			if(($giocatoreObj->getRuoloByIdGioc($player) == $ruolo) && ($presenza))
			{
				array_splice($panch,$i,1);
				$cambi ++;
				return $player;
			}
		}
		return FALSE;
	}

	function calcolaPunti($giornata,$idSquadra,$idLega,$percentualePunteggio = NULL)
	{
		require_once(INCDIR . 'formazione.db.inc.php');
		require_once(INCDIR . 'voto.db.inc.php');
		require_once(INCDIR . 'giocatore.db.inc.php');
		require_once(INCDIR . 'schieramento.db.inc.php');
		require_once(INCDIR . 'lega.db.inc.php');

		$formazioneObj = new formazione();
		$votoObj = new voto();
		$giocatoreObj = new giocatore();
		$schieramentoObj = new schieramento();
		$legaObj = new lega();
		// Se i punti di quella squadra e giornata ci sono già, esce
		$punteggioOld = $this->getPunteggi($idSquadra,$giornata);
		$datiLega = $legaObj->getLegaById($idLega);
		if($punteggioOld == '0' || $punteggioOld == NULL)
		{
			$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($idSquadra,$giornata);
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
					if($votoObj->getPresenzaByIdGioc($cap,$giornata))
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
					$presenza = $votoObj->getPresenzaByIdGioc($player,$giornata);
					if((!$presenza) && ($cambi < 3))
					{
						$sostituto = $this->recurSost($giocatoreObj->getRuoloByIdGioc($player),$panch,$cambi,$giornata);
						if($sostituto != 0)
							$player = $sostituto;
					}
					$schieramentoObj->setConsiderazione($idFormazione,$player,1);
					$voto = $votoObj->getVotoByIdGioc($player,$giornata);
					if($player == $cap && $datiLega->capitano)
					{
						$voto *= 2;
						$schieramentoObj->setConsiderazione($idFormazione,$cap,2);
					}
					$somma += $voto;
				}
				if($punteggioOld == '0')
					$q = "UPDATE punteggio
							SET punteggio = '" . $somma . "' 
							WHERE idGiornata = '" . $giornata . "' AND idUtente = '" . $idSquadra . "'";
				else
					$q = "INSERT INTO punteggio (idGiornata,idUtente,punteggio,idLega) 
							VALUES ('" . $giornata . "','" . $idSquadra . "','" . $somma . "','" . $idLega . "')";
				if(DEBUG)
					echo $q . "<br />";
				mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
				if($percentualePunteggio != NULL)
				{
					$puntiDaTogliere = round((($somma / 100) * (100 - $percentualePunteggio)),1);
					$modulo = ($puntiDaTogliere * 10) % 5;
					$puntiDaTogliere = (($puntiDaTogliere * 10) - $modulo) / 10;
					$q = "INSERT INTO punteggio (idGiornata,idUtente,punteggio,penalità,idLega) 
							VALUES ('" . $giornata . "','" . $idSquadra . "','" . - ($puntiDaTogliere) ."','Formazione non settata','" . $idLega . "')";
					mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
					if(DEBUG)
						echo $q . "<br />";
				}
				return TRUE;
			}
			else
				$this->setPunteggiToZeroByGiornata($idSquadra,$idLega,$giornata);
		}
	}
	
	function setPunteggiToZero($idUtente,$idLega)
	{
		$giornateWithPunt = $this->getGiornateWithPunt();
		echo $giornateWithPunt;
		if(empty($giornateWithPunt))
			$giornateWithPunt = 0;
		for($i = 1; $i <= $giornateWithPunt; $i++)
		{
			$q = "INSERT INTO punteggio (punteggio,idGiornata,idUtente,idLega) 
					VALUES('0','" . $i . "','" . $idUtente . "','" . $idLega . "')";
			if(DEBUG)
				echo $q . "<br />";
			mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		}
		return TRUE;
	}
	
	function setPunteggiToZeroByGiornata($idUtente,$idLega,$idGiornata)
	{
		if($this->getPunteggi($idUtente,$idGiornata) != '0')
			$q = "INSERT INTO punteggio (punteggio,idGiornata,idUtente,idLega) 
					VALUES('0','" . $idGiornata . "','" . $idUtente . "','" . $idLega . "')";
		else
			return TRUE;
		if(DEBUG)
			echo $q . "<br />";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function getPenalitàBySquadraAndGiornata($idUtente,$idGiornata)
	{
		$q = "SELECT punteggio,penalità 
				FROM punteggio
				WHERE punteggio < 0 AND idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		$values = FALSE;
		while ($row = mysql_fetch_object($exe))
			$values = $row;
		return $values;
		}
	
	function getPenalitàByLega($idLega)
	{
		$q = "SELECT *
				FROM punteggio
				WHERE punteggio < 0 AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		$values = FALSE;
		while ($row = mysql_fetch_object($exe))
			$values[$row->idUtente][$row->idGiornata] = $row->punteggio;
		return $values;
	}
	
	function setPenalità($punti,$motivo,$idGiornata,$idUtente,$idLega)
	{
		if($this->getPenalitàBySquadraAndGiornata($idUtente,$idGiornata) != FALSE)
			$q = "UPDATE punteggio SET punteggio = '" . (-$punti) . "', penalità = '" . $motivo . "'
					WHERE idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "' AND punteggio < 0"; 
		else
			$q = "INSERT INTO punteggio (punteggio,penalità,idGiornata,idUtente,idLega) 
					VALUES('" . (-$punti) . "','" . $motivo . "','" . $idGiornata . "','" . $idUtente . "','" . $idLega . "')";
		if(DEBUG)
			echo $q . "<br />";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function unsetPenalità($idUtente,$idGiornata)
	{
		$q = "DELETE FROM punteggio
				WHERE punteggio < 0 AND idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "'";
		return  mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
}
?>
