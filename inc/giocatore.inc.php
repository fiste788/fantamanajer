<?php 
class giocatore
{
	var $idGioc;
	var $nome;
	var $cognome;
	var $ruolo;
	var $club;	//è l'id del club nella tabella club
	
	function getGiocatoriByIdSquadra($idUtente)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, idUtente
				FROM giocatore INNER JOIN squadre ON giocatore.idGioc = squadre.idGioc
				WHERE idUtente = '" . $idUtente . "'
				ORDER BY giocatore.idGioc ASC";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$giocatori = array();
		while($row = mysql_fetch_array($exe))
			$giocatori[] = $row;
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
	
	function getGiocatoriByIdSquadraAndRuolo($idUtente,$ruolo)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, idUtente
				FROM giocatore INNER JOIN squadre ON giocatore.idGioc = squadre.idGioc
				WHERE idUtente = '" . $idUtente . "' AND ruolo = '" . $ruolo . "'
				ORDER BY giocatore.idGioc ASC";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$giocatori = array();
		while($row = mysql_fetch_array($exe))
			$giocatori[] = $row;
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
	
	function getFreePlayer($ruolo,$idLega)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, nomeClub,SUM( gol ) as gol,SUM( assist ) as assist,AVG(voto) as mediaPunti, AVG(votoUff) as mediaVoti,count(voto) as presenze
				FROM (giocatore LEFT JOIN voti ON giocatore.IdGioc = voti.idGioc) LEFT JOIN club ON giocatore.club = club.idClub
				WHERE ruolo = '" . $ruolo . "' AND giocatore.idGioc NOT IN (SELECT idGioc 
																			FROM squadre 
																			WHERE idLega = '" . $idLega . "') AND club <> '' AND (votoUff <> 0 OR voto IS NULL) 
				GROUP BY giocatore.idGioc
				ORDER BY cognome";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while($row = mysql_fetch_array($exe,MYSQL_ASSOC))
			$giocatori[$row['idGioc']] = $row;
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
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while($row = mysql_fetch_array($exe))
			$result[] = $row;
		return $result;
	}
	
	function getGiocatoreById($idGioc)
	{
		$q = "SELECT idGioc,cognome,nome,ruolo 
				FROM giocatore 
				WHERE idGioc = '" . $idGioc . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while($row = mysql_fetch_array($exe))
			$result[$row['idGioc']] = $row;
		if(isset($result))
			return $result;
		else
			return FALSE;
	}
	
	function getGiocatoreByIdWithStats($giocatore)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, idUtente, nomeClub, ROUND(AVG( voto ),2) as mediaPunti,ROUND(avg(votoUff),2) as mediaVoti,COUNT( votoUff ) as presenze, SUM( gol ) as gol, SUM( assist ) as assist 
				FROM squadre RIGHT JOIN ((giocatore LEFT JOIN voti ON giocatore.idGioc = voti.idGioc) LEFT JOIN club ON club.idClub = giocatore.club) ON squadre.idGioc = giocatore.idGioc 
				WHERE giocatore.idGioc = '" . $giocatore . "' AND (votoUff <> 0 OR voto IS NULL) 
				GROUP BY giocatore.idGioc";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$q2 = "SELECT idGiornata, voto,votoUff , gol, assist FROM voti  WHERE idGioc = '" . $giocatore . "';";
		$exe2 = mysql_query($q2) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q2);
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
		//echo "<pre>".print_r($values,1)."</pre>";
		return $values;
	}
	
	function getVotiGiocatoriByGiornataAndSquadra($giornata,$idUtente)
	{
		require_once(INCDIR.'voti.inc.php');
		require_once(INCDIR.'formazione.inc.php');
		$votiObj = new voti();
		$formazioneObj = new formazione();
		$formazione = $formazioneObj->getFormazioneBySquadraAndGiornata($idUtente,$giornata);
		$q = "SELECT giocatore.idGioc as gioc, cognome, nome, ruolo, nomeClub, idPosizione, considerato
				FROM schieramento INNER JOIN (giocatore LEFT JOIN club ON giocatore.club = club.idClub) ON schieramento.idGioc = giocatore.idGioc
				WHERE idFormazione = '" . $formazione['id'] . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe,MYSQL_ASSOC))
		{
			$row['voto'] = $votiObj->getVotoByIdGioc($row['gioc'],$giornata);
			$elenco[] = $row;
		}
		if(isset($elenco))
			return $elenco;
		else
			return FALSE;
	}
	
	function getGiocatoriByIdSquadraWithStats($idUtente)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, idUtente, nomeClub, AVG( voto ) as voto,COUNT( votoUff ) as presenze, SUM( gol ) as gol, SUM( assist ) as assist,AVG(votoUff) as votoeff
				FROM squadre INNER JOIN ((giocatore LEFT JOIN voti ON giocatore.idGioc = voti.idGioc) LEFT JOIN club ON club.idClub = giocatore.club) ON squadre.idGioc = giocatore.idGioc
				WHERE idUtente = '" . $idUtente . "' 
				GROUP BY giocatore.idGioc";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while($row = mysql_fetch_array($exe))
			$giocatori[] = $row;
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
	
	function getRuoloByIdGioc($idGioc)
	{
		$q="SELECT ruolo 
				FROM giocatore 
				WHERE idGioc = '" . $idGioc . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe))
			return($row['ruolo']);
	}
	
	function getArrayGiocatoriFromDatabase()
	{
		$q = "SELECT giocatore.idGioc, cognome, ruolo, nomeClub  
				FROM giocatore LEFT JOIN club ON giocatore.club = club.idClub WHERE giocatore.club IS NOT NULL";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while($row = mysql_fetch_row($exe))
		{
			$row[3] = strtoupper(substr($row[3],0,3));
			$giocatori[$row[0]] = implode(";",$row);
		}
		return $giocatori;
	}

	function updateTabGiocatore($giornata)
	{
		require_once(INCDIR.'fileSystem.inc.php');
		require_once(INCDIR.'eventi.inc.php');
		$fileSystemObj = new fileSystem();
		$eventiObj = new eventi();
		$percorso = "./docs/ListaGiornata/ListaGiornata" . ($giornata) . ".csv"; 
		$fileSystemObj->scaricaLista($percorso);  // crea il .csv con la lista aggiornata
		
    $playersOld = $this->getArrayGiocatoriFromDatabase();
		$players = $fileSystemObj->returnArray($percorso);
		
    // aggiorna eventuali cambi di club dei Giocatori-> Es.Turbato Tomas  da Juveterranova a Spartak Foligno
		foreach($players as $key=>$line)
		{
			if(array_key_exists($key,$playersOld))
			{
				$pieces = explode(";",$line);
				$clubNew = $pieces[3];
				$pieces = explode(";",$playersOld[$key]);
				$clubOld = $pieces[3];
				if($clubNew != $clubOld)
					$clubs[$clubNew][] = $key;
			}
		}
    if(isset($clubs))
		{
			foreach($clubs as $key => $val)
			{
				$giocatori = join("','",$clubs[$key]);
				$q = "UPDATE giocatore 
						SET club = (SELECT idClub FROM club WHERE nomeClub LIKE '" . $key . "%') 
						WHERE idGioc IN ('" . $giocatori . "')";
				mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
			}
		}
		// aggiunge i giocatori nuovi e rimuove quelli vecchi
		$daTogliere = array_diff_key($playersOld, $players);  
		$daInserire = array_diff_key($players,$playersOld);
    //toglie giocatori venduti o svincolati
		if(isset($daTogliere))
		{
		  foreach($daTogliere as $id => $val)
		    $eventiObj->addEvento('6',0,0,$id);
      $stringaDaTogliere = join("','",array_keys($daTogliere));
			$q = "UPDATE giocatore 
					SET club = NULL 
					WHERE idGioc IN ('" . $stringaDaTogliere . "')";
      mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		}        
		// aggiunge nuovi giocatori
		foreach($daInserire as $key => $val)
		{
			$pezzi = explode(";",$val);
			$cognome = ucwords(strtolower((addslashes($pezzi[1]))));
			$q = "INSERT INTO giocatore(idGioc,cognome,ruolo,club) 
					VALUES ('" . $pezzi[0] . "','" . $cognome . "','" . $pezzi[2] . "',(SELECT idClub FROM club WHERE nomeClub LIKE '" . $pezzi[3] . "%'))";
			mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
			$eventiObj->addEvento('5',0,0,$pezzi[0]);
		}
	}

	function getGiocatoriNotSquadra($idUtente,$idLega)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, idUtente
				FROM giocatore LEFT JOIN squadre ON giocatore.idGioc = squadre.idGioc
				WHERE idLega = '" . $idLega . "' AND idUtente <> '" . $idUtente . "' OR idUtente IS NULL
				ORDER BY giocatore.idGioc ASC";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while($row = mysql_fetch_array($exe,MYSQL_ASSOC))
			$giocatori[$row['idGioc']] = $row;
		return $giocatori;
	}
	
	function getGiocatoriBySquadraAndGiornata($idUtente,$idGiornata)
	{
		require_once(INCDIR.'trasferimenti.inc.php');
		$trasferimentiObj = new trasferimenti();
		$giocatori = $this->getGiocatoriByIdSquadra($idUtente);
		$trasferimenti = $trasferimentiObj->getTrasferimentiByIdSquadra($idUtente,$idGiornata);
		if($trasferimenti != FALSE)
		{
			$sort_arr = array();
			foreach($trasferimenti as $uniqid => $row)
				foreach($row as $key=>$value)
					$sort_arr[$key][$uniqid] = $value;
			array_multisort($sort_arr['idGiornata'] , SORT_DESC , $trasferimenti);
			foreach($trasferimenti as $key => $val)
				foreach($giocatori as $key2=>$val2)
					if($val2['idGioc'] == $val['idGiocNew'])
					{
						$giocOld = $this->getGiocatoreById($val['idGiocOld']);
						$giocatori[$key2] = $giocOld[$val['idGiocOld']];
					}
			$sort_arr = array();
			foreach($giocatori as $uniqid => $row)
				foreach($row as $key => $value)
					$sort_arr[$key][$uniqid] = $value;
			array_multisort($sort_arr['idGioc'] , SORT_ASC , $giocatori);
		}
		foreach($giocatori as $key => $val)
			$giocatoriByRuolo[$val['ruolo']][] = $val;
		return $giocatoriByRuolo;
	}
	
	function getGiocatoriTrasferiti($idUtente)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo
				FROM giocatore INNER JOIN squadre ON giocatore.idGioc = squadre.idGioc
				WHERE idUtente = '" . $idUtente . "' AND (club IS NULL OR club = '')";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while($row = mysql_fetch_array($exe,MYSQL_ASSOC))
			$giocatori[] = $row;
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
}
//insert into trasferimenti2 (SELECT idTrasf,idgiornate.idGiornata FROM (`eventi` inner join giornate on eventi.data between giornate.dataInizio AND dataFine) inner join trasferimenti on idExternal = idTrasf WHERE tipo = '4')
?>
