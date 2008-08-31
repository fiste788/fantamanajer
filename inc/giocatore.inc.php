<?php 
class giocatore
{
	var $IdGioc;
	var $Cognome;
	var $Nome;
	var $Ruolo;
	var $IdSquadra;
	var $Club;
	var $Voti;
	
	function giocatore()
	{ 
		$this->IdGioc = NULL;
		$this->Cognome = NULL;
		$this->Nome = NULL;
		$this->Ruolo = NULL;
		$this->IdSquadra = NULL;
		$this->Club = NULL;
		$this->Voti = NULL;
	}
	
	function setFromRow($row)
	{ 
		$this->idGioc = $row[0];
		$this->Cognome = $row[1];
		$this->Nome = $row[2];
		$this->Ruolo = $row[3];
		$this->idSquadra = $row[4];
		$this->Club = $row[5];
		$this->Voti = $row[6];
	}
	
	function getGiocatoriByIdSquadra($idSquadra)
	{
		$q = "SELECT IdGioc, Cognome, Nome, Ruolo, IdSquadra
            FROM giocatore 
            WHERE IdSquadra ='$idSquadra'
            ORDER BY IdGioc ASC";
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
	
	function getFreePlayer($ruolo)
	{
		$q = "SELECT giocatore.IdGioc,Cognome,Nome,Ruolo,IdSquadra,Club,AVG( Voto ) as Voti,SUM( Presenza ) as Presenze, SUM( Gol ) as Gol, SUM( Assist ) as Assist FROM giocatore LEFT JOIN voti ON giocatore.IdGioc = voti.IdGioc WHERE IdSquadra= '0' AND Club <> '' AND Ruolo = '". $ruolo . "' GROUP BY giocatore.IdGioc ORDER BY Cognome;";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());
		while($row=mysql_fetch_array($exe))
		{
			$giocatori[] = $row;
		}
		return $giocatori;
	}
	
	function getGiocatoriByArray($giocatori)
	{
		$q = "SELECT idGioc,Cognome,Nome,Ruolo,idSquadraAcquisto FROM giocatore WHERE idGioc IN (";
		foreach($giocatori as $key => $val)
			$q .= $val . ",";
		$q = substr($q,0,-1);
		$q .= ");";
		$i=0;
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		while($row = mysql_fetch_array($exe))
			$result[$row[0]] = $row;
		foreach($result as $key=>$val)
		{
			$appo[$giocatori[$i]] = $result[$giocatori[$i]];
			$i++;
		}
		return $appo;
	}
	
	function getGiocatoreById($giocatore)
	{
		$q = "SELECT giocatore.IdGioc, Cognome, Nome, Ruolo, IdSquadra, Club, AVG( Voto ) as votoMedio,SUM( Presenza ) as presenze, SUM( Gol ) as gol, SUM( Assist ) as assist FROM giocatore LEFT JOIN voti ON giocatore.IdGioc = voti.IdGioc WHERE giocatore.idGioc = '" . $giocatore . "' GROUP BY giocatore.IdGioc;";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		$q2 = "SELECT IdGiornata, Voto , Presenza , Gol, Assist FROM voti  WHERE idGioc = '" . $giocatore . "';";
		$exe2 = mysql_query($q2) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q2);
		while($row = mysql_fetch_array($exe))
			$values[] = $row;
		while($row = mysql_fetch_array($exe2))
		{
			$data[$row['IdGiornata']] = $row;
			unset($data[$row['IdGiornata']]['IdGiornata']);
			unset($data[$row['IdGiornata']][0]);
		}
		if(isset($data))
			$values['data'] = $data;
		return $values;
	}
	
    function getVotiGiocatoryByGiornataSquadra($giornata,$idsquadra)
    {
        $query="SELECT voti.IdGioc,Cognome, Ruolo, Club, Voto, IdPosizione,Considerato
                FROM voti
                INNER JOIN schieramento ON voti.IdGioc = schieramento.IdGioc
                INNER JOIN giocatore ON voti.IdGioc = giocatore.IdGioc
                WHERE schieramento.IdFormazione=(
                                                    SELECT IdFormazione
                                                    FROM formazioni
                                                    WHERE IdGiornata ='$giornata'
                                                    AND IdSquadra ='$idsquadra' )
                AND voti.IdGiornata ='$giornata'
                ORDER BY IdPosizione;";
        $exe=mysql_query($query) or die ("Query non valida: ".$query. mysql_error());
        while ($row = mysql_fetch_array($exe,MYSQL_ASSOC))
			$elenco[] = $row;	
		return($elenco);

    }
	
	function createGiornataDettaglioByGiocatori($giornata,$squadra)
	{
		$formazione = array();
		echo "<pre>".print_r($result,1)."</pre>";
		$q = "SELECT IdGioc,Nome,Cognome,Ruolo,Club FROM giocatore WHERE IdGioc = "; 
		foreach($result as $key=>$val)
		{
			$q .= substr($key,0,3) . " OR IdGioc = ";
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
        echo "<pre>".print_r($elenco,1)."</pre>";
		while ($row = mysql_fetch_array($exe))
		{
			$row[] = $giocatori[ $row['IdGioc'] ]['punt'];
			$row['punt'] = $giocatori[ $row['IdGioc'] ]['punt'];
			$row['Cognome'] = $row['Cognome'];
			$row['Nome'] = $row['Nome'];
			$row['Ruolo'] = $ruoli[$row['Ruolo']]; 
			if($giocatori[ $row['IdGioc'] ]['panch'] ==TRUE  )
			{	
				$row[] = TRUE;
				$row['panch'] = TRUE;
			}
			else
			{	
				$row[] = FALSE;
				$row['panch'] = FALSE;
			}
			if($giocatori[ $row['IdGioc'] ]['cap'] ==TRUE  )
			{	
				$row[] = TRUE;
				$row['cap'] = TRUE;
			}
			else
			{	
				$row[] = FALSE;
				$row['cap'] = FALSE;
			}
		  $prog=$this->recuperaOrdine($giornata,$squadra,$row['IdGioc']);
		  $formazione[$prog] = $row;
		}
    ksort($formazione);		
		return $formazione;


	}
	
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
		$eventiObj = new eventi();
		$q = "SELECT * FROM giocatore WHERE idSquadraAcquisto <> 0";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		while($row = mysql_fetch_array($exe))
		{
			$values[] = $row;
		}
		echo "<pre>".print_r($values,1)."</pre>";
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
				$q = "INSERT INTO trasferimenti (IdGiocOld,IdGiocNew,IdSquadra) VALUES ('" . $val['old'] . "' , '" . $val['new'] . "' ,'" . $key . "');";
				mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
				$q = "SELECT IdTrasf FROM trasferimenti WHERE IdGiocOld = '" . $val['old'] . "' AND IdGiocNew = '" . $val['new'] . "' AND IdSquadra = '" . $key . "';";
				$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());
				$data = mysql_fetch_row($exe);
				$eventiObj->addEvento('4',$_SESSION['idsquadra'],$data[0]);
			}
		}
	}
	
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
    function getGiocatoryByIdSquadraWithStats($idsquadra)
    {
		$q = "SELECT giocatore.IdGioc, Cognome, Nome, Ruolo, IdSquadra, Club, AVG( Voto ) as voto,SUM( Presenza ) as presenze, SUM( Gol ) as gol, SUM( Assist ) as assist
            FROM giocatore LEFT JOIN voti ON giocatore.IdGioc = voti.IdGioc
            WHERE IdSquadra ='" . $idsquadra . "'
            GROUP BY giocatore.IdGioc";
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
}
?>
