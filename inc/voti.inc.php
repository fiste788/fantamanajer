<?php 
class voti
{		
	function getVotoByIdGioc($idGioc,$giornata)
	{
		$q = "SELECT voto 
				FROM voti 
				WHERE idGioc = '".$idGioc."' AND idGiornata = '".$giornata."'";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		while ($row = mysql_fetch_row($exe))
			return($row[0]);
	}

	function getPresenzaByIdGioc($idGioc,$giornata)
	{
		$i = 0;
		$q = "SELECT voto,votoUff 
				FROM voti WHERE idGioc='$idGioc' AND idGiornata='".$giornata."'";
		$exe = mysql_query($select) or die("Query non valida: ".$q. mysql_error());
		while ($row = mysql_fetch_array($exe,MYSQL_ASSOC))
		{
			$i++;
			if($row['votoUff']==0 and $row['voto']==0)
				return 0;
		}
		if(!$i)
			return 0;
	    else
			return 1;
	}

	function getMedieVoto($idGioc)
	{
		$q = "SELECT AVG(voto) as mediaPunti,AVG(votoUff) as mediaVoti,count(voto) as Presenze 
				FROM voti 
				WHERE idGioc='".$idGioc."' AND votoUff <> 0 AND voto <> 0 GROUP by idGioc";
		$exe = mysql_query($q) or die(MYSQL_ERRNO(). $q ." ".MYSQL_ERROR());
		while($row = mysql_fetch_array($exe,MYSQL_ASSOC))
			return $row;
	}
	
	function recuperaVoti($giorn)
	{
		require_once(INCDIR.'fileSystem.inc.php');
		$fileSystemObj = new fileSystem();
		$percorso = "./docs/voti/Giornata".$giorn.".csv";
		$fileSystemObj->scaricaVotiCsv($percorso);	// crea il .csv con i voti
		// inserisce i voti di giornata nel db
		foreach (file($percorso) as $player)
		{
			$pezzi = explode(";",$player);
			if($pezzi[3] == "-")
			$presenza = 0;
			else
			$presenza = 1;
			if($pezzi[2] == "P")
			$pezzi[6] = -$pezzi[6];
			$q = "INSERT INTO voti(idGioc,idGiornata,votoUff,voto,gol,assist) 
					VALUES ('".$pezzi[0]."','".$giorn."','".$pezzi[3]."','".$pezzi[4]."','".$pezzi[6]."','".$pezzi[7]."')";
		mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		}
	}
}
?>
