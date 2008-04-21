<?php
class punteggi
{
	function checkPunteggi($giornata)
	{
		$select="SELECT * FROM punteggi WHERE IdGiornata='$giornata'";
		$ris=mysql_query($select) or die("Query non valida: ".$select . mysql_error());
		if(mysql_num_rows($ris)>0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	function getClassifica()
	{
		//L'array deve essere strutturato come quì sotto
		$q="SELECT squadra.IdSquadra,Nome,SUM(punteggio) as punteggioTot,AVG(punteggio) as punteggioMed, MAX(punteggio) as punteggioMax, MIN(punteggio) as punteggioMin FROM `punteggi` INNER JOIN squadra on punteggi.IdSquadra = squadra.IdSquadra GROUP BY IdSquadra ORDER BY punteggioTot DESC;";
		$exe=mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		while ($row = mysql_fetch_array($exe))
			$classifica[] = $row;
		return($classifica);
	}

	function getAllPunteggi()
	{
		$q = "SELECT squadra.IdSquadra, IdGiornata,Nome, punteggio FROM punteggi INNER JOIN squadra ON punteggi.IdSquadra = squadra.IdSquadra;";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		$i=0;
		while ($row = mysql_fetch_array($exe))
		{
			$classifica[$row['IdSquadra']] [$row['IdGiornata']]= $row['punteggio'];
			$somme[$row['IdSquadra']] = array_sum($classifica[$row['IdSquadra']]);
		}
		arsort($somme);
		$appo = array_keys($somme);
		for($i = 0; $i < count($classifica); $i++)
		{
			for($j = 0 ; $j < count($classifica [$appo[$i]]) ; $j++)
			{
	      	$classificaokay[$appo[$i]][$j+1] = $classifica[$appo[$i]] [$j+1];
	      }
		}
	    return($classificaokay);
	}
	
	function getPosClassifica($classifica) //come classifica si intende la variabile uscita dalla funzione getClassifica
	{
		$pos = array();
		$i=1;
		foreach($classifica as $key => $val)
		{
			$pos[$val[0]] = $i;
			$i++;
		}
		return $pos;
	}
	
	function getAllPunteggiByGiornata($giornata)
	{
		$q = "SELECT squadra.IdSquadra, IdGiornata,Nome, punteggio FROM punteggi INNER JOIN squadra ON punteggi.IdSquadra = squadra.IdSquadra WHERE idGiornata <= " . $giornata . ";";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		$i=0;
		while ($row = mysql_fetch_array($exe))
		{
			$classifica[$row['IdSquadra']] [$row['IdGiornata']]= $row['punteggio'];
			$somme[$row['IdSquadra']] = array_sum($classifica[$row['IdSquadra']]);
		}
		arsort($somme);
		$appo = array_keys($somme);
		for($i = 0; $i < count($classifica); $i++)
		{
			for($j = 0 ; $j < count($classifica [$appo[$i]]) ; $j++)
			{
	      	$classificaokay[$appo[$i]][$j+1] = $classifica[$appo[$i]] [$j+1];
	      }
		}
	    return($classificaokay);
	}
	
	function getGiornateWithPunt()
	{
		$q = " SELECT COUNT(DISTINCT(IdGiornata)) FROM punteggi;";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		while ($row = mysql_fetch_row($exe))
			return($row[0]);
	}
      
	function recVoto($id , $giornata)
	{
		$pattern = ";";
		$query = "SELECT Voti FROM giocatore WHERE IdGioc='$id'";
		$risu = mysql_query($query) or die("Query non valida: ".$risu . mysql_error());
		$riga = mysql_fetch_array($risu, MYSQL_NUM) or die("Query non valida: ".$query . mysql_error());;
		$voti = explode($pattern,$riga[0]);
		$voto = $voti[$giornata - 1];  
		return $voto;
	}
	
	function verificaVoto($voto)
	{
		$novoto = "-";  
		if($voto != $novoto)
			return true;
		else
			return false;
	}
	
	function calcolaPunti($giornata , $idsquadra , $carica)
	{
		$pattern = ";";
		$cambi = 0;
		$prn_cap = "-";
		$ruolo_prec = "por";
		$cap = "";
		
		// ricavo la formazione relativa
		$query = "SELECT Elenco FROM formazioni WHERE IdGiornata='$giornata' AND IdSquadra='$idsquadra'";
		$risu = mysql_query($query) or die("Query non valida: ".$query . mysql_error());
		$riga = mysql_fetch_array($risu, MYSQL_NUM) or die("Query non valida: ".$query . mysql_error());;
		$formaz = explode("!",$riga[0]);
		
		// ottengo i titolari
		$tito = explode($pattern,array_shift($formaz));
		
		// ottengo i panchinari
		$panch = explode($pattern,array_shift($formaz));
		$el_voti = array();
		
		// ciclo ogni giocatore titolare
		foreach ($tito as $player_dae)
		{
			$pieces = explode($prn_cap,$player_dae);
			$player = $pieces[0];
			
			// recupero il voto del giocatore 
			$voto = $this->recVoto($player,$giornata);
			
			// trovo i cap,v-cap e vv-cap
			if(count($pieces) > 1)	//Mia aggiunta
			{	
				$cap = $pieces[1];
				// se trovo li sbatto dentro così: $array["C"]->idgioc  $array["VC"]->idgioc    
				$el_cap[$cap] = $player;
			}
			// se il gioc ha preso voto lo sbatto dentro così: $array[idgioc]->voto    
			if($this->verificaVoto($voto))
				$el_voti[$player] = $voto;
			// se non ha preso voto    
			elseif($cambi < 3)
			{
				$el_voti[$player] = '';
				// ottengo il ruolo del gioc che nn ha giocato
				$q_ruolo = "SELECT ruolo FROM giocatore WHERE IdGioc='$player'";
				$risu = mysql_query($q_ruolo) or die("Query non valida: ".$q_ruolo. mysql_error());
				$r = array_shift(mysql_fetch_array($risu, MYSQL_NUM)) or die("Query non valida: ".$q_ruolo . mysql_error());;
				 
				// cerco fra tutti i panchinari i giocatori con lo stesso ruolo es. CRESPO SV(CERCO TUTTI GLI ATTACCANTI IN PANCA)
				$conc = substr(join(",",$panch),1);
				if($ruolo_prec != $r)
			      {
					$ruolo_prec = $r;
					$q_sost = "SELECT IdGioc FROM giocatore WHERE IdGioc IN (" . $conc . ") AND Ruolo='" . $r . "'";
					$esito = mysql_query($q_sost) or die("Query non valida: ".$q_sost . mysql_error());
					$sost = array();
					
					// METTO IN $sost TUTTI QUELLI I PANCH CON LO STESSO RUOLO   
					while ($riga = mysql_fetch_array($esito, MYSQL_NUM))
					{
						$a = strpos($conc, $riga[0]);
						$sost[$a] = $riga[0];    
					}
					ksort($sost);
		     		}		
				// E LI ORDINO COME NELLA FORMAZIONE     
				foreach ($sost as $key=>$id_sost)
				{
					$voto_s = $this->recVoto($id_sost,$giornata);
					
					// SE IL 1° PANCHINARO PRESO VOTO ESCO SENNò CONTINUO A CERCARE   
					if($this->verificaVoto($voto_s))
					{						
						$el_voti[$id_sost.'-panch'] = $voto_s;
						unset($sost[$key]);
						$cambi++;
						break;
					}
				}     			
			}
		}

    	if(isset($el_cap))
    	{
			ksort($el_cap);
			// RADDOPPIO I PUNTI DEL CAP  
			foreach ($el_cap as $key=>$value)
			{			
				if(array_key_exists($value,$el_voti) && $el_voti[$value] != '')
				{
					$el_voti[$value.'-cap'] = $el_voti[$value]*2;
					unset($el_voti[$value]);
					break;
				}
			}
		}
		
		//INSERISCO NELL'ARRAY ANCHE I PANCHINARI CHE NON SONO ENTRATI
		foreach($panch as $key=>$val)
		{
			if(!isset($el_voti[$val.'-panch']) && $key != 0)
				$el_voti[$val.'-panch'] = '';
		}
		if($carica)	//CONTROLLO SE SONO DA CARICARE
		{
			// SOMMO I PUNTI DI QLL KE HAN GIOCATO  
			$somma = array_sum($el_voti);
			$ins_somma = "INSERT INTO punteggi(IdGiornata,IdSquadra,Punteggio) VALUES ('$giornata','$idsquadra','$somma')";
			mysql_query($ins_somma) or die("Query non valida: ".$ins_somma . mysql_error());
		}
		return $el_voti;
	}	
}

//QUESTE FUNZIONI SONO ESCLUSE DALLA CLASSE PER UN BUG DA CORREGGERE

function trim_value(&$value)
{
	$value = trim($value);
}

function contenuto_via_curl($url)
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

function scarica_voti()
{
	$array = array("portieri","difensori","centrocampisti","attaccanti");
	$tabella_voti = ""; 
	foreach ($array as $ruolo)
	{
		$link = "http://magiccup.gazzetta.it/statiche/campionato/2008/statistiche/media_voto_".$ruolo."_nomegazz.shtml";
		$contenuto = contenuto_via_curl($link);
		$tabxruolo = "<tr ".strstr($contenuto,"RIGA1");
		$s = explode("</table",$tabxruolo);
		$tabxruolo = $s[0];
		$handle = fopen("./docs/b.txt", "w");  
		fwrite($handle,$tabxruolo); 
		fclose($handle);
		$tabella_voti .= $tabxruolo;
	}
	return $tabella_voti;
}

function remove_voti_giornata($voti,$id)
{
  print "$id -> $voti<br>";
  $pieces=explode(";",$voti);
  $pieces=array_slice($pieces,0,-2);
  $voti=join(";",$pieces).";";
  print "$id votazzi:$voti<br>";
  $update = "UPDATE giocatore SET Voti='$voti' WHERE IdGioc='$id'";  
	mysql_query($update) or die("Query non valida: ".$update . mysql_error());
}

function recupera_voti($giorn)
{
	$percorso = "docs/voti/Giornata".$giorn.".csv";
	if(file_exists($percorso))
		unlink($percorso);
	$sep_voti = ";";
	$novoto = "-";
	$voti = scarica_voti();
	$espr = "<tr";
	$keywords = explode($espr, $voti);
	array_shift($keywords);
	$handle = fopen($percorso, "a");
	foreach ($keywords as $player)
	{
		$espre = "/(<[^<>]+>)+/";
		$player = preg_replace($espre,"\t",$player);
		$pieces = explode("\t",$player);
		$voto = $pieces[23];
		$voto = ereg_replace(',','.',$voto);
		$id = $pieces[1];
		$cognome = $pieces[3];
		$club = substr($pieces[5],0,3);
		$azzo = "$id\t$cognome\t$voto\t$club\n";
		fwrite($handle,"$id;$cognome;$pieces[23]\n");
		$select = "SELECT Voti FROM giocatore WHERE IdGioc='$id'";
		$sel_gioc = mysql_query($select)or die("Query non valida: ".$select . mysql_error());
		$votisc = mysql_fetch_array($sel_gioc);
		if(is_array($votisc))
		{
			$votiold = array_shift($votisc);
			
/*->da togliere->			$votiold=remove_voti_giornata($votiold,$id);*/
		  $update = "UPDATE giocatore SET Voti='$votiold$voto$sep_voti' WHERE IdGioc='$id'";
			/*----->>>>   DA SCOMMENTARE QUANDO SI ESEGUE CON IL CRON <<<<<<<<<<<<<<------- */  
			mysql_query($update) or die("Query non valida: ".$update . mysql_error());
		}
		else
		{
			$newvoto = "";
			for($i = 1 ; $i < $giorn ; $i++)
				$newvoto .= $novoto.$sep_voti;
			$newvoto .= $voto.$sep_voti;
			$cognome=addslashes($cognome);
			$insert = "INSERT INTO giocatore(IdGioc,Cognome,Club,Voti) VALUES('$id','$cognome','$club','$newvoto')";
			mysql_query($insert) or die("Query non valida: ".$insert . mysql_error());
		}
	}
	fclose($handle);
}	
?>
