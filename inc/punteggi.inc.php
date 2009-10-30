<?php
class punteggi
{
	var $punteggio;
	var $penalità;
	var $idGiornata;
	var $idUtente;
	var $idLega;
	
	function checkPunteggi($giornata)
	{
		$q = "SELECT * 
				FROM punteggi 
				WHERE idGiornata = '" . $giornata . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(mysql_num_rows($exe) > 0)
			return FALSE;
		else
			return TRUE;
	}

	function getPunteggi($idUtente,$idGiornata)
	{
		$q = "SELECT punteggio 
				FROM punteggi 
				WHERE idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$punteggio = NULL;
		if(mysql_num_rows($exe) > 0)
		{
			$punteggio = 0;
			while ($row = mysql_fetch_array($exe))
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
			$pos[$val[0]] = $i;
			$i++;
		}
		return $pos;
	}
	
	function getClassificaByGiornata($idLega,$idGiornata)
	{
		$q = "SELECT utente.idUtente, nome, SUM(punteggi.punteggio) AS punteggioTot, AVG(punteggi.punteggio) AS punteggioMed, MAX(punteggi.punteggio) AS punteggioMax, (SELECT MIN(punteggi.punteggio) FROM punteggi WHERE punteggio >= 0 AND idUtente = utente.idUtente) AS punteggioMin, COALESCE(giornateVinte,0) as giornateVinte
				FROM (punteggi INNER JOIN utente ON punteggi.idUtente = utente.idUtente) LEFT JOIN giornatevinte ON punteggi.idUtente = giornatevinte.idUtente
				WHERE punteggi.idGiornata <= '" . $idGiornata . "' AND punteggi.idLega = '" . $idLega . "'
				GROUP BY idUtente
				ORDER BY punteggioTot DESC , giornateVinte DESC";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe))
			$classifica[$row['idUtente']] = $row;
	
		if(isset($classifica))
			return($classifica);
		else
			return NULL;
	}
	
	function getAllPunteggiByGiornata($giornata,$idLega)
	{
		$q = "SELECT utente.idUtente, idGiornata, nome, punteggio
				FROM punteggi RIGHT JOIN utente ON punteggi.idUtente = utente.idUtente 
				WHERE (idGiornata <= " . $giornata . " OR idGiornata IS NULL) AND utente.idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$i = 0;
		while ($row = mysql_fetch_array($exe))
		{
			if(isset($classifica[$row['idUtente']] [$row['idGiornata']]))
				$classifica[$row['idUtente']] [$row['idGiornata']] += $row['punteggio'];
			else
				$classifica[$row['idUtente']] [$row['idGiornata']] = $row['punteggio'];
		}
		$somme = $this->getClassificaByGiornata($idLega,$giornata);
		if(isset($somme))
		{
			foreach($somme as $key=>$val)
				$somme[$key] = $classifica[$key];
		}
		else
		{
			require_once(INCDIR.'utente.inc.php');
			$utenteObj = new utente();
			$squadre = $utenteObj->getElencoSquadreByLega($idLega);
			foreach($squadre as $key => $val)
				$somme[$key][0] = 0;
		}
		return($somme);
		
	}
	
	
	function getGiornateWithPunt()
	{
		$q = "SELECT COUNT(DISTINCT(idGiornata)) 
				FROM punteggi";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_row($exe))
			return($row[0]);
	}

	function recurSost($ruolo,&$panch,&$cambi,$giornata)
	{
		require_once(INCDIR.'voti.inc.php');
		require_once(INCDIR.'giocatore.inc.php');
		$votiObj = new voti();
		$giocatoreObj = new giocatore();
		$num = count($panch);
		for($i = 0;$i < $num;$i++)
		{
			$player = $panch[$i];
			$presenza = $votiObj->getPresenzabyIdGioc($player,$giornata);
			if(($giocatoreObj->getRuoloByIdGioc($player) == $ruolo) && ($presenza))
			{
				array_splice($panch,$i,1);
				$cambi++;
				return $player;
			}
		}
		return 0;
	}

