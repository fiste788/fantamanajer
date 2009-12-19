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
		$q = "SELECT idGioc, cognome, nome, ruolo, idUtente
				FROM giocatorisquadra
				WHERE idUtente = '" . $idUtente . "'
				ORDER BY idGioc ASC";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$giocatori = array();
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
			$giocatori[$row->idGioc] = $row;
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
	
	function getGiocatoriByIdClub($idClub)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo
				FROM giocatore
				WHERE club = '" . $idClub . "'
				ORDER BY giocatore.idGioc ASC";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$giocatori = array();
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
			$giocatori[$row->idGioc] = $row;
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
	
	function getGiocatoriByIdSquadraAndRuolo($idUtente,$ruolo)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, idUtente
				FROM giocatore INNER JOIN squadra ON giocatore.idGioc = squadra.idGioc
				WHERE idUtente = '" . $idUtente . "' AND ruolo = '" . $ruolo . "' AND club IS NOT NULL
				ORDER BY giocatore.idGioc ASC";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$giocatori = array();
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
			$giocatori[] = $row;
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
	
	function getFreePlayer($ruolo,$idLega)
	{				
		$q = "SELECT giocatoristatistiche.*
				FROM giocatoristatistiche
				WHERE idGioc IN (SELECT idGioc
					FROM giocatore
					INNER JOIN club ON giocatore.club = club.idClub
					WHERE ruolo = '" . $ruolo . "'
					AND club <> ''
					AND giocatore.idGioc NOT IN (
						SELECT idGioc
						FROM squadra
						WHERE idLega = '" . $idLega . "'))
				ORDER BY cognome,nome";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
			$giocatori[$row->idGioc] = $row;
		return $giocatori;
	}
	
	function getGiocatoriByArray($giocatori)
	{
		$q = "SELECT idGioc,cognome,nome,ruolo 
				FROM giocatore 
				WHERE idGioc IN ('" . implode("','",$giocatori) . "')
				ORDER BY FIELD(idGioc,'" . implode("','",$giocatori) . "')";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
			$result[] = $row;
		return $result;
	}
	
	function getGiocatoreById($idGioc)
	{
		$q = "SELECT idGioc,cognome,nome,ruolo,nomeClub,partitivo 
				FROM giocatore LEFT JOIN club ON giocatore.club=club.idClub
				WHERE idGioc = '" . $idGioc . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
			$result[$row->idGioc] = $row;
		if(isset($result))
			return $result;
		else
			return FALSE;
	}
	
	function getGiocatoreByIdWithStats($idGioc,$idLega = NULL)
	{
		require_once(INCDIR . 'voto.db.inc.php');
		$votoObj = new voto();
		$q = "SELECT giocatoristatistiche.*,idLega,idUtente
				FROM (SELECT * 
						FROM squadra 
						WHERE idLega='" . $idLega . "') AS squad RIGHT JOIN giocatoristatistiche ON squad.idGioc = giocatoristatistiche.idGioc
				WHERE giocatoristatistiche.idGioc ='" . $idGioc . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$data = $votoObj->getAllVotoByIdGioc($idGioc);
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
			$values['dettaglio'] = $row;
		if(!empty($data))
			$values['dettaglio']->data = $data;
		return $values;
	}
	
	function getVotiGiocatoriByGiornataAndSquadra($giornata,$idUtente)
	{
		$q = "SELECT *
				FROM dettagliogiornata 
				WHERE idGiornata = '" . $giornata . "' AND idUtente = '" . $idUtente . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while ($row = mysql_fetch_object($exe))
			$elenco[] = $row;
		if(isset($elenco))
			return $elenco;
		else
			return FALSE;
	}
	
	function getGiocatoriByIdSquadraWithStats($idUtente)
	{
		$q = "SELECT *
				FROM giocatoristatistiche INNER JOIN squadra on giocatoristatistiche.idGioc = squadra.idGioc
				WHERE idUtente = '" . $idUtente . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
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
		if(DEBUG)
			echo $q . "<br />";
		while ($row = mysql_fetch_object($exe))
			return $row->ruolo;
	}
	
	function getArrayGiocatoriFromDatabase()
	{
		$q = "SELECT giocatore.idGioc, cognome, ruolo, nomeClub  
				FROM giocatore LEFT JOIN club ON giocatore.club = club.idClub WHERE giocatore.club IS NOT NULL";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$giocatori = array();
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
		{
			$row->nomeClub = strtoupper(substr($row->nomeClub,0,3));
			$giocatori[$row->idGioc] = implode(";",$row);
		}
		return $giocatori;
	}

	function updateTabGiocatore($path,$giornata)
	{
		require_once(INCDIR . 'decrypt.inc.php');
		require_once(INCDIR . 'evento.db.inc.php');
		require_once(INCDIR . 'fileSystem.inc.php');
		
		$decryptObj = new decrypt();
		$eventoObj = new evento();
		$fileSystemObj = new fileSystem();
		
		$ruoli = array("P","D","C","A");
		$playersOld = $this->getArrayGiocatoriFromDatabase();
		$players = $fileSystemObj->returnArray($path,";");
		// aggiorna eventuali cambi di club dei Giocatori-> Es.Turbato Tomas  da Juveterranova a Spartak Foligno
		foreach($players as $key=>$details)
		{
			if(array_key_exists($key,$playersOld))
			{
				$clubNew = substr($details[3],1,3);
				$pieces = explode(";",$playersOld[$key]);
				$clubOld = $pieces[3];
				if($clubNew != $clubOld)
					$clubs[$clubNew][] = $key;
			}
		}
		if(isset($clubs))
		{
			mysql_query("START TRANSACTION");
			foreach($clubs as $key => $val)
			{
				$giocatori = join("','",$clubs[$key]);
				$q = "UPDATE giocatore 
						SET club = (SELECT idClub FROM club WHERE nomeClub LIKE '" . $key . "%') 
						WHERE idGioc IN ('" . $giocatori . "')";
				foreach($clubs[$key] as $single)
					$eventoObj->addEvento('7',0,0,$single);
				if(DEBUG)
					echo $q . "<br />";
				mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
			}
		}
		// aggiunge i giocatori nuovi e rimuove quelli vecchi
		$daTogliere = array_diff_key($playersOld, $players);  
		$daInserire = array_diff_key($players,$playersOld);

		// aggiunge nuovi giocatori
		if(count($daInserire) != 0)
		{
			$rowtoinsert = "";
			foreach($daInserire as $key => $pezzi)
			{
				$esprex = "/[A-Z`]*\s?[A-Z`]{2,}/";
				$id = $pezzi[0];
				$nominativo = trim($pezzi[2],'"');
				$club = substr(trim($pezzi[3],'"'),0,3);
				$ruolo = $ruoli[$pezzi[5]];
				preg_match ($esprex,$nominativo,$ass);
				$cognome = $ass[0];
				$nome = trim(substr($nominativo,strlen($cognome)));
				
				$cognome = ucwords(strtolower((addslashes($cognome))));
				$nome = ucwords(strtolower((addslashes($nome))));
				$rowtoinsert .=  "('" .$id. "','" . $cognome . "','" . $nome . "','" . $ruolo . "',(SELECT idClub FROM club WHERE nomeClub LIKE '" . $club . "%')),";
				if(!empty($playersOld))
					$eventoObj->addEvento('5',0,0,$pezzi[0]);
			}
			$q = rtrim("INSERT INTO giocatore(idGioc,cognome,nome,ruolo,club) VALUES " . $rowtoinsert,",");
			if(DEBUG)
				echo $q . "<br />";
			mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		}
		if(count($daTogliere) != 0)
		{
			foreach($daTogliere as $id => $val)
				$eventoObj->addEvento('6',0,0,$id);
			$stringaDaTogliere = join("','",array_keys($daTogliere));
			$q = "UPDATE giocatore 
					SET club = NULL 
					WHERE idGioc IN ('" . $stringaDaTogliere . "')";
			if(DEBUG)
				echo $q . "<br />";
			mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		}
		if(isset($err))
		{
			mysql_query("ROLLBACK");
			return FALSE;
		}
		else
		{
			mysql_query("COMMIT");
			return TRUE;
		}	
	}

	function getGiocatoriNotSquadra($idUtente,$idLega)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, idUtente
				FROM giocatore LEFT JOIN squadra ON giocatore.idGioc = squadra.idGioc
				WHERE idLega = '" . $idLega . "' AND idUtente <> '" . $idUtente . "' OR idUtente IS NULL
				ORDER BY giocatore.idGioc ASC";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
			$giocatori[$row->idGioc] = $row;
		return $giocatori;
	}
	
	function getGiocatoriBySquadraAndGiornata($idUtente,$idGiornata)
	{
		require_once(INCDIR . 'trasferimento.db.inc.php');
		
		$trasferimentoObj = new trasferimento();
		
		$giocatori = $this->getGiocatoriByIdSquadra($idUtente);
		$trasferimenti = $trasferimentoObj->getTrasferimentiByIdSquadra($idUtente,$idGiornata);
		if($trasferimenti != FALSE)
		{
			$sort_arr = array();
			foreach($trasferimenti as $uniqid => $row)
				foreach($row as $key=>$value)
					$sort_arr[$key][$uniqid] = $value;
			array_multisort($sort_arr['idGiornata'] , SORT_DESC , $trasferimenti);
			foreach($trasferimenti as $key => $val)
				foreach($giocatori as $key2=>$val2)
					if($val2->idGioc == $val->idGiocNew)
					{
						$giocOld = $this->getGiocatoreById($val->idGiocOld);
						$giocatori[$key2] = $giocOld[$val->idGiocOld];
					}
			$sort_arr = array();
			foreach($giocatori as $uniqid => $row)
				foreach($row as $key => $value)
					$sort_arr[$key][$uniqid] = $value;
			array_multisort($sort_arr['idGioc'] , SORT_ASC , $giocatori);
		}
		foreach($giocatori as $key => $val)
			$giocatoriByRuolo[$val->ruolo][] = $val;
		return $giocatoriByRuolo;
	}
	
	function getGiocatoriTrasferiti($idUtente)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo
				FROM giocatore INNER JOIN squadra ON giocatore.idGioc = squadra.idGioc
				WHERE idUtente = '" . $idUtente . "' AND (club IS NULL OR club = '')";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
			$giocatori[] = $row;
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
	
	function getAllGiocatori()
	{
		$q = "SELECT * 
				FROM giocatore";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
			$giocatori[] = $row;
		return $giocatori;
	}
	
	function aggiornaGiocatore($id,$cognome,$nome)
	{
		$q = "UPDATE giocatore
				SET cognome = '" . $cognome . "', nome = '" . $nome . "'
				WHERE idGioc = '" . $id . "'";
		if(DEBUG)
			echo $q . "<br />";
		return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
	
	function checkOutLista($idGioc)
	{
		$q = "SELECT club
				FROM giocatore
				WHERE idGioc = '" . $idGioc . "' AND club IS NULL";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
			return TRUE;
		return FALSE;
	}
	
	function getBestPlayerByGiornataAndRuolo($idGiornata,$ruolo)
	{
		$q = "SELECT *
				FROM giocatore INNER JOIN voto ON giocatore.idGioc = voto.idGioc INNER JOIN club ON giocatore.club = club.idClub
				WHERE idGiornata = '" . $idGiornata . "' AND ruolo = '" . $ruolo . "'
				ORDER BY punti DESC , voto DESC
				LIMIT 0 , 5";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
			$values[] = $row;
		return $values;
	}
}
?>
