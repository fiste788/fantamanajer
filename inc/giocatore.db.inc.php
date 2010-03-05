<?php 
class Giocatore extends DbTable
{
	var $idGioc;
	var $nome;
	var $cognome;
	var $ruolo;
	var $club;
	
	public static function getGiocatoriByIdSquadra($idUtente)
	{
		$q = "SELECT idGioc, cognome, nome, ruolo, idUtente
				FROM giocatorisquadra
				WHERE idUtente = '" . $idUtente . "'
				ORDER BY ruolo DESC,cognome ASC";
		$exe = mysql_query($q) or self::sqlError($q);
		$giocatori = array();
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$giocatori[$row->idGioc] = $row;
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
	
	public static function getGiocatoriByIdClub($idClub)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo
				FROM giocatore
				WHERE club = '" . $idClub . "'
				ORDER BY giocatore.ruolo DESC,giocatore.cognome ASC";
		$exe = mysql_query($q) or self::sqlError($q);
		$giocatori = array();
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$giocatori[$row->idGioc] = $row;
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
	
	public static function getGiocatoriByIdSquadraAndRuolo($idUtente,$ruolo)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, idUtente
				FROM giocatore INNER JOIN squadra ON giocatore.idGioc = squadra.idGioc
				WHERE idUtente = '" . $idUtente . "' AND ruolo = '" . $ruolo . "' AND club IS NOT NULL
				ORDER BY giocatore.idGioc ASC";
		$exe = mysql_query($q) or self::sqlError($q);
		$giocatori = array();
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$giocatori[] = $row;
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
	
	public static function getFreePlayer($ruolo,$idLega)
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
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$giocatori[$row->idGioc] = $row;
			FB::log($giocatori);
		return $giocatori;
	}
	
	public static function getGiocatoriByArray($giocatori)
	{
		$q = "SELECT idGioc,cognome,nome,ruolo 
				FROM giocatore 
				WHERE idGioc IN ('" . implode("','",$giocatori) . "')
				ORDER BY FIELD(idGioc,'" . implode("','",$giocatori) . "')";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$result[] = $row;
		return $result;
	}
	
	public static function getGiocatoreById($idGioc)
	{
		$q = "SELECT idGioc,cognome,nome,ruolo,nomeClub,partitivo 
				FROM giocatore LEFT JOIN club ON giocatore.club=club.idClub
				WHERE idGioc = '" . $idGioc . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$result[$row->idGioc] = $row;
		if(isset($result))
			return $result;
		else
			return FALSE;
	}
	
	public static function getGiocatoreByIdWithStats($idGioc,$idLega = NULL)
	{
		require_once(INCDIR . 'voto.db.inc.php');
		
		$q = "SELECT giocatoristatistiche.*,idLega,idUtente
				FROM (SELECT * 
						FROM squadra 
						WHERE idLega='" . $idLega . "') AS squad RIGHT JOIN giocatoristatistiche ON squad.idGioc = giocatoristatistiche.idGioc
				WHERE giocatoristatistiche.idGioc ='" . $idGioc . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		$data = Voto::getAllVotoByIdGioc($idGioc);
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values['dettaglio'] = $row;
		if(!empty($data))
			$values['dettaglio']->data = $data;
		return $values;
	}
	
	public static function getVotiGiocatoriByGiornataAndSquadra($giornata,$idUtente)
	{
		$q = "SELECT *
				FROM dettagliogiornata
				WHERE idGiornata = '" . $giornata . "' AND idUtente = '" . $idUtente . "' ORDER BY idPosizione";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		$elenco = FALSE;
		while ($row = mysql_fetch_object($exe,__CLASS__))
			$elenco[] = $row;
		return $elenco;
	}
	
