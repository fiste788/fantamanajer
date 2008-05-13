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
		$q = "SELECT IdGioc,Cognome,Nome,Ruolo,IdSquadra,Club,Voti FROM giocatore WHERE IdSquadra='" . $idSquadra . "' ORDER BY IdGioc ASC";
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
		$q = "SELECT IdGioc,Cognome,Nome,Ruolo,IdSquadra,Club,Voti FROM giocatore WHERE IdSquadra= '0' AND Club <> '' AND Ruolo = '". $ruolo . "' ORDER BY Cognome;";
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
			$result[$row[0]] = $row;		foreach($result as $key=>$val)
		{
			$appo[$giocatori[$i]] = $result[$giocatori[$i]];
			$i++;
		}
		return $appo;
	}
	
      function recuperaOrdine($giornata,$idsquadra,$idg)
      {
 		// ricavo la formazione relativa
		$query="SELECT Elenco FROM formazioni WHERE idgiornata='$giornata' AND idsquadra='$idsquadra'";
		$risu=mysql_query($query) or die("Query non valida: ".$query . mysql_error());
		$riga = mysql_fetch_array($risu, MYSQL_NUM) or die("Query non valida: ".$query . mysql_error());
		$formaz=explode("!",$riga[0]);
		// ottengo i titolari
		$tito=explode(";",$formaz[0]);
		// ottengo i panchinari
		$panch=explode(";",$formaz[1]);
    array_shift($panch);
    $fusion=array_merge($tito,$panch);             
        for($i=0;$i<count($fusion);$i++)
        {
		      $pieces=explode("-",$fusion[$i]);
		      $fusion[$i]=$pieces[0];

            //print "num$i:$fusion[$i] in $kiave<br>";
            if(strstr($idg,$fusion[$i]))
            {
              return $i;
            }
        }

      }
	
	function createGiornataDettaglioByGiocatori($result,$giornata,$squadra)
	{
		$formazione = array();
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
		$q = "SELECT * FROM giocatore WHERE idSquadraAcquisto <> 0";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		while($row = mysql_fetch_row($exe))
		{
			$values[] = $row;
		}
		if(isset($values))
		{
			foreach ($values as $key => $val)
			{
				if($val[8] == -1)
				{
					$values[$key][8] = 0;
					$values[$key][6] = 0;
				}
				else
				{
					$values[$key][6] = $val[8];
					$values[$key][8] = 0;
				}
				if($val[6] != 0)
					$trasf[$val[6]]['old'] = $val[0];
				else
					$trasf[$val[8]]['new'] = $val[0];
			}
			foreach ($values as $key => $val)
			{
				$q = "UPDATE giocatore SET idSquadraAcquisto = '" . $val[8] . "', idSquadra = '" . $val[6] . "' WHERE IdGioc = '" . $val[0] . "'; ";
				mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
			}
			foreach ($trasf as $key => $val)
			{
				$q = "INSERT INTO trasferimenti (IdGiocOld,IdGiocNew,IdSquadra) VALUES ('" . $val['old'] . "' , '" . $val['new'] . "' ,'" . $key . "');";
				mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
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
	function returnarray($path) 
	{
		if(!file_exists($path)) die("File non esistente");
		$content = join('',file($path));
		$players=explode("\n",$content);
		foreach ($players as &$value) 
		{
			$par=explode(";",$value);
			$key=$par[0];
			$keys[]=$key;
		}
		$c = array_combine($keys, $players);
		return $c;
	}

	// aggiorna di giornata in giornata i giocatori, togliendo qll ceduti
	function updateListaGiocatori($giornata)
	{
		$percorso = "docs/voti/Giornata".$giornata.".csv";
		$players_now = $this->returnarray($percorso);
		$q = "SELECT * FROM giocatore WHERE Club <> ''";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());
		$handle = fopen("docs/voti/ToltiGiornata".$giornata.".csv", "a");
		while($row=mysql_fetch_row($exe))
		{
			$chiave=$row[0];
			if(!array_key_exists($chiave,$players_now))
			{
				fwrite($handle,"$row[0];$row[1];$row[2]\n");
				$update="UPDATE giocatore SET Club = '' WHERE IdGioc='".$chiave."';";
				mysql_query($update) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());
			}
		}
		fclose($handle);
	}  
}
?>
