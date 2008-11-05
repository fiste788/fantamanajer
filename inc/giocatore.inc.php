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
            	WHERE idUtente ='".$idUtente."'
            	ORDER BY giocatore.idGioc ASC";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());
		$giocatori = array();
		while($row = mysql_fetch_array($exe))
			$giocatori[] = $row;
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
	
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
		$q = "SELECT idGioc,cognome,nome,ruolo 
				FROM giocatore 
				WHERE idGioc IN (";
		foreach($giocatori as $key => $val)
			$q .= $val . ",";
		$q = substr($q,0,-1);
		$q .= ")";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR()." ".$q);
		while($row = mysql_fetch_array($exe))
			$result[] = $row;
		return $result;
	}
	
	function getGiocatoreById($idGioc)
	{
		$q = "SELECT idGioc,cognome,nome,ruolo 
				FROM giocatore 
				WHERE idGioc = '" . $idGioc . "'";
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
				GROUP BY giocatore.idGioc";
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
	
    function getVotiGiocatoriByGiornataSquadra($giornata,$idUtente)
    {
        $q = "SELECT giocatore.idGioc as gioc, cognome, nome, ruolo, club, idPosizione, considerato
                FROM schieramento
                INNER JOIN giocatore ON schieramento.idGioc = giocatore.idGioc
                WHERE idFormazione=(SELECT idFormazione FROM formazioni WHERE idGiornata = '".$giornata."' AND idUtente = '".$idUtente."')";
        $exe = mysql_query($q) or die ("Query non valida: ".$q. mysql_error());
        while ($row = mysql_fetch_array($exe,MYSQL_ASSOC))
        {
            $idgioc = $row['gioc'];
            $q = "SELECT voto FROM voti WHERE idGioc='".$idgioc."' AND idGiornata = '".$giornata."'";
            $exe = mysql_query($q) or die ("Query non valida: ".$q. mysql_error());
            $flag = 0;
            while($riga = mysql_fetch_array($exe,MYSQL_ASSOC))
            {
                $row['voto'] = $riga['voto'];
                $flag = 1;
            }
            if(!$flag)
                $row['voto'] = "";
			$elenco[] = $row;
        }
		return($elenco);
    }
	
	function getGiocatoriByIdSquadraWithStats($idUtente)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, idUtente, club, AVG( voto ) as voto,COUNT( votoUff ) as presenze, SUM( gol ) as gol, SUM( assist ) as assist,AVG(votoUff) as votoeff
				FROM squadre INNER JOIN (giocatore LEFT JOIN voti ON giocatore.idGioc = voti.idGioc) ON squadre.idGioc = giocatore.idGioc
				WHERE idUtente = '" . $idUtente . "' 
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
	
	function getRuoloByIdGioc($idGioc)
    {
        $q="SELECT ruolo 
				FROM giocatore 
				WHERE idGioc = '".$idGioc."'";
        $exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		while ($row = mysql_fetch_row($exe))
			return($row[0]);      
    }
    
    function updateTabGiocatore($giornata)
	{
		require_once(INCDIR.'fileSystem.inc.php');
		$fileSystemObj = new fileSystem();
		
	    $percorsoold = "./docs/ListaGiornata/ListaGiornata".($giornata-1).".csv";
	    if(!file_exists($percorsoold))
	        return;   
	    $percorso = "./docs/ListaGiornata/ListaGiornata".($giornata).".csv";  
	    $fileSystemObj->scaricaLista($percorso);  // crea il .csv con la lista aggiornata
	    $playersold = $fileSystemObj->returnArray($percorsoold);
	    $players = $fileSystemObj->returnArray($percorso);
	    
		// aggiorna eventuali cambi di club dei Giocatori-> Es.Turbato Tomas  da Juveterranova a Spartak Foligno
	    foreach($players as $key=>$line)
	    {
	        if(array_key_exists($key,$playersold))
	        {
	            $pieces = explode(";",$line);
	            $clubnew = $pieces[3];
	            $pieces = explode(";",$playersold[$key]);
	            $clubold = $pieces[3];
	            if($clubnew != $clubold)
	            {
	                $q = "UPDATE giocatore 
							SET Club = '".$clubnew."' 
							WHERE IdGioc = '".$key."s'";
	                mysql_query($q) or die("Query non valida: ".$q . mysql_error());
	            }
	        }
	    }
	    // aggiunge i giocatori nuovi e rimuove quelli vecchi
	    $datogliere = array_diff_key($playersold, $players);  
	    $dainserire = array_diff_key($players,$playersold);
	    foreach($datogliere as $key=>$val)
	    {
	        $q = "UPDATE giocatore 
					SET Club = '' 
					WHERE IdGioc = '".$key."'";
	        mysql_query($q) or die("Query non valida: ".$q . mysql_error());
	    }        
	    foreach($dainserire as $key=>$val)
	    {
	        $pezzi = explode(";",$val);
	        $cognome = ucwords(strtolower((addslashes($pezzi[1]))));
	        $q = "INSERT INTO giocatore(idGioc,cognome,ruolo,club) 
					VALUES ('".$pezzi[0]."','".$cognome."','".$pezzi[2]."','".$pezzi[3]."')";
	        mysql_query($q) or die("Query non valida: ".$q . mysql_error());
	    }
	}
}
?>