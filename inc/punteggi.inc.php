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
    
    function getPunteggi($idsquadra,$idgiornata)
    {
        $query="SELECT punteggio FROM punteggi WHERE IdSquadra='$idsquadra' AND IdGiornata='$idgiornata'";
		$exe=mysql_query($query) or die("Query non valida: ".$query . mysql_error());
		while ($row = mysql_fetch_array($exe))
			return $row[0];       
    }
    
	function getClassifica()
	{
		//L'array deve essere strutturato come quì sotto
		$q="SELECT squadra.IdSquadra,Nome,SUM(punteggio) as punteggioTot,AVG(punteggio) as punteggioMed, MAX(punteggio) as punteggioMax, MIN(punteggio) as punteggioMin FROM `punteggi` INNER JOIN squadra on punteggi.IdSquadra = squadra.IdSquadra GROUP BY IdSquadra ORDER BY punteggioTot DESC;";
		$exe=mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		while ($row = mysql_fetch_array($exe))
			$classifica[] = $row;
		if(isset($classifica))
			return($classifica);
		else
		{
			$q="SELECT IdSquadra, nome FROM squadra";
			$exe=mysql_query($q) or die("Query non valida: ".$q . mysql_error());
			while ($row = mysql_fetch_array($exe) )
			{
				$row['punteggioTot'] = 0;
				$row['punteggioMed'] = 0;
				$row['punteggioMax'] = 0;
				$row['punteggioMin'] = 0;
				$classifica[] = $row;
			}
			return $classifica;
		}
	}

	function getAllPunteggi()
	{
		$q = "SELECT squadra.IdSquadra, IdGiornata,Nome, punteggio FROM punteggi INNER JOIN squadra ON punteggi.IdSquadra = squadra.IdSquadra;";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		$i=0;
		while ($row = mysql_fetch_array($exe))
		{
			$classifica[$row['IdSquadra']-1] [$row['IdGiornata']]= $row['punteggio'];
			$somme[$row['IdSquadra']-1] = array_sum($classifica[$row['IdSquadra']-1]);
		}
		if(isset($somme))
		{
			arsort($somme);
			$appo = array_keys($somme);
			for($i = 0; $i < count($classifica); $i++)
			{
				for($j = 1 ; $j <= count($classifica [$appo[$i]]) ; $j++)
				{
			  		$classificaokay[$appo[$i]][$j] = $classifica[$appo[$i]] [$j];
				}
			}
		}
		else
		{
			$q="SELECT nome FROM squadra";
			$exe=mysql_query($q) or die("Query non valida: ".$q . mysql_error());
			while ($row = mysql_fetch_row($exe) )
			{
				$row[] = 0;
				$classificaokay[] = $row;
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
			$classifica[$row['IdSquadra']-1] [$row['IdGiornata']]= $row['punteggio'];
			$somme[$row['IdSquadra']-1] = array_sum($classifica[$row['IdSquadra']-1]);
		}
		if(isset($somme))
		{
			arsort($somme);

			$appo = array_keys($somme);
			for($i = 0; $i < count($classifica); $i++)
			{
				for($j = 1 ; $j <= count($classifica [$appo[$i]]) ; $j++)
				{
			  		$classificaokay[$appo[$i]][$j] = $classifica[$appo[$i]] [$j];
			  	}
			}
		}
		else
		{
			$q="SELECT nome FROM squadra";
			$exe=mysql_query($q) or die("Query non valida: ".$q . mysql_error());
			while ($row = mysql_fetch_row($exe) )
			{
				$classificaokay[][] = 0;
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
    
    function getVotoById($id,$giornata)
    {
        $selectvoto="SELECT Voto FROM voti WHERE IdGioc='$id' AND IdGiornata='$giornata'";
        $risu = mysql_query($selectvoto) or die("Query non valida: ".$selectvoto . mysql_error());
		while ($row = mysql_fetch_row($risu))
			return($row[0]);
    }  
    
    function getPresenzaById($id,$giornata)
    {
        $i=0;
        $select="SELECT Voto,VotoUff FROM voti WHERE IdGioc='$id' AND IdGiornata='$giornata'";
        $risu = mysql_query($select) or die("Query non valida: ".$select. mysql_error());
		while ($row = mysql_fetch_array($risu,MYSQL_ASSOC))
		{
			$i++;
			if($row['VotoUff']==0)
			 return 0;
		}
		if(!$i)
		{
		  return 0;
		}
    else
    {
		  return 1;
    }
    }
    
    function getRuoloById($id)
    {
        $query="SELECT Ruolo FROM giocatore WHERE IdGioc='$id'";
        $exe = mysql_query($query) or die("Query non valida: ".$query . mysql_error());
		while ($row = mysql_fetch_row($exe))
			return($row[0]);      
    }
    
    function recurSost($ruolo,&$panch,&$cambi,$giornata)
    {
        $num=count($panch);
        for($i=0;$i<$num;$i++)
        {
            $player=$panch[$i];
            $presenza=$this->getPresenzabyId($player,$giornata);
            if(($this->getRuoloById($player)==$ruolo)&&($presenza))
            {
                array_splice($panch,$i,1);
                $cambi++;
                return $player;
            }
        }
        return 0;
    }

function setConsiderazione($idform,$idplayer)
{
    $update="UPDATE schieramento SET Considerato=Considerato+1 WHERE IdFormazione='$idform' AND IdGioc='$idplayer'";
    mysql_query($update) or die("Query non valida: ".$update. mysql_error());
    
}
function calcolaPunti($giornata,$idsquadra)
{
    // Se i punti di quella squadra e giornata ci sono già, esce
    if($this->getPunteggi($idsquadra,$giornata))
    {
        return;
    }
    $cambi=0;
    $somma=0;
    $flag=0;
    require_once('formazione.inc.php');
    $formObj = new formazione();
    $form=$formObj->getFormazioneBySquadraAndGiornata($idsquadra,$giornata);
    $idform=$form['Id'];
    $ecap=$form['Cap'];
    // ottengo il capitano che ha preso voto
    foreach($ecap as $cap)
    {
        if($this->getPresenzaById($cap,$giornata))
        { 
            $flag=1;
            break;
        }
    }
    if ($flag!=1)
        $cap="";

    $panch=$form['Elenco'];
    $tito=array_splice($panch,0,11);

    foreach ($tito as $player)
    {

        $presenza=$this->getPresenzaById($player,$giornata);
        if((!$presenza)&&($cambi<3))
        {
            $sostituto=$this->recurSost($this->getRuoloById($player),$panch,$cambi,$giornata);
            if($sostituto!=0)
                $player=$sostituto;
        }
        $this->setConsiderazione($idform,$player);
        $voto=$this->getVotoById($player,$giornata);
        if($player==$cap)
        {
            $voto*=2;
            $this->setConsiderazione($idform,$cap);
        }    
        $somma+=$voto;
    }
    $insert="INSERT INTO punteggi(IdGiornata,IdSquadra,punteggio) VALUES ('$giornata','$idsquadra','$somma');";
    mysql_query($insert) or die("Query non valida: ".$insert. mysql_error());
}
}
//QUESTE FUNZIONI SONO ESCLUSE DALLA CLASSE PER UN BUG DA CORREGGERE
function returnarray($path) 
{
	if(!file_exists($path)) die("File non esistente");
	$content = join('',file($path));
	$players=explode("\n",$content);
	foreach ($players as &$value) 
	{
		$par=explode(";",$value);
		$key=$par[0];
		$keys[]=$key;
	}
	$c = array_combine($keys, $players);
	array_pop($c);
	return $c;
}

function TrimArray($Input){
 
    if (!is_array($Input))
        return trim($Input);
 
    return array_map('TrimArray', $Input);
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

function scarica_voti_csv($percorso)
{
	$sep_voti = ";";
	$novoto = "-";
	$array = array("P"=>"por","D"=>"dif","C"=>"cen","A"=>"att");
	$tabella_voti = array(); 
    $espr = "<tr>";
    if (file_exists($percorso))
        unlink($percorso);
	$handle = fopen($percorso, "a");
	foreach ($array as $keyruolo=>$ruolo)
	{
		$link = "http://magic.gazzetta.it/magiccampionato/08-09/statistiche/stats_gg_".$ruolo.".shtml?s=75caca1787f9f15f1b3e231cb1a21974";
		$contenuto = contenuto_via_curl($link);
		//print htmlspecialchars($contenuto);
		$contenuto = preg_replace("/\n/","",$contenuto);
		preg_match("/(<tr>\s+<td class=\"ar_txtInput\").*<\/table>/",$contenuto,$matches);
		print $matches[0];

    $keywords = explode($espr, $matches[0]);
    array_shift($keywords);


        foreach($keywords as $key)
        {
            $espre = "/(\s*\/?<[^<>]+>)+/";
            $key = preg_replace($espre,"\t",$key); 
            $pieces = explode("\t",$key);
            $pieces=TrimArray($pieces);
            $pieces=array_map("htmlspecialchars",$pieces);
            $pieces[10] = ereg_replace(',','.',$pieces[10]);
            $pieces[4] = ereg_replace(',','.',$pieces[4]);
            echo "<pre>".print_r($pieces,1)."</pre>";
            fwrite($handle,"$pieces[1];$pieces[2];$keyruolo;$pieces[4];$pieces[10];$pieces[3];$pieces[5];$pieces[9];\n");
        }
    }  
    fclose($handle);
}


//lancia il confronto con giornata precedente(esaminando i .csv quindi senza accesso al db) per aggiungere o togliere i giocatori  	
function update_tab_giocatore($percorsoold,$percorso)
{
    $playersold=returnarray($percorsoold);
    $players=returnarray($percorso);
    
// aggiorna eventuali cambi di club dei Giocatori-> Es.Criscito da Juve a Genoa
    foreach($players as $key=>$line)
    {
        if(array_key_exists($key,$playersold))
        {
            $pieces=explode(";",$line);
            $clubnew=$pieces[4];
            $pieces=explode(";",$playersold[$key]);
            $clubold=$pieces[4];
            if($clubnew!=$clubold)
            {
                $updateclub="UPDATE giocatore SET Club='$clubnew' WHERE IdGioc='$key'";
                mysql_query($updateclub) or die("Query non valida: ".$updateclub .        mysql_error());
            }
        }
    }
    
// aggiunge i giocatori nuovi e rimuove quelli vecchi
    $datogliere = array_diff_key($playersold, $players);  
    $dainserire=array_diff_key($players,$playersold);
    foreach($datogliere as $key=>$val)
    {
        $update="UPDATE giocatore SET Club = '' WHERE IdGioc='$key';";
        mysql_query($update) or die("Query non valida: ".$update .        mysql_error());
    }        
    foreach($dainserire as $key=>$val)
    {
        $pezzi=explode(";",$val);
        $cognome=strtoupper(addslashes($pezzi[1]));
        $insert="INSERT INTO giocatore(IdGioc,Cognome,Ruolo,Club) VALUE ('$pezzi[0]','$cognome','$pezzi[2]','$pezzi[4]')";
        mysql_query($insert) or die("Query non valida: ".$insert . mysql_error());
    }            
}

function recupera_voti($giorn)
{
    $percorso = "../docs/voti/Giornata".$giorn.".csv";
        // crea il .csv con i voti
        scarica_voti_csv($percorso);
        
	$percorsoold="../docs/voti/Giornata".($giorn-1).".csv";  
	/*if(file_exists($percorsoold))
        update_tab_giocatore($percorsoold,$percorso);  
    else
        echo "Nessun giocatore aggiunto/tolto !";*/


    // inserisce i voti di giornata nel db
    foreach (file($percorso) as $player)
	{
        $pezzi=explode(";",$player);
        if($pezzi[3]=="-")
            $presenza=0;
        else
            $presenza=1;
        if($pezzi[2]=="P")
            $pezzi[6]=-$pezzi[6];
        $insert="INSERT INTO voti(IdGioc,IdGiornata,VotoUff,Voto,Gol,Assist) VALUES ('$pezzi[0]','$giorn','$pezzi[3]','$pezzi[4]','$pezzi[6]','$pezzi[7]');";
        mysql_query($insert) or die("Query non valida: ".$insert . mysql_error());
    }
}
	
?>
