<?php 
class fileSystem
{
	function getDirIntoFolder($folder) 
	{
		$output = array();
		if ($handle = opendir($folder)) 
		{
			while (false !== ($file = readdir($handle)))
			{ 
				if ($file != "." && $file != ".." && $file != ".svn" && is_dir($folder.'/'.$file)) 
					$output[] = $file;
			} 
			closedir($handle); 
			return $output;
		}
		else
		{
			return "La cartella ".$folder." non esiste";
			die;
		}
	}
	
	function getFileIntoFolder($folder) 
	{
		$output = array();
		if ($handle = opendir($folder)) 
		{
			while (false !== ($file = readdir($handle)))
			{ 
				if ($file != "." && $file != ".." && $file != ".svn") 
					$output[] = $file;
			} 
			closedir($handle); 
			return $output;
		}
		else
		{
			return "La cartella ".$folder." non esiste";
			die;
		}
	}
	
	function returnArray($path) 
	{
		if(!file_exists($path)) 
			die("File non esistente");
		$content = join('',file($path));
		$players = explode("\n",$content);
		foreach ($players as &$value) 
		{
			$par = explode(";",$value);
			$players = trim($value);
			$key = $par[0];
			$keys[] = $key;
		}
		$c = array_combine($keys, $players);
		array_pop($c);
		return $c;
	}
	
	function contenutoCurl($url)
	{
		$handler = curl_init();
		curl_setopt($handler, CURLOPT_URL, $url);
		curl_setopt($handler, CURLOPT_HEADER, false);
		ob_start();
		curl_exec($handler);
		curl_close($handler);
		$string = ob_get_contents();
		ob_end_clean();
		return $string;
	}
	
	function scaricaVotiCsv($percorso)
	{
		$array = array("P"=>"por","D"=>"dif","C"=>"cen","A"=>"att");
	    $espr = "<tr>";
	    if (file_exists($percorso))
	        unlink($percorso);
		$handle = fopen($percorso, "a");
		foreach ($array as $keyruolo=>$ruolo)
		{
			$link = "http://magic.gazzetta.it/magiccampionato/08-09/statistiche/stats_gg_" . $ruolo . ".shtml?s=75caca1787f9f15f1b3e231cb1a21974";
			$contenuto = $this->contenutoCurl($link);
			//print htmlspecialchars($contenuto);
			$contenuto = preg_replace("/\n/","",$contenuto);
			preg_match("/(<tr>\s+<td class=\"ar_txtInput\").*<\/table>/",$contenuto,$matches);
			$keywords = explode($espr, $matches[0]);
			array_shift($keywords);
			foreach($keywords as $key)
			{
				$espre = "/(\s*\/?<[^<>]+>)+/";
				$key = preg_replace($espre,"\t",$key); 
				$pieces = explode("\t",$key);
				foreach($pieces as $key => $val)
					$pieces = trim($val);
				$pieces = array_map("htmlspecialchars",$pieces);
				$pieces[10] = ereg_replace(',','.',$pieces[10]);
				$pieces[4] = ereg_replace(',','.',$pieces[4]);
				fwrite($handle,"$pieces[1];$pieces[2];$keyruolo;$pieces[4];$pieces[10];$pieces[3];$pieces[5];$pieces[9];\n");
			}
		}
		fclose($handle);
	}
	
	function scaricaLista($percorso)
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
			//$keywords=array_map("htmlspecialchars",$keywords);
			//echo "<pre>".print_r($keywords,1)."</pre>";
			array_shift($keywords);
			array_shift($keywords);
			foreach($keywords as $key)
			{
				$espre = "/(\s*\/?<[^<>]+>)+/";
				$key = preg_replace($espre,"\t",$key); 
				$pieces = explode("\t",$key);
				foreach($pieces as $key => $val)
					$pieces = trim($val);
				$pieces=array_map("htmlspecialchars",$pieces);
				$pieces[6]=substr($pieces[6],0,3);
				$pieces[2]=ucwords(strtolower($pieces[2]));
				fwrite($handle,"$pieces[1];$pieces[2];$keyruolo;$pieces[6]\n");
			}
		}
		fclose($handle);
	}
	
	/*function TrimArray($Input)
	{
	    if (!is_array($Input))
	        return trim($Input);
	 
	    return array_map('TrimArray', $Input);
	}*/
}
?>