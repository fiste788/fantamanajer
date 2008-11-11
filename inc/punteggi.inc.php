<?php
class punteggi
{
	var $punteggio;
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
		while ($row = mysql_fetch_array($exe))
			$punteggio = $row['punteggio'];
		return $punteggio;      
	}

	function getClassifica($idLega)
	{
		$q = "SELECT utente.idUtente,nome,SUM(punteggio) as punteggioTot,AVG(punteggio) as punteggioMed, MAX(punteggio) as punteggioMax, MIN(punteggio) as punteggioMin 
				FROM punteggi INNER JOIN utente on punteggi.idUtente = utente.idUtente
				WHERE punteggi.idLega = '" . $idLega . "' 
				GROUP BY idUtente 
				ORDER BY punteggioTot DESC";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe))
			$classifica[] = $row;
		if(isset($classifica))
			return($classifica);
		else
		{
			$q = "SELECT idUtente, nome 
					FROM utente";
			$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
			while ($row = mysql_fetch_array($exe) )
			{
				$row['punteggioTot'] = 0;
				$row['punteggioMed'] = 0;
				$row['punteggioMax'] = 0;
				$row['punteggioMin'] = 0;
				$classifica[$row['idUtente']] = $row;
			}
			return $classifica;
		}
	}

	function getAllPunteggi($idLega)
	{
		$q = "SELECT utente.idUtente, idGiornata,nome, punteggio 
				FROM punteggi INNER JOIN utente ON punteggi.idUtente = utente.idUtente
				WHERE punteggi.idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$i = 0;
		while ($row = mysql_fetch_array($exe))
		{
			$classifica[$row['idUtente']] [$row['idGiornata']] = $row['punteggio'];
			$somme[$row['idUtente']] = array_sum($classifica[$row['idUtente']]);
		}
		if(isset($somme))
		{
			arsort($somme);
			$appo = array_keys($somme);
			for($i = 0; $i < count($classifica); $i++)
			{
				for($j = 1 ; $j <= count($classifica [$appo[$i]]) ; $j++)
					$classificaOkay[$appo[$i]][$j] = $classifica[$appo[$i]] [$j];
			}
		}
		else
		{
			require_once(INCDIR.'utente.inc.php');
			$utenteObj = new utente();
			$squadre = $utenteObj->getElencoSquadre();
			foreach($squadre as $key => $val)
				$classificaOkay[$key][0] = 0;
		}
		return($classificaOkay);
	}
	
	function getPosClassifica($idLega)
	{
		$classifica = $this->getClassifica($idLega);
		$pos = array();
		$i = 1;
		foreach($classifica as $key => $val)
		{
			$pos[$val[0]] = $i;
			$i++;
		}
		return $pos;
	}
	
	function getAllPunteggiByGiornata($giornata,$idLega)
	{
		$q = "SELECT utente.idUtente, idGiornata, nome, punteggio 
				FROM punteggi INNER JOIN utente ON punteggi.idUtente = utente.idUtente 
				WHERE idGiornata <= " . $giornata . " AND punteggi.idLega = '" . $idLega . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$i = 0;
		while ($row = mysql_fetch_array($exe))
		{
			$classifica[$row['idUtente']] [$row['idGiornata']] = $row['punteggio'];
			$somme[$row['idUtente']] = array_sum($classifica[$row['idUtente']]);
		}
		if(isset($somme))
		{
			arsort($somme);
			$appo = array_keys($somme);
			for($i = 0; $i < count($classifica); $i++)
				for($j = 1 ; $j <= count($classifica [$appo[$i]]) ; $j++)
					$classificaOkay[$appo[$i]][$j] = $classifica[$appo[$i]] [$j];
		}
		else
		{
			require_once(INCDIR.'utente.inc.php');
			$utenteObj = new utente();
			$squadre = $utenteObj->getElencoSquadre();
			foreach($squadre as $key => $val)
				$classificaOkay[$key][0] = 0;
		}
		return($classificaOkay);
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

		$formazioneObj = new formazione();
		$votiObj = new voti();
		$giocatoreObj = new giocatore();
		$schieramentoObj = new schieramento();
		// Se i punti di quella squadra e giornata ci sono già, esce
		$punteggioOld = $this->getPunteggi($idSquadra,$giornata);
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
				$schieramentoObj->setConsiderazione($idForm,$player);
				$voto = $votiObj->getVotoByIdGioc($player,$giornata);
				if($player == $cap)
				{
					$voto *= 2;
					$schieramentoObj->setConsiderazione($idForm,$cap);
				}
				$somma += $voto;
			}
			if($punteggioOld != NULL)
				$q = "UPDATE punteggi
						SET punteggio = '" . $somma . "' 
						WHERE idGiornata = '" . $giornata . "' AND idUtente = '" . $idSquadra . "'";
			else
				$q = "INSERT INTO punteggi(idGiornata,idUtente,punteggio,idLega) 
						VALUES ('" . $giornata . "','" . $idSquadra . "','" . $somma . "','" . $idLega . "')";
			mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		}
	}
	
	function setPunteggiToZero($idUtente,$idLega)
	{
		$giornateWithPunt = $this->getGiornateWithPunt();
		if(!empty($giornateWithPunt))
			$giornateWithPunt = 0;
		for($i = 1; $i < $giornateWithPunt; $i++)
		{
			$q = "INSERT INTO punteggi
					VALUES('0','" . $i . "','" . $idUtente . "','" . $idLega . "')";
			mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		}
	}
}
?>