<?php 
class fileSystem
{
	static function getDirIntoFolder($folder) 
	{
		$output = array();
		if ($handle = opendir($folder)) 
		{
			while (FALSE !== ($file = readdir($handle)))
			{ 
				if ($file != "." && $file != ".." && $file != ".svn" && is_dir($folder . '/' . $file)) 
					$output[] = $file;
			} 
			closedir($handle); 
			return $output;
		}
		else
		{
			return "La cartella " . $folder . " non esiste";
			die;
		}
	}
	
	static function getFileIntoFolder($folder) 
	{
		$output = array();
		if ($handle = opendir($folder)) 
		{
			while (FALSE !== ($file = readdir($handle)))
			{ 
				if ($file != ".htaccess" && $file != "." && $file != ".." && $file != ".svn") 
					$output[] = $file;
			} 
			closedir($handle); 
			return $output;
		}
		else
		{
			return "La cartella " . $folder . " non esiste";
			die;
		}
	}
	
	static function getFileIntoFolderRecursively($directory, $recursive) 
	{
		$array_items = array();
		if ($handle = opendir($directory)) 
		{
			while (false !== ($file = readdir($handle))) 
			{
				if ($file != "." && $file != ".." && $file != '.svn') 
				{
					if (is_dir($directory . "/" . $file)) 
					{
						if($recursive) 
							$array_items = array_merge($array_items, self::getFileIntoFolderRecursively($directory . "/" . $file, $recursive));
						$file = $directory . "/" . $file;
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					} 
					else
					{
						$file = $directory . "/" . $file;
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					}
				}
			}
			closedir($handle);
		}
		return $array_items;
	}
	
	static function returnArray($path,$sep = ";") 
	{
		if(!file_exists($path)) 
			die("File non esistente");
		$content = trim(file_get_contents($path));
		$players = explode("\n",$content);
		foreach ($players as $key => $val) 
		{
			$par = explode($sep,$val);
			$players = trim($val);
			$playersOk[$par[0]] = $par;
		}
		return $playersOk;
	}
	
	static function contenutoCurl($url)
	{
		$handler = curl_init();
		curl_setopt($handler, CURLOPT_URL, $url);
		curl_setopt($handler, CURLOPT_HEADER, FALSE);
		curl_setopt($handler, CURLOPT_COOKIESESSION, TRUE);
		ob_start();
		curl_exec($handler);
		curl_close($handler);
		$string = ob_get_contents();
		ob_end_clean();
		return $string;
	}
	
	static function scaricaVotiCsv($giornata)
	{
		$nomeFile = str_pad($giornata,2,"0",STR_PAD_LEFT);
		$percorso = VOTICSVDIR . "Giornata" . $nomeFile . ".csv";
		$percorsoXml = VOTIXMLDIR . "Giornata" . $nomeFile . ".xml";
		$array = array("P"=>"por","D"=>"dif","C"=>"cen","A"=>"att");
		if (file_exists($percorso))
			unlink($percorso);
		$handle = fopen($percorso, "a");
		$xmlArray = array();
		foreach ($array as $keyruolo => $ruolo)
		{
			if($keyruolo == "P")
				$link = "http://magic.gazzetta.it/magiccampionato/09-10/free/statistiche/?s=e11ee247de54adfcc262c4c541994c02105e75bf22";
			else
				$link = "http://magic.gazzetta.it/magiccampionato/09-10/free/statistiche/stats_gg_" . $ruolo . ".shtml?s=e11ee247de54adfcc262c4c541994c02105e75bf22";
			$contenuto = $this->contenutoCurl($link);
			if(empty($contenuto))
				return TRUE;
				
			preg_match('#<span class="giornata">Giornata\s(.*?)<\/span>#mis',$contenuto,$matches);
			$giornataGazzetta = $matches[1];
			if($giornataGazzetta != $giornata) //si assicura che la giornata che scarichiamo sia uguale a quella scritta sul sito della gazzetta
				return TRUE;
			$contenuto = preg_replace("/\n/","",$contenuto);
			preg_match('#<div class="freeTable"><table[^>]*>(.*?)</table></div>#mis',$contenuto,$matches);
			preg_match_all('#<tr[^>]*>(.*?)</tr>#mis', $matches[1],$keywords);
			$keywords = $keywords[1];
			unset($keywords[0]);
			print $keywords;
			foreach($keywords as $key)
			{
				preg_match_all("#<td[^>]*>(.*?)</td>#mis",$key,$player);
				$player = array_map("trim",$player[1]);
				$player = array_map("stripslashes",$player);
				$player = array_map("addslashes",$player);
				
				if(!empty($key))
				{
					$player[3] = str_replace(',','.',$player[3]);
					$player[9] = str_replace(',','.',$player[9]);
					$player[10] = $keyruolo;
					$xmlArray[] = $player;
					fwrite($handle,"$player[0];$player[1];$player[2];$keyruolo;$player[3];$player[4];$player[5];$player[6];$player[7];$player[8];$player[9];\n");
				}
			}
		}
		$this->writeXmlVoti($xmlArray,$percorsoXml);
		fclose($handle);
		return TRUE;
	}
	