	public static function getGiocatoriByIdSquadraWithStats($idUtente)
	{
		$q = "SELECT *
				FROM giocatoristatistiche INNER JOIN squadra on giocatoristatistiche.idGioc = squadra.idGioc
				WHERE idUtente = '" . $idUtente . "'
				ORDER BY ruolo DESC,cognome ASC";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$giocatori[] = $row;
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
	
	public static function getRuoloByIdGioc($idGioc)
	{
		$q="SELECT ruolo 
				FROM giocatore 
				WHERE idGioc = '" . $idGioc . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while ($row = mysql_fetch_object($exe,__CLASS__))
			return $row->ruolo;
	}
	
	public static function getArrayGiocatoriFromDatabase()
	{
		$q = "SELECT giocatore.*, nomeClub  
				FROM giocatore LEFT JOIN club ON giocatore.club = club.idClub WHERE giocatore.club IS NOT NULL";
		$exe = mysql_query($q) or self::sqlError($q);
		$giocatori = array();
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
		{
			$row->nomeClub = strtoupper(substr($row->nomeClub,0,3));
			$giocatori[$row->idGioc] = implode(";",get_object_vars($row));
		}
		return $giocatori;
	}

	public static function updateTabGiocatore($path,$giornata)
	{
		require_once(INCDIR . 'decrypt.inc.php');
		require_once(INCDIR . 'evento.db.inc.php');
		require_once(INCDIR . 'fileSystem.inc.php');
		
		$ruoli = array("P","D","C","A");
		$playersOld = self::getArrayGiocatoriFromDatabase();
		
		$players = fileSystem::returnArray($path,";");
		FB::info($path);
		FB::info($players);
		FB::info($playersOld);
		// aggiorna eventuali cambi di club dei Giocatori-> Es.Turbato Tomas  da Juveterranova a Spartak Foligno
		foreach($players as $key=>$details)
		{
			if(array_key_exists($key,$playersOld))
			{
				$clubNew = substr($details[3],1,3);
				$pieces = explode(";",$playersOld[$key]);
				$clubOld = $pieces[5];
				if($clubNew != $clubOld)
					$clubs[$clubNew][] = $key;
			}
		}
		if(isset($clubs))
		{
			self::startTransaction();
			foreach($clubs as $key => $val)
			{
				$giocatori = join("','",$clubs[$key]);
				$q = "UPDATE giocatore 
						SET club = (SELECT idClub FROM club WHERE nomeClub LIKE '" . $key . "%') 
						WHERE idGioc IN ('" . $giocatori . "')";
				foreach($clubs[$key] as $single)
					Evento::addEvento('7',0,0,$single);
				if(DEBUG)
					FB::log($q);
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
					Evento::addEvento('5',0,0,$pezzi[0]);
			}
			$q = rtrim("INSERT INTO giocatore(idGioc,cognome,nome,ruolo,club) VALUES " . $rowtoinsert,",");
			if(DEBUG)
				FB::log($q);
			mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		}
		if(count($daTogliere) != 0)
		{
			foreach($daTogliere as $id => $val)
				Evento::addEvento('6',0,0,$id);
			$stringaDaTogliere = join("','",array_keys($daTogliere));
			$q = "UPDATE giocatore 
					SET club = NULL 
					WHERE idGioc IN ('" . $stringaDaTogliere . "')";
			if(DEBUG)
				FB::log($q);
			mysql_query($q) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q;
		}
		if(isset($err))
		{
			self::rollback();
			return FALSE;
		}
		else
		{
			self::commit();
			return TRUE;
		}	
	}

	public static function getGiocatoriNotSquadra($idUtente,$idLega)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo, idUtente
				FROM giocatore LEFT JOIN squadra ON giocatore.idGioc = squadra.idGioc
				WHERE idLega = '" . $idLega . "' AND idUtente <> '" . $idUtente . "' OR idUtente IS NULL
				ORDER BY giocatore.idGioc ASC";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$giocatori[$row->idGioc] = $row;
		return $giocatori;
	}
	
	public static function getGiocatoriBySquadraAndGiornata($idUtente,$idGiornata)
	{
		require_once(INCDIR . 'trasferimento.db.inc.php');
		
		$giocatori = self::getGiocatoriByIdSquadra($idUtente);
		$trasferimenti = Trasferimento::getTrasferimentiByIdSquadra($idUtente,$idGiornata);
		FB::info($trasferimenti);
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
						$giocOld = self::getGiocatoreById($val->idGiocOld);
						$giocatori[$key2] = $giocOld[$val->idGiocOld];
					}
			$sort_arr = array();
			foreach($giocatori as $uniqid => $row)
				foreach($row as $key => $value)
					$sort_arr[$key][$uniqid] = $value;
			array_multisort($sort_arr['cognome'] , SORT_ASC , $giocatori);
		}
		$giocatoriByRuolo = array();
		foreach($giocatori as $key => $val)
			$giocatoriByRuolo[$val->ruolo][] = $val;
		return $giocatoriByRuolo;
	}
	
	public static function getGiocatoriTrasferiti($idUtente)
	{
		$q = "SELECT giocatore.idGioc, cognome, nome, ruolo
				FROM giocatore INNER JOIN squadra ON giocatore.idGioc = squadra.idGioc
				WHERE idUtente = '" . $idUtente . "' AND (club IS NULL OR club = '')";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$giocatori[] = $row;
		if(isset($giocatori))
			return $giocatori;
		else
			return FALSE;
	}
	
	public static function getAllGiocatori()
	{
		$q = "SELECT * 
				FROM giocatore";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$giocatori[] = $row;
		return $giocatori;
	}
	
	public static function aggiornaGiocatore($id,$cognome,$nome)
	{
		$q = "UPDATE giocatore
				SET cognome = '" . $cognome . "', nome = '" . $nome . "'
				WHERE idGioc = '" . $id . "'";
		if(DEBUG)
			FB::log($q);
		return mysql_query($q) or self::sqlError($q);
	}
	
	public static function checkOutLista($idGioc)
	{
		$q = "SELECT club
				FROM giocatore
				WHERE idGioc = '" . $idGioc . "' AND club IS NULL";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			return TRUE;
		return FALSE;
	}
	
	public static function getBestPlayerByGiornataAndRuolo($idGiornata,$ruolo)
	{
		$values = FALSE;
		$q = "SELECT *
				FROM giocatore INNER JOIN voto ON giocatore.idGioc = voto.idGioc INNER JOIN club ON giocatore.club = club.idClub
				WHERE idGiornata = '" . $idGiornata . "' AND ruolo = '" . $ruolo . "'
				ORDER BY punti DESC , voto DESC
				LIMIT 0 , 5";
		$exe = mysql_query($q) or self::sqlError($q);
		if(DEBUG)
			FB::log($q);
		while($row = mysql_fetch_object($exe,__CLASS__))
			$values[] = $row;
		return $values;
	}
}
?>
