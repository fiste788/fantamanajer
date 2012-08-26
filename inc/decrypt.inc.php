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
	25  votopolitico portiere
	27	costo
	
*/
	// per calcolare la chiave di decrypt...da lanciare manualmente
	public static function calculateKey()
	{
		$pathcript = DOCSDIR . "mcc00.rcs";	//file criptato .rcs
		$pathencript = DOCSDIR . "mcc00.txt";	//file decritato es prima riga 101|0|"ABBIATI Christian"|"MILAN"|1|0|0|0.0|0|0|0.0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|16
		$cript = file_get_contents($pathcript);
		$encript = file_get_contents($pathencript);
		$ris = "";
		for($i = 0;$i < 28;$i++)
		{
			$xor1 = hexdec(bin2hex($cript[$i]));
			$xor2 = hexdec(bin2hex($encript[$i]));
			if($i!=0)
				$ris .= '-';
			$ris .= dechex($xor1 ^ $xor2);
		}
		
		FirePHP::getInstance()->log($ris);
		return $ris;
	}
	public static function decryptCdfile($giornata,$scostamentoGazzetta = 0)
	{
		require_once(INCDIR . 'fileSystem.inc.php');
		require_once(INCDIR . 'phpQuery.inc.php');
		
		//$decrypt=self::calculateKey();die();
		$percorsoCsv = VOTICSVDIR . "Giornata" . str_pad($giornata,2,"0",STR_PAD_LEFT) . ".csv";
		$percorsoXml = VOTIXMLDIR . "Giornata" . str_pad($giornata,2,"0",STR_PAD_LEFT) . ".xml";
		$percorsoContent = (file_exists($percorsoCsv)) ? trim(file_get_contents($percorsoCsv)) : "";
		if (!empty($percorsoContent)&&($giornata != 0))
			return $percorsoCsv;
		$site = "http://magic.gazzetta.it";
		$content = FileSystem::contenutoCurl($site . "/magiccampionato/12-13/free/download/cd/?");
        FirePHP::getInstance()->log("sono qui");
        
		if(!empty($content)) {
            FirePHP::getInstance()->log("content c'è");
			$search = "";
			//$content = preg_replace("/\n/","",$content);
			$giornataGazzetta = ($giornata + $scostamentoGazzetta);
			phpQuery::newDocument($content);
//			echo $content;
			$ul = pq("#elenco_download");
			$li = pq("li:contains(Giornata $giornataGazzetta)",$ul);
			$a = pq("a",$li);
			
			preg_match("/Giornata $giornataGazzetta(.*?)<a (.*?)href=\"(.+?)\"/i",$content,$matches);
			//echo "<pre>" . print_r($matches,1) . "</pre>";
			//die();
			$url = $a->attr("href");
			if($url != "") {
                FirePHP::getInstance()->log("pure qui");
				if(strpos($url,$site) === FALSE)
					$url = $site . $url;
				$url = htmlspecialchars_decode($url);
				$decrypt = "33-34-35-2A-6D-33-34-35-33-34-47-46-44-2A-52-33-32-34-72-66-65-73-64-53-44-46-34-33";
				$decript = "38-38-36-21-6a-36-35-38-39-33-4a-49-4f-50-2b-31-37-39-68-6a-75-79-72-47-54-59-35-34";
				$explode_xor = explode("-", $decrypt);
				if (!$p_file = fopen($url,"r"))
					return FALSE;
				else {
					$i = 0;
					$stringa = "";
					$votiContent = file_get_contents($url);
					if(!empty($votiContent)) {
						while(!feof($p_file)) {
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
						/*<<inserire le descrizio
						die();  */
						foreach($pezzi as $key=>$val) {
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
