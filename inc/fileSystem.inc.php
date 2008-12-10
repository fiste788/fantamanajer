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
		$content = trim(file_get_contents($path));
		$players = explode("\n",$content);
		foreach ($players as $key => $val) 
		{
			$par = explode(";",$val);
			$players = trim($val);
			$playersOk[$par[0]] = $players;
		}
		return $playersOk;
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
	
	function scaricaVotiCsv($giornata)
	{
        $percorso = "./docs/voti/Giornata" . $giornata . ".csv";
		$array = array("P"=>"por","D"=>"dif","C"=>"cen","A"=>"att");
	    $espr = "<tr>";
	    if (file_exists($percorso))
	        unlink($percorso);
		$handle = fopen($percorso, "a");
		foreach ($array as $keyruolo => $ruolo)
		{
			$link = "http://magic.gazzetta.it/magiccampionato/08-09/statistiche/stats_gg_" . $ruolo . ".shtml?s=75caca1787f9f15f1b3e231cb1a21974";
			$contenuto = $this->contenutoCurl($link);
			if(empty($contenuto))
				return FALSE;
			//print htmlspecialchars($contenuto);
			preg_match("/<td.*?artxtTitolino.*?Giornata\s{1}(.+?)<\/span>/",$contenuto,$matches);
			$giornataGazzetta = $matches[1];
			if($giornataGazzetta != $giornata) //si assicura che la giornata che scarichiamo sia uguale a quella scritta sul sito della gazzetta
                return FALSE;
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
					$pieces[$key] = trim($val);
				$pieces = array_map("htmlspecialchars",$pieces);
				$pieces[10] = ereg_replace(',','.',$pieces[10]);
				$pieces[4] = ereg_replace(',','.',$pieces[4]);
				fwrite($handle,"$pieces[1];$pieces[2];$keyruolo;$pieces[4];$pieces[10];$pieces[3];$pieces[5];$pieces[9];\n");
			}
		}
		fclose($handle);
		return TRUE;
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
	
	function getLastBackup()
	{
		$nomeBackup = @file_get_contents("http://www.fantamanajer.it/docs/nomeBackup.txt");
		if(!empty($nomeBackup) && file('http://www.fantamanajer.it/db/' . $nomeBackup) != FALSE)
			return file_get_contents('http://www.fantamanajer.it/db/' . $nomeBackup);
		else
			return FALSE;
	}
}
?>
