<?php 
class giocatore
{
	var $idGioc;
	var $cognome;
	var $nome;
	var $ruolo;
	var $club;
	
	function giocatore()
	{ 
		$this->IdGioc = NULL;
		$this->Cognome = NULL;
		$this->Nome = NULL;
		$this->Ruolo = NULL;
		$this->Club = NULL;
	}
	
	function setFromRow($row)
	{ 
		$this->idGioc = $row[0];
		$this->Cognome = $row[1];
		$this->Nome = $row[2];
		$this->Ruolo = $row[3];
		$this->Club = $row[4];
	}
	
	function getGiocatoriByIdSquadra($idUtente)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, idUtente
            FROM giocatore INNER JOIN squadre ON giocatore.idGioc = squadre.idGioc
            WHERE idUtente ='$idUtente'
            ORDER BY giocatore.idGioc ASC";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());
		$giocatori = array();
		while($row=mysql_fetch_array($exe))
			$giocatori[] = $row;
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
	
/*	function getFreePlayer($ruolo)
	{
		$q = "SELECT giocatore.IdGioc, Cognome, Nome, Ruolo, IdSquadra, Club,SUM( Gol ) as Gol,       SUM( Assist ) as Assist
            FROM giocatore LEFT JOIN voti ON giocatore.IdGioc = voti.IdGioc
            WHERE ruolo ='" . $ruolo . "'
            AND idsquadra =0
            AND Club <> ''
            GROUP BY giocatore.IdGioc
            ORDER BY Cognome";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());
		while($row=mysql_fetch_array($exe,MYSQL_ASSOC))
		{
                $giocatori[]=$row;
		}
		return $giocatori;
	}*/
	function getFreePlayer($ruolo)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, club,SUM( gol ) as Gol,SUM( assist ) as Assist,AVG(voto) as mediaPunti, AVG(votoUff) as mediaVoti,count(voto) as Presenze
            FROM giocatore LEFT JOIN voti ON giocatore.IdGioc = voti.IdGioc
            WHERE ruolo ='" . $ruolo . "'
            AND giocatore.idGioc NOT IN (SELECT idGioc FROM squadre WHERE idLega  = '" . $_SESSION['idLega'] . "')
            AND club <> ''
            AND (votoUff <> 0 OR voto IS NULL)
            GROUP BY giocatore.idGioc
            ORDER BY cognome";
		$exe = mysql_query($q) or die(MYSQL_ERRNO(). $q ." ".MYSQL_ERROR());
		while($row=mysql_fetch_array($exe,MYSQL_ASSOC))
            $giocatori[$row['idGioc']]=$row;
		return $giocatori;
	}
	
	function getGiocatoriByArray($giocatori)
	{
		$q = "SELECT idGioc,cognome,nome,ruolo FROM giocatore WHERE idGioc IN (";
		foreach($giocatori as $key => $val)
			$q .= $val . ",";
		$q = substr($q,0,-1);
		$q .= ");";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		while($row = mysql_fetch_array($exe))
			$result[] = $row;
		return $result;
	}
	
	function getGiocatoreById($idGioc)
	{
		$q = "SELECT idGioc,cognome,nome,ruolo FROM giocatore WHERE idGioc = '" . $idGioc . "';";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		while($row = mysql_fetch_array($exe))
			$result[$row[0]] = $row;
		return $result;
	}
	
	function getGiocatoreByIdWithStats($giocatore)
	{
		$q = "SELECT giocatore.IdGioc, cognome, nome, ruolo, idUtente, club, AVG( voto ) as mediaPunti,avg(votoUff) as mediaVoti,COUNT( votoUff ) as presenze, SUM( gol ) as gol, SUM( assist ) as assist 
				FROM squadre INNER JOIN (giocatore LEFT JOIN voti ON giocatore.idGioc = voti.idGioc) on squadre.idGioc = giocatore.idGioc 
				WHERE giocatore.idGioc = '" . $giocatore . "' AND (votoUff <> 0 OR voto IS NULL) 
				GROUP BY giocatore.idGioc;";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		$q2 = "SELECT idGiornata, voto,votoUff , gol, assist FROM voti  WHERE idGioc = '" . $giocatore . "';";
		$exe2 = mysql_query($q2) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q2);
		while($row = mysql_fetch_array($exe))
			$values[] = $row;
		while($row = mysql_fetch_array($exe2))
		{
			$data[$row['idGiornata']] = $row;
			unset($data[$row['idGiornata']]['idGiornata']);
			unset($data[$row['idGiornata']][0]);
		}
		if(isset($data))
			$values['data'] = $data;
		return $values;
	}
	
    function getVotiGiocatoryByGiornataSquadra($giornata,$idUtente)
    {
        $q="SELECT giocatore.idGioc as gioc, cognome, nome, ruolo, club, idPosizione, considerato
                FROM schieramento
                INNER JOIN giocatore ON schieramento.idGioc = giocatore.idGioc
                WHERE idFormazione=(SELECT idFormazione FROM formazioni WHERE idGiornata='".$giornata."' AND idUtente='".$idUtente."')";
        $exe=mysql_query($q) or die ("Query non valida: ".$q. mysql_error());
        while ($row = mysql_fetch_array($exe,MYSQL_ASSOC))
        {
            
            $idgioc=$row['gioc'];
            $q="SELECT voto FROM voti WHERE idGioc='".$idgioc."' AND idGiornata='".$giornata."';";
            $mais=mysql_query($q) or die ("Query non valida: ".$q. mysql_error());
            $flag=0;
            while($riga=mysql_fetch_array($mais,MYSQL_ASSOC))
            {
                $row['voto']=$riga['voto'];
                $flag=1;
            }
            if(!$flag)
                $row['voto']="";
				$elenco[] = $row;
        }
		return($elenco);

    }
	
	function createGiornataDettaglioByGiocatori($giornata,$squadra)
	{
		$formazione = array();
		$q = "SELECT idGioc,nome,cognome,ruolo,club FROM giocatore WHERE idGioc = "; 
		foreach($result as $key=>$val)
		{
			$q .= substr($key,0,3) . " OR idGioc = ";
		}
		foreach($result as $key=>$val)
		{
			$giocatori[substr($key,0,3)]['punt'] = $val;
			if( (strpos($key,'panch')) === FALSE )
				$giocatori[substr($key,0,3)]['panch'] = FALSE;
			else
			 	$giocatori[substr($key,0,3)]['panch'] = TRUE;
			 if( (strpos($key,'cap')) === FALSE )
				$giocatori[substr($key,0,3)]['cap'] = FALSE;
			else
			 	$giocatori[substr($key,0,3)]['cap'] = TRUE;
		}
		$q = substr($q , 0 , -13);
		$exe = mysql_query($q);
		$ruoli = array('P'=>'Por.','D'=>'Dif.','C'=>'Cen','A'=>'Att.');        
		while ($row = mysql_fetch_array($exe))
		{
			$row[] = $giocatori[ $row['idGioc'] ]['punt'];
			$row['punt'] = $giocatori[ $row['idGioc'] ]['punt'];
			$row['cognome'] = $row['cognome'];
			$row['nome'] = $row['nome'];
			$row['ruolo'] = $ruoli[$row['ruolo']]; 
			if($giocatori[ $row['idGioc'] ]['panch'] ==TRUE  )
			{	
				$row[] = TRUE;
				$row['panch'] = TRUE;
			}
			else
			{	
				$row[] = FALSE;
				$row['panch'] = FALSE;
			}
			if($giocatori[ $row['idGioc'] ]['cap'] ==TRUE  )
			{	
				$row[] = TRUE;
				$row['cap'] = TRUE;
			}
			else
			{	
				$row[] = FALSE;
				$row['cap'] = FALSE;
			}
		  $prog=$this->recuperaOrdine($giornata,$squadra,$row['idGioc']);
		  $formazione[$prog] = $row;
		}
    	ksort($formazione);		
		return $formazione;
	}
	/*
	function getGiocatoreAcquistatoByIdSquadra($squadra)
	{
		$q = "SELECT * FROM giocatore WHERE idSquadraAcquisto = '" . $squadra . "';";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		return mysql_fetch_row($exe);
	}
	
	function unsetGiocatoreAcquistatoByIdGioc($gioc)
	{
		$q = "UPDATE giocatore SET IdSquadraAcquisto = 0 WHERE idGioc = '" . $gioc . "';";
		return mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
	}
	
	function setGiocatoreAcquistatoByIdGioc($gioc,$squadra)
	{
		$q = "UPDATE giocatore SET IdSquadraAcquisto = '" . $squadra . "' WHERE idGioc = '" . $gioc . "';";
		return mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
	}
	
	function getGiocatoreLasciatoByIdSquadra($squadra)
	{
		$q = "SELECT * FROM giocatore WHERE idSquadra = '" . $squadra . "' AND idSquadraAcquisto = -1;";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		return mysql_fetch_row($exe);
	}
	
	function unsetGiocatoreLasciatoByIdGioc($gioc)
	{
		$q = "UPDATE giocatore SET IdSquadraAcquisto = 0 WHERE idGioc = '" . $gioc . "';";
		return mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
	}
	
	function setGiocatoreLasciatoByIdGioc($gioc)
	{
		$q = "UPDATE giocatore SET IdSquadraAcquisto = -1 WHERE idGioc = '" . $gioc . "';";
		return mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
	}
	
	function unsetGiocatoreLasciatoByIdSquadra($squadra)
	{
		$q = "UPDATE giocatore SET IdSquadraAcquisto = 0 WHERE idSquadra = '" . $squadra . "' AND idSquadraAcquisto = -1 ;";
		return mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
	}
	
	function doTransfert()
	{
		require_once(INCDIR.'eventi.inc.php');
		require_once(INCDIR.'formazione.inc.php');
		$eventiObj = new eventi();
		$formazioneObj = new formazione();
		$q = "SELECT * FROM giocatore WHERE idSquadraAcquisto <> 0";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		while($row = mysql_fetch_array($exe))
		{
			$values[] = $row;
		}
		if(isset($values))
		{
			foreach ($values as $key => $val)
			{
				if($val['idSquadraAcquisto'] == -1)
				{
					$values[$key]['idSquadraAcquisto'] = 0;
					$values[$key]['IdSquadra'] = 0;
				}
				else
				{
					$values[$key]['IdSquadra'] = $val['idSquadraAcquisto'];
					$values[$key]['idSquadraAcquisto'] = 0;
				}
				if($val['IdSquadra'] != 0)
					$trasf[$val['IdSquadra']]['old'] = $val[0];
				else
					$trasf[$val['idSquadraAcquisto']]['new'] = $val[0];
			}
			foreach ($values as $key => $val)
			{
				$q = "UPDATE giocatore SET idSquadraAcquisto = '" . $val['idSquadraAcquisto'] . "', idSquadra = '" . $val['IdSquadra'] . "' WHERE IdGioc = '" . $val[0] . "'; ";
				mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
			}
			foreach ($trasf as $key => $val)
			{
				$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($key,GIORNATA);
				echo "<pre>".print_r($formazione,1)."</pre>";
				if($formazione != FALSE)
				{
					echo "ok";
					if(array_search($val['old'],$formazione['Elenco']) != FALSE)
					{
						echo "ok2";
						$q = "UPDATE schieramento SET IdGioc = '" . $val['new'] . "' WHERE IdGioc = '" . $val['old'] . "' AND IdFormazione = '" . $formazione['Id'] . "';";
						mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
						$pos = array_search($val['old'],$formazione['Cap']);
						if($pos != FALSE)
						{
							$q = "UPDATE formazioni SET " . $pos . " = '" . $val['new'] . "' WHERE IdFormazione = '" . $formazione['Id'] . "';";
							mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
						}
					}
				}
				$q = "INSERT INTO trasferimenti (IdGiocOld,IdGiocNew,IdSquadra) VALUES ('" . $val['old'] . "' , '" . $val['new'] . "' ,'" . $key . "');";
				mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
				$q = "SELECT IdTrasf FROM trasferimenti WHERE IdGiocOld = '" . $val['old'] . "' AND IdGiocNew = '" . $val['new'] . "' AND IdSquadra = '" . $key . "';";
				$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());
				$data = mysql_fetch_row($exe);
				$eventiObj->addEvento('4',$_SESSION['idsquadra'],$data[0]);
			}
		}
	}
	*/
