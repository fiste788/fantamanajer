<?php
class punteggi
{
	function checkPunteggi($giornata)
	{
		$q="SELECT * FROM punteggi WHERE IdGiornata='".$giornata."'";
		$exe=mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		if(mysql_num_rows($exe)>0)
			return FALSE;
		else
			return TRUE;
	}
    
    function getPunteggi($idUtente,$idGiornata)
    {
        $query="SELECT punteggio 
				FROM punteggi WHERE idUtente='".$idUtente."' AND idGiornata='".$idGiornata."'";
		$exe=mysql_query($query) or die("Query non valida: ".$query . mysql_error());
		while ($row = mysql_fetch_array($exe))
			return $row[0];       
    }
    
	function getClassifica()
	{
		$q = "SELECT utente.idUtente,nome,SUM(punteggio) as punteggioTot,AVG(punteggio) as punteggioMed, MAX(punteggio) as punteggioMax, MIN(punteggio) as punteggioMin 
				FROM punteggi INNER JOIN utente on punteggi.idUtente = utente.idUtente 
				GROUP BY idUtente ORDER BY punteggioTot DESC";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		while ($row = mysql_fetch_array($exe))
			$classifica[] = $row;
		if(isset($classifica))
			return($classifica);
		else
		{
			$q = "SELECT idUtente, nome 
					FROM utente";
			$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
			while ($row = mysql_fetch_array($exe) )
			{
				$row['punteggioTot'] = 0;
				$row['punteggioMed'] = 0;
				$row['punteggioMax'] = 0;
				$row['punteggioMin'] = 0;
				$classifica[] = $row;
			}
			return $classifica;
		}
	}

	function getAllPunteggi()
	{
		$q = "SELECT utente.idUtente, idGiornata,nome, punteggio 
				FROM punteggi INNER JOIN utente ON punteggi.idUtente = utente.idUtente";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		$i=0;
		while ($row = mysql_fetch_array($exe))
		{
			$classifica[$row['idUtente']] [$row['idGiornata']]= $row['punteggio'];
			$somme[$row['idUtente']] = array_sum($classifica[$row['idUtente']]);
		}
		if(isset($somme))
		{
			arsort($somme);
			$appo = array_keys($somme);
			for($i = 0; $i < count($classifica); $i++)
			{
				for($j = 1 ; $j <= count($classifica [$appo[$i]]) ; $j++)
				{
			  		$classificaokay[$appo[$i]][$j] = $classifica[$appo[$i]] [$j];
				}
			}
		}
		else
		{
			require_once(INCDIR.'utente.inc.php');
			$utenteObj = new utente();
			$squadre = $utenteObj->getElencoSquadre();
			foreach($squadre as $key=>$val)
				$classificaokay[$key][0] = 0;
		}
	    return($classificaokay);
	}
	
	function getPosClassifica($classifica) //come classifica si intende la variabile uscita dalla funzione getClassifica
	{
		$pos = array();
		$i=1;
		foreach($classifica as $key => $val)
		{
			$pos[$val[0]] = $i;
			$i++;
		}
		return $pos;
	}
	
	function getAllPunteggiByGiornata($giornata)
	{
		$q = "SELECT utente.idUtente, idGiornata, nome, punteggio 
				FROM punteggi INNER JOIN utente ON punteggi.idUtente = utente.idUtente 
				WHERE idGiornata <= " . $giornata;
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		$i=0;
		while ($row = mysql_fetch_array($exe))
		{
			$classifica[$row['idUtente']] [$row['idGiornata']]= $row['punteggio'];
			$somme[$row['idUtente']] = array_sum($classifica[$row['idUtente']]);
		}
		if(isset($somme))
		{
			arsort($somme);
			$appo = array_keys($somme);
			for($i = 0; $i < count($classifica); $i++)
				for($j = 1 ; $j <= count($classifica [$appo[$i]]) ; $j++)
			  		$classificaokay[$appo[$i]][$j] = $classifica[$appo[$i]] [$j];
		}
		else
		{
			require_once(INCDIR.'utente.inc.php');
			$utenteObj = new utente();
			$squadre = $utenteObj->getElencoSquadre();
			foreach($squadre as $key=>$val)
				$classificaokay[$key][0] = 0;
		}
	    return($classificaokay);
	}
	
	
	function getGiornateWithPunt()
	{
		$q = "SELECT COUNT(DISTINCT(idGiornata)) 
				FROM punteggi";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		while ($row = mysql_fetch_row($exe))
			return($row[0]);
	}

/* tutte le funzione da qui in poi sono da mettere nelle apposite classi tranne quella che calcola i punteggi */
    
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
            if(($giocatore->getRuoloByIdGioc($player) == $ruolo) && ($presenza))
            {
                array_splice($panch,$i,1);
                $cambi++;
                return $player;
            }
        }
        return 0;
    }

	function calcolaPunti($giornata,$idsquadra)
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
	    if($this->getPunteggi($idsquadra,$giornata))
	        return;
	    $cambi = 0;
	    $somma = 0;
	    $flag = 0;
	    $form = $formazioneObj->getFormazioneBySquadraAndGiornata($idsquadra,$giornata);
	    $idform = $form['Id'];
	    $ecap = $form['Cap'];
	    // ottengo il capitano che ha preso voto
	    foreach($ecap as $cap)
	    {
	        if($votiObj->getPresenzaById($cap,$giornata))
	        { 
	            $flag = 1;
	            break;
	        }
	    }
	    if ($flag != 1)
	        $cap = "";
	    $panch = $form['Elenco'];
	    $tito = array_splice($panch,0,11);
	    foreach ($tito as $player)
	    {
	
	        $presenza = $votiObj->getPresenzaById($player,$giornata);
	        if((!$presenza) && ($cambi < 3))
	        {
	            $sostituto = $this->recurSost($giocatoreObj->getRuoloById($player),$panch,$cambi,$giornata);
	            if($sostituto != 0)
	                $player = $sostituto;
	        }
	        $schieramentoObj->setConsiderazione($idform,$player);
	        $voto = $votiObj->getVotoById($player,$giornata);
	        if($player == $cap)
	        {
	            $voto *= 2;
	            $schieramentoObj->setConsiderazione($idform,$cap);
	        }    
	        $somma += $voto;
	    }
	    $q = "INSERT INTO punteggi(idGiornata,idUtente,punteggio) VALUES ('".$giornata."','".$idsquadra."','".$somma."')";
	    mysql_query($q) or die("Query non valida: ".$q. mysql_error());
	}
}
?>