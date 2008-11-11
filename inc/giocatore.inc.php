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
	
	function getFreePlayer($ruolo)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, nomeClub,SUM( gol ) as gol,SUM( assist ) as assist,AVG(voto) as mediaPunti, AVG(votoUff) as mediaVoti,count(voto) as presenze
				FROM (giocatore LEFT JOIN voti ON giocatore.IdGioc = voti.idGioc) LEFT JOIN club ON giocatore.club = club.idClub
				WHERE ruolo ='" . $ruolo . "' AND giocatore.idGioc NOT IN (SELECT idGioc 
																			FROM squadre 
																			WHERE idLega  = '" . $_SESSION['idLega'] . "') AND club <> '' AND (votoUff <> 0 OR voto IS NULL) 
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
		return $result;
	}
	
	function getGiocatoreByIdWithStats($giocatore)
	{
		$q = "SELECT giocatore.IdGioc, cognome, nome, ruolo, idUtente, nomeClub, AVG( voto ) as mediaPunti,avg(votoUff) as mediaVoti,COUNT( votoUff ) as presenze, SUM( gol ) as gol, SUM( assist ) as assist 
				FROM squadre INNER JOIN ((giocatore LEFT JOIN voti ON giocatore.idGioc = voti.idGioc) LEFT JOIN club ON club.idClub = giocatore.club) ON squadre.idGioc = giocatore.idGioc 
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
		return $values;
	}
	
	function getVotiGiocatoriByGiornataSquadra($giornata,$idUtente)
	{
		require_once(INCDIR.'voti.inc.php');
		$votiObj = new voti();
		$q = "SELECT giocatore.idGioc as gioc, cognome, nome, ruolo, club, idPosizione, considerato
				FROM schieramento
				INNER JOIN giocatore ON schieramento.idGioc = giocatore.idGioc
				WHERE idFormazione = (SELECT idFormazione 
					FROM formazioni 
					WHERE idGiornata = '" . $giornata . "' AND idUtente = '" . $idUtente . "')";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe,MYSQL_ASSOC))
		{
			$row['voto'] = $votiObj->getVotoByIdGioc($row['gioc'],$giornata);
			$elenco[] = $row;
		}
		return($elenco);
	}
	
	function getGiocatoriByIdSquadraWithStats($idUtente)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, idUtente, nomeClub, AVG( voto ) as voto,COUNT( votoUff ) as presenze, SUM( gol ) as gol, SUM( assist ) as assist,AVG(votoUff) as votoeff
				FROM squadre INNER JOIN ((giocatore LEFT JOIN voti ON giocatore.idGioc = voti.idGioc) LEFT JOIN club ON club.idClub = giocatore.club) ON squadre.idGioc = giocatore.idGioc
				WHERE idUtente = '" . $idUtente . "' 
				GROUP BY giocatore.idGioc";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$giocatori = "";
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

	function updateTabGiocatore($giornata)
	{
		require_once(INCDIR.'fileSystem.inc.php');
		$fileSystemObj = new fileSystem();
		
		$percorsoOld = "./docs/ListaGiornata/ListaGiornata" . ($giornata-1) . ".csv";
		if(!file_exists($percorsoOld))
			return;   
		$percorso = "./docs/ListaGiornata/ListaGiornata" . ($giornata) . ".csv";  
		$fileSystemObj->scaricaLista($percorso);  // crea il .csv con la lista aggiornata
		$playersOld = $fileSystemObj->returnArray($percorsoOld);
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
				{
					$q = "UPDATE giocatore 
							SET club = (SELECT idClub FROM club WHERE nomeClub LIKE '%" . $clubNew . "%') 
							WHERE idGioc = '" . $key . "'";
					mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
				}
			}
		}
		// aggiunge i giocatori nuovi e rimuove quelli vecchi
		$daTogliere = array_diff_key($playersOld, $players);  
		$daInserire = array_diff_key($players,$playersOld);
		foreach($daTogliere as $key => $val)
		{
			$q = "UPDATE giocatore 
					SET club = '' 
					WHERE idGioc = '" . $key . "'";
			mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		}        
		foreach($daInserire as $key => $val)
		{
			$pezzi = explode(";",$val);
			$cognome = ucwords(strtolower((addslashes($pezzi[1]))));
			$q = "INSERT INTO giocatore(idGioc,cognome,ruolo,club) 
					VALUES ('" . $pezzi[0] . "','" . $cognome . "','" . $pezzi[2] . "',(SELECT idClub FROM club WHERE nomeClub LIKE '%" . $pezzi[3] . "%'))";
			mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		}
	}

	function getGiocatoriNotSquadra($squadra)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, idUtente
				FROM giocatore LEFT JOIN squadre ON giocatore.idGioc = squadre.idGioc
				WHERE idUtente <> '" . $squadra . "' OR idUtente IS NULL
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
}
//insert into trasferimenti2 (SELECT idTrasf,idgiornate.idGiornata FROM (`eventi` inner join giornate on eventi.data between giornate.dataInizio AND dataFine) inner join trasferimenti on idExternal = idTrasf WHERE tipo = '4')
?>