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
		while ($row = mysql_fetch_assoc($exe))
			return($row['punti']);
	}
	
	function getAllVotoByIdGioc($idGioc)
	{
		$q = "SELECT * 
				FROM voto 
				WHERE idGioc = '" . $idGioc . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_assoc($exe))
			$values[$row['idGiornata']] = $row;
		return $values;
	}

	function getPresenzaByIdGioc($idGioc,$giornata)
	{
		$q = "SELECT voto,punti 
				FROM voto 
				WHERE idGioc='" . $idGioc . "' AND idGiornata='" . $giornata . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_assoc($exe))
			if($row['punti'] <> 0 && $row['voto'] <> 0)
				return TRUE;
		return FALSE;
	}

	function getMedieVoto($idGioc)
	{
		$q = "SELECT AVG(voto) as mediaPunti,AVG(punti) as mediaVoti,count(voto) as presenze 
				FROM voto 
				WHERE idGioc = '" . $idGioc . "' AND punti <> 0 AND voto <> 0 
				GROUP BY idGioc";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while($row = mysql_fetch_assoc($exe))
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
		while($row = mysql_fetch_assoc($exe))
			$values[] = $row['idGiornata'];
		return in_array($giornata,$values);
	}
}
?>
