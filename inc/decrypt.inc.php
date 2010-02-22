<?php 
class decrypt
{
/*campi file .rcs
	0	cod
	1	bo?
	2	nomecognome
	3	club
	4	1=attivo;0=passivo
	5	ruolo(0=PORTIERE,1=DIFENSORE,2=CENTROCAMPISTA,3=ATTACCANTE)
	6	1=valutato;0=non valutato
	7	punti
	8	bo
	9	1=valutato,0=senzavoto
	10	voto
	11	gol
	12	gol subiti
	13  gol vittoria
	14  gol pareggio
	15	assist
	16	ammonizione
	17	espulsione
	18  rigori calciati
	19  rigori subiti
	20  boh
	23	presenza
	24	titolare
	27	costo
	
*/
	function decryptCdfile($giornata)
	{
		require_once(INCDIR . 'fileSystem.inc.php');
		$fileSystemObj = new fileSystem();
		
		$percorso = VOTIDIR . "Giornata" . str_pad($giornata,2,"0",STR_PAD_LEFT) . ".csv";
		$percorsoContent = file_get_contents($percorso);
		if (file_exists($percorso) && !empty($percorsoContent))
			return $percorso;
		$site = "http://magic.gazzetta.it";
		$content = $fileSystemObj->contenutoCurl($site . "/magiccampionato/09-10/free/download/cd/?s=26f93e16fd6fc65929fd435a0cf17e372f23a6b422");
		if(!empty($content))
		{
			$search = "";
			$content = preg_replace("/\n/","",$content);
			preg_match("/Giornata $giornata(.*?)<a href=\"(.+?)\"/i",$content,$matches);
			if(strpos($matches[2],$site) === FALSE)
				$url = ($site . $matches[2]);
			else
				$url = $matches[2];
			
			$decrypt = "2A 68 6C 34 35 6A 6E 31 32 64 66 67 46 46 44 52 38 73 78 63 33 33 64 65 72 66 76 2A";
			$explode_xor = explode(" ", $decrypt);
			$i = 0;
	
			$scriviFile = fopen($percorso,"w");
			$stringa = "";
			FB::log($url);
			if (!$p_file = fopen($url,"r"))
				return FALSE;
			else
			{
				while(!feof($p_file))
				{
					if ($i == 28)
						$i = 0;
					$linea = fgets($p_file, 2);
					$xor2 = hexdec(bin2hex($linea)) ^ hexdec($explode_xor[$i]);
					$i++;
					
					$stringa .= chr($xor2);
				}
				FB::log($stringa);
				$pezzi = explode("\n",$stringa);
				array_pop($pezzi);
				foreach($pezzi as $key=>$val)
				{
					$pieces = explode("|",$val);
					$pezzi[$key] = join(";",$pieces);
					if($pieces[4] == 0) 
						unset($pezzi[$key]);
				}
				fwrite($scriviFile,join("\n",$pezzi));
				fclose($scriviFile);
				fclose($p_file);
			}
			$fileContent = trim(file_get_contents($percorso));
			if(!empty($fileContent))
				return $percorso;
			else
				return FALSE;
		}
		else
			return FALSE;
	}
}
?>
