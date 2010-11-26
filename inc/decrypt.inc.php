<?php 
class Decrypt
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
	public static function decryptCdfile($giornata)
	{
		require_once(INCDIR . 'fileSystem.inc.php');
		
		$percorsoCsv = VOTICSVDIR . "Giornata" . str_pad($giornata,2,"0",STR_PAD_LEFT) . ".csv";
		$percorsoXml = VOTIXMLDIR . "Giornata" . str_pad($giornata,2,"0",STR_PAD_LEFT) . ".xml";
		$percorsoContent = (file_exists($percorsoCsv)) ? trim(file_get_contents($percorsoCsv)) : "";
		if (!empty($percorsoContent))
			return $percorsoCsv;
		$site = "http://magic.gazzetta.it";
		$content = FileSystem::contenutoCurl($site . "/magiccampionato/10-11/free/download/cd/?");
		if(!empty($content))
		{
			$search = "";
			$content = preg_replace("/\n/","",$content);
			preg_match("/Giornata $giornata(.*?)<a href=\"(.+?)\"/i",$content,$matches);
			if(isset($matches[2]))
			{
				if(strpos($matches[2],$site) === FALSE)
					$url = $site . htmlspecialchars_decode($matches[2]);
				else
					$url = $matches[2];
				$url = htmlspecialchars_decode($url);
			
				$decrypt = "72 2A 67 66 64 34 56 42 48 34 34 46 46 35 52 38 73 78 2A 63 33 33 66 34 66 45 45 32";
				$explode_xor = explode(" ", $decrypt);
				if (!$p_file = fopen($url,"r"))
					return FALSE;
				else
				{
					$i = 0;
					$stringa = "";
					$votiContent = file_get_contents($url);
					if(!empty($votiContent))
					{
						while(!feof($p_file))
						{
							if ($i == count($explode_xor))
								$i = 0;
							$linea = fgets($p_file, 2);
							$xor2 = hexdec(bin2hex($linea)) ^ hexdec($explode_xor[$i]);
							$i++;
							$stringa .= chr($xor2);
						}
						$scriviFile = fopen($percorsoCsv,"w");
						$pezzi = explode("\n",$stringa);
						array_pop($pezzi);
						foreach($pezzi as $key=>$val)
						{
							$pieces = explode("|",$val);
							$pezziXml[$key] = $pieces;
							$pezzi[$key] = join(";",$pieces);
							if($pieces[4] == 0) 
								unset($pezzi[$key]);
						}
						fwrite($scriviFile,join("\n",$pezzi));
						fclose($scriviFile);
						fclose($p_file);
						FileSystem::writeXmlVotiDecript($pezziXml,$percorsoXml);
					}
				}
			}
			$fileContent = (file_exists($percorsoCsv)) ? trim(file_get_contents($percorsoCsv)) : "";
			if(!empty($fileContent))
				return $percorsoCsv;
			else
				return FALSE;
		}
		else
			return FALSE;
	}
}
?>