	static function writeXmlVoti($tree,$percorso) 
	{
		$xml = new XmlWriter();
		$xml->openURI($percorso);
		$xml->startDocument("1.0");
		$xml->startElement("players");
		foreach($tree as $node) 
		{
			$xml->startElement("player");
			$xml->writeElement("id",$node[0]);
			$xml->writeElement("nome",ucwords(strtolower($node[1])));
			$xml->writeElement("club",$node[2]);
			$xml->writeElement("ruolo",$node[10]);
			$xml->writeElement("voto",$node[3]);
			$xml->writeElement("gol",$node[4]);
			$xml->writeElement("rigori",$node[5]);
			$xml->writeElement("ammonizioni",$node[6]);
			$xml->writeElement("esplusioni",$node[7]);
			$xml->writeElement("assist",$node[8]);
			$xml->writeElement("punti",$node[9]);
			$xml->endElement();
		}
		$xml->endDocument();
	}
	
	static function writeXmlVotiDecript($tree,$percorso) 
	{
		$xml = new XmlWriter();
		$ruoli = array("P","D","C","A");
		$xml->openURI($percorso);
		$xml->startDocument("1.0");
		$xml->startElement("players");
		foreach($tree as $id=>$node) 
		{
			$xml->startElement("player");
			$xml->writeElement("id",$id);
			$xml->writeElement("nome",trim($node[2],'"'));
			$xml->writeElement("club",substr(trim($node[3],'"'),0,3));
			$xml->writeElement("ruolo",$ruoli[$node[5]]);
			$xml->writeElement("valutato",$node[6]);	//1=valutato,0=senzavoto
			$xml->writeElement("punti",$node[7]);
			$xml->writeElement("voto",$node[10]);
			$xml->writeElement("gol",$node[11]);
			$xml->writeElement("golSubiti",$node[12]);
			$xml->writeElement("golVittoria",$node[13]);
			$xml->writeElement("golPareggio",$node[14]);
			$xml->writeElement("assist",$node[15]);
			$xml->writeElement("ammonizioni",$node[16]);
			$xml->writeElement("espulsioni",$node[17]);
			$xml->writeElement("rigoriSegnati",$node[18]);
			$xml->writeElement("rigoriSubiti",$node[19]);
			$xml->writeElement("presenza",$node[23]);
			$xml->writeElement("titolare",$node[24]);
			$xml->writeElement("quotazione",$node[27]);
			$xml->endElement();
		}
		$xml->endDocument();
	}
	
	static function scaricaLista($percorso)
	{
		if(file_exists($percorso))
			unlink($percorso);
		$handle = fopen($percorso, "a");
		$array = array("P"=>"portieri","D"=>"difensori","C"=>"centrocampisti","A"=>"attaccanti");
		foreach($array as $keyruolo=>$ruolo)
		{
			$link = "http://www.fantagazzetta.com/quotazioni_" . $ruolo . "_gazzetta_dello_sport.asp";
			$contenuto = $this->contenutoCurl($link);   
			$contenuto = preg_replace("/\n/","",$contenuto);
			preg_match("/<table.*?class=\"statistiche\">\s*(.*?<\/table>)/",$contenuto,$matches);
			$keywords = explode("<tr",$matches[0]);
			array_shift($keywords);
			array_shift($keywords);
			foreach($keywords as $key)
			{
				$espre = "/(\s*\/?<[^<>]+>)+/";
				$key = preg_replace($espre,"\t",$key); 
				$pieces = explode("\t",$key);
				foreach($pieces as $key => $val)
					$pieces[$key] = trim($val);
				$pieces = array_map("htmlspecialchars",$pieces);
				$pieces[6] = substr($pieces[6],0,3);
				$pieces[2] = ucwords(strtolower($pieces[2]));
				fwrite($handle,"$pieces[1];$pieces[2];$keyruolo;$pieces[6]\n");
			}
		}
		fclose($handle);
	}
	
	static function getLastBackup()
	{
		$nomeBackup = @file_get_contents("http://static.fantamanajer.it/docs/nomeBackup.txt");
		if(!empty($nomeBackup) && file('http://administrator:banana@static.fantamanajer.it/db/' . $nomeBackup . '.sql.gz') != FALSE)
			return implode(gzfile('http://administrator:banana@static.fantamanajer.it/db/' . $nomeBackup . '.sql.gz'));
		else
			return FALSE;
	}
}
?>