	function calcolaPunti($giornata,$idSquadra,$idLega)
	{
		require_once('formazione.inc.php');
		require_once('voti.inc.php');
		require_once('giocatore.inc.php');
		require_once('schieramento.inc.php');
		require_once('leghe.inc.php');

		$formazioneObj = new formazione();
		$votiObj = new voti();
		$giocatoreObj = new giocatore();
		$schieramentoObj = new schieramento();
		$legheObj = new leghe();
		// Se i punti di quella squadra e giornata ci sono già, esce
		$punteggioOld = $this->getPunteggi($idSquadra,$giornata);
		$datiLega = $legheObj->getLegaById($idLega);
		if($punteggioOld == '0' || $punteggioOld == NULL)
		{
			$cambi = 0;
			$somma = 0;
			$flag = 0;
			$form = $formazioneObj->getFormazioneBySquadraAndGiornata($idSquadra,$giornata);
			$idForm = $form['id'];
			$eCap = $form['cap'];
			// ottengo il capitano che ha preso voto
			foreach($eCap as $cap)
			{
				if($votiObj->getPresenzaByIdGioc($cap,$giornata))
				{ 
					$flag = 1;
					break;
				}
			}
			if ($flag != 1)
				$cap = "";
			$panch = $form['elenco'];
			$tito = array_splice($panch,0,11);
			foreach ($tito as $player)
			{
				$presenza = $votiObj->getPresenzaByIdGioc($player,$giornata);
				if((!$presenza) && ($cambi < 3))
				{
					$sostituto = $this->recurSost($giocatoreObj->getRuoloByIdGioc($player),$panch,$cambi,$giornata);
					if($sostituto != 0)
						$player = $sostituto;
				}
				$schieramentoObj->setConsiderazione($idForm,$player,1);
				$voto = $votiObj->getVotoByIdGioc($player,$giornata);
				if($player == $cap && $datiLega['capitano'])
				{
					$voto *= 2;
					$schieramentoObj->setConsiderazione($idForm,$cap,2);
				}
				$somma += $voto;
			}
			if($punteggioOld == '0')
				$q = "UPDATE punteggi
						SET punteggio = '" . $somma . "' 
						WHERE idGiornata = '" . $giornata . "' AND idUtente = '" . $idSquadra . "'";
			else
				$q = "INSERT INTO punteggi (idGiornata,idUtente,punteggio,idLega) 
						VALUES ('" . $giornata . "','" . $idSquadra . "','" . $somma . "','" . $idLega . "')";
			mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		}
	}
	
	function setPunteggiToZero($idUtente,$idLega)
	{
		$giornateWithPunt = $this->getGiornateWithPunt();
		if(empty($giornateWithPunt))
			$giornateWithPunt = 0;
		for($i = 1; $i <= $giornateWithPunt; $i++)
		{
			$q = "INSERT INTO punteggi (punteggio,idGiornata,idUtente,idLega) 
					VALUES('0','" . $i . "','" . $idUtente . "','" . $idLega . "')";
			mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		}
	}
	
	function setPunteggiToZeroByGiornata($idUtente,$idLega,$idGiornata)
	{
		if($this->getPunteggi($idUtente,$idGiornata) != '0')
			$q = "INSERT INTO punteggi (punteggio,idGiornata,idUtente,idLega) 
					VALUES('0','" . $idGiornata . "','" . $idUtente . "','" . $idLega . "')";
		else
			return TRUE;
		mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function getPenalitàBySquadraAndGiornata($idUtente,$idGiornata)
	{
		$q = "SELECT punteggio,penalità 
				FROM punteggi
				WHERE punteggio < 0 AND idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe))
			$val = $row;
		if(isset($val))
			return $val;
		else
			return FALSE;
	}
	
	function getPenalitàByLega($idLega)
	{
		$q = "SELECT *
				FROM punteggi
				WHERE punteggio < 0 AND idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe))
			$val[$row['idUtente']][$row['idGiornata']] = $row['punteggio'];
		if(isset($val))
			return $val;
		else
			return FALSE;
	}
	
	function setPenalità($punti,$motivo,$idGiornata,$idUtente,$idLega)
	{
		if($this->getPenalitàBySquadraAndGiornata($idUtente,$idGiornata) != FALSE)
			$q = "UPDATE punteggi SET punteggio = '" . (-$punti) . "', penalità = '" . $motivo . "'
					WHERE idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "'"; 
		else
			$q = "INSERT INTO punteggi (punteggio,penalità,idGiornata,idUtente,idLega) 
					VALUES('" . (-$punti) . "','" . $motivo . "','" . $idGiornata . "','" . $idUtente . "','" . $idLega . "')";
		return $exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function unsetPenalità($idUtente,$idGiornata)
	{
		$q = "DELETE FROM punteggi
				WHERE punteggio < 0 AND idUtente = '" . $idUtente . "' AND idGiornata = '" . $idGiornata . "'";
		return  mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
}
?>
