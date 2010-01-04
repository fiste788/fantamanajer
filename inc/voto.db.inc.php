<?php 
class voto
{
	var $idGioc;
	var $idGiornata;
	var $votoUff;
	var $voto;
	var $gol;
	var $assist;
	
	function getVotoByIdGioc($idGioc,$giornata)
	{
		$q = "SELECT punti 
				FROM voto 
				WHERE idGioc = '" . $idGioc . "' AND idGiornata = '" . $giornata . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while ($row = mysql_fetch_object($exe))
			return $row->punti;
	}
	
	function getAllVotoByIdGioc($idGioc)
	{
		$q = "SELECT * 
				FROM voto 
				WHERE idGioc = '" . $idGioc . "' AND valutato = 1";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		$values=false;
		if(DEBUG)
			echo $q . "<br />";
		while ($row = mysql_fetch_object($exe))
			$values[$row->idGiornata] = $row;
		return $values;
	}
	
	function getPresenzaByIdGioc($idGioc,$giornata)
	{
		$q = "SELECT valutato 
				FROM voto 
				WHERE idGioc='" . $idGioc . "' AND idGiornata='" . $giornata . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while ($row = mysql_fetch_object($exe))
			return $row->valutato;
	}

	function getMedieVoto($idGioc)
	{
		$q = "SELECT AVG(voto) as mediaPunti,AVG(punti) as mediaVoti,count(voto) as presenze 
				FROM voto 
				WHERE idGioc = '" . $idGioc . "' AND punti <> 0 AND voto <> 0 
				GROUP BY idGioc";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
			return $row;
	}
	
	function recuperaVoti($giorn)
	{
		require_once(INCDIR.'fileSystem.inc.php');
		$fileSystemObj = new fileSystem();
		
		$percorso = "./docs/voti/csv/Giornata" . str_pad($giorn,2,"0",STR_PAD_LEFT) . ".csv";
		if($fileSystemObj->scaricaVotiCsv($giorn))
		{
			if($this->checkVotiExist($giorn))
				return TRUE;
			$q = "INSERT INTO voto(idGioc,idGiornata,punti,voto,gol,assist,rigori,ammonizioni,espulsioni) VALUES ";
			$content = file($percorso);
			if($content != FALSE)
			{
				foreach ($content as $player)
				{
					$pezzi = explode(";",$player);
					if($pezzi[2] == "P")
						$pezzi[6] = -$pezzi[6];
					$voti[] = "('" . $pezzi[0] . "','" . $giorn . "','" . $pezzi[4] . "','" . $pezzi[10] . "','" . $pezzi[5] . "','" . $pezzi[9] . "','" . $pezzi[6] . "','" . $pezzi[7] . "','" . $pezzi[8] . "')";
				}
				$q .= implode(',',$voti);
				if(DEBUG)
					echo $q . "<br />";
				return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
			}
			else
				return FALSE;
		}
		else
			return FALSE;
	}
	
	function checkVotiExist($giornata)
	{
		$values = array();
		$q = "SELECT DISTINCT(idGiornata)
				FROM voto";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		if(DEBUG)
			echo $q . "<br />";
		while($row = mysql_fetch_object($exe))
			$values[] = $row->idGiornata;
		return in_array($giornata,$values);
	}
	
	function importVoti($path,$giornata)
	{
		require_once(INCDIR . 'fileSystem.inc.php');
		$fileSystemObj = new fileSystem();
		
		$players = $fileSystemObj->returnArray($path,";");
		$fileSystemObj->writeXmlVotiDecript($players,str_replace("csv","xml",$path));
		foreach($players as $id=>$stats)
		{
			$valutato = $stats[6];	//1=valutato,0=senzavoto
			$punti = $stats[7];
			$voto = $stats[10];
			$gol = $stats[11];
			$golsub = $stats[12];
			$golvit = $stats[13];
			$golpar = $stats[14];
			$assist = $stats[15];
			$ammonizioni = $stats[16];
			$espulsioni = $stats[17];
			$rigorisegn = $stats[18];
			$rigorisub = $stats[19];
			$presenza = $stats[23];
			$titolare = $stats[24];
			$quotazione = $stats[27];
			$rows[] = "('" . $id . "','" . $giornata . "','" . $valutato . "','" . $punti . "','" . $voto . "','" . $gol . "','" . $golsub . "','" . $golvit . "','" . $golpar . "','" . $assist . "','" . $ammonizioni . "','" . $espulsioni . "','" . $rigorisegn . "','" . $rigorisub . "','" . $presenza . "','" . $titolare . "','" . $quotazione . "')";
		}
		$q = "INSERT INTO voto (idGioc,idGiornata,valutato,punti,voto,gol,golSub,golVit,golPar,assist,ammonizioni,espulsioni,rigoriSegn,rigoriSub,presenza,titolare,quotazione) VALUES ";  
		$q .= implode(',',$rows);
		mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
	}
}
?>