/*
    Restituisce un array bidimensionale contenente tutti i campi di un file CSV, potenziato per i CSV creati da Excel.
    
        test1;test2;test3;test4;"test5
        test5b
        test5c";test6
    
    Supporta i campi stringa delimitati da " contenenti andate accapo.
    
    Codice: <a href="http://it2.php.net/manual/it/function.fgetcsv.php" target="_blank">http://it2.php.net/manual/it/function.fgetcsv.php</a>
    
    Esempio:
        $content = join('',file('test.csv'));
        $liste = csv2array($content);
        print_r($liste);
*/
	function getGiocatoryByIdSquadraWithStats($idUtente)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, idUtente, club, AVG( voto ) as voto,COUNT( votoUff ) as presenze, SUM( gol ) as gol, SUM( assist ) as assist,AVG(votoUff) as votoeff
			FROM squadre INNER JOIN (giocatore LEFT JOIN voti ON giocatore.idGioc = voti.idGioc) ON squadre.idGioc = giocatore.idGioc
			WHERE idUtente ='" . $idUtente . "' 
			GROUP BY giocatore.idGioc";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());
		$giocatori = "";
		while($row=mysql_fetch_row($exe))
		{
			$giocatori[] = $row;
		}
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
		
	function getMedieVoto($idGioc)
	{
		$q="SELECT AVG(voto) as mediaPunti,AVG(votoUff) as mediaVoti,count(voto) as Presenze FROM voti WHERE idGioc='".$idGioc."' AND votoUff <> 0 AND voto <> 0 GROUP by idGioc";
		$exe = mysql_query($q) or die(MYSQL_ERRNO(). $q ." ".MYSQL_ERROR());
		while($row=mysql_fetch_array($exe,MYSQL_ASSOC))
		{
			return $row;
		}
	}
}
?>