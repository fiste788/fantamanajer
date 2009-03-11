<?php 
class voti
{
	var $idGioc;
	var $idGiornata;
	var $votoUff;
	var $voto;
	var $gol;
	var $assist;
	
	function getVotoByIdGioc($idGioc,$giornata)
	{
		$q = "SELECT voto 
				FROM voti 
				WHERE idGioc = '" . $idGioc . "' AND idGiornata = '" . $giornata . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_row($exe))
			return($row[0]);
	}

	function getPresenzaByIdGioc($idGioc,$giornata)
	{
		$q = "SELECT voto,votoUff 
				FROM voti WHERE idGioc='" . $idGioc . "' AND idGiornata='" . $giornata . "'";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while ($row = mysql_fetch_array($exe,MYSQL_ASSOC))
			if($row['votoUff'] <> 0 && $row['voto'] <> 0)
				return TRUE;
		return FALSE;
	}

	function getMedieVoto($idGioc)
	{
		$q = "SELECT AVG(voto) as mediaPunti,AVG(votoUff) as mediaVoti,count(voto) as presenze 
				FROM voti 
				WHERE idGioc = '" . $idGioc . "' AND votoUff <> 0 AND voto <> 0 
				GROUP BY idGioc";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while($row = mysql_fetch_array($exe,MYSQL_ASSOC))
			return $row;
	}
	
	function recuperaVoti($giorn)
	{
		require_once(INCDIR.'fileSystem.inc.php');
		$fileSystemObj = new fileSystem();
		$percorso = "./docs/voti/Giornata" . str_pad($giorn,2,"0",STR_PAD_LEFT) . ".csv";
		if($fileSystemObj->scaricaVotiCsv($giorn))
		{
			if($this->checkVotiExist($giorn))
				return TRUE;
			$q = "INSERT INTO voti(idGioc,idGiornata,votoUff,voto,gol,assist) VALUES ";
			foreach (file($percorso) as $player)
			{
				$pezzi = explode(";",$player);
				if($pezzi[3] == "-")
					$presenza = 0;
				else
					$presenza = 1;
				if($pezzi[2] == "P")
					$pezzi[6] = -$pezzi[6];
				$voti[] = "('" . $pezzi[0] . "','" . $giorn . "','" . $pezzi[3] . "','" . $pezzi[4] . "','" . $pezzi[6] . "','" . $pezzi[7] . "')";
			}
			$q .= implode(',',$voti);
			return mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		}
		else
			return FALSE;
	}
	
	function checkVotiExist($giornata)
	{
		$q = "SELECT DISTINCT(idGiornata)
				FROM voti";
		$exe = mysql_query($q) or die(MYSQL_ERRNO() . " - " . MYSQL_ERROR() . "<br />Query: " . $q);
		while($row = mysql_fetch_array($exe,MYSQL_ASSOC))
			$values[] = $row['idGiornata'];
		return in_array($giornata,$values);
	}
}
?>
