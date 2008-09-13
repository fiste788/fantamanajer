<?php
class formazione
{
	function getGiocatoriByIdSquadra($id_squadra)
	{  
	  
	  $selez_ruolo="SELECT Cognome,IdGioc,Nome,Ruolo FROM giocatore WHERE IdSquadra='$id_squadra'";
	  $risultato = mysql_query($selez_ruolo);
	  $elencoopzioni = "";
	  while ($riga = mysql_fetch_row($risultato)) 
	    $giocatori[$riga['3']][] = $riga;
	  return $giocatori;
	}
	
	function getFormazioneById($id)
	{
		$q = "SELECT formazioni.IdFormazione,IdSquadra,IdGiornata,IdGioc,IdPosizione,Modulo,C,VC,VVC FROM formazioni INNER JOIN schieramento ON formazioni.IdFormazione=schieramento.IdFormazione WHERE formazioni.IdFormazione='$id' ORDER BY IdPosizione;";
		$exe = mysql_query($q);
		$flag=0;
		while ($row = mysql_fetch_array($exe,MYSQL_ASSOC))
		{
            $elenco[$row['IdPosizione']]=$row['IdGioc'];
            if(!$flag)
            {
                $idformazione=$row['IdFormazione'];
                $idsquadra=$row['IdSquadra'];
                $idgiornata=$row['IdGiornata'];
                $modulo=$row['Modulo'];
                $cap['C']=$row['C'];
                $cap['VC']=$row['VC'];
                $cap['VVC']=$row['VVC'];
                $flag=1;
            }
        }

		if($flag)
		{
            $formazione['Id']=$idformazione;
            $formazione['IdSquadra']=$idsquadra;
            $formazione['IdGiornata']=$idgiornata;
            $formazione['Elenco']=$elenco;
            $formazione['Modulo']=$modulo;
            $formazione['Cap']=$cap;
            return $formazione;
        }			
		else
			return FALSE;
	}
	
	function carica_formazione($formazione,$capitano,$giornata)
	{
        //echo "<pre>".print_r($formazione,1)."</pre>";
        //echo "<pre>".print_r($capitano,1)."</pre>";
        $modulo=$_SESSION['modulo'];
        $campi="";
        $valori="";
        foreach($capitano as $key=>$val)
        {
            $campi.=",$key";
            $valori.=",'$val'";
        }        

        $insert="INSERT INTO formazioni (IdSquadra,IdGiornata,Modulo".$campi.") VALUES (".$_SESSION['idsquadra'].",'$giornata','$modulo'".$valori.")";
        mysql_query($insert) or die("Query non valida: ".$insert . mysql_error());

	  $q = "SELECT idFormazione FROM formazioni WHERE IdSquadra = '" . $_SESSION['idsquadra'] . "' AND IdGiornata ='" . $giornata . "';";
      $exe = mysql_query($q) or die(MYSQL_ERRNO(). $q ." ".MYSQL_ERROR());
		while($row = mysql_fetch_array($exe))
			$id=$row[0];
	    foreach($formazione as $key=>$player)
        {
            $pos=$key+1;
            $query="INSERT INTO schieramento(IdFormazione,IdGioc,IdPosizione) VALUES ('$id','$player','$pos')";
            $exe = mysql_query($query) or die(MYSQL_ERRNO(). $query ." ".MYSQL_ERROR());            
        }
        return $id;
	}
	
	function updateFormazione($formazione,$capitano,$giornata)
	{

      	$modulo=$_SESSION['modulo'];

      	$str="";
        foreach($capitano as $key=>$val)
        {
            $str.=",$key='$val'";
        }        
		$update="UPDATE formazioni SET Modulo='$modulo'".$str." WHERE IdSquadra= '" . $_SESSION['idsquadra'] . "' AND IdGiornata = '" . $giornata . "';";
		mysql_query($update) or die("Query non valida: ".$update . mysql_error());

		$q = "SELECT idFormazione FROM formazioni WHERE IdSquadra = '" . $_SESSION['idsquadra'] . "' AND IdGiornata ='" . $giornata . "';";
	  	$exe = mysql_query($q) or die(MYSQL_ERRNO(). $q ." ".MYSQL_ERROR());
		while($row = mysql_fetch_array($exe))
			$id=$row[0];
		foreach($formazione as $key=>$player)
        {
            $pos=$key+1;
            $query="UPDATE schieramento SET IdFormazione='$id',IdGioc='$player',IdPosizione='$pos' WHERE IdFormazione='$id' AND IdPosizione='$pos'";
            $exe = mysql_query($query) or die(MYSQL_ERRNO(). $query ." ".MYSQL_ERROR());
        }
        return $id;
	}
	
	function getFormazioneBySquadraAndGiornata($idSquadra,$giornata)
	{
		$q = "SELECT formazioni.IdFormazione,IdGioc,IdPosizione,Modulo,C,VC,VVC FROM formazioni INNER JOIN schieramento ON formazioni.IdFormazione=schieramento.IdFormazione WHERE formazioni.IdSquadra='$idSquadra' AND formazioni.IdGiornata = '$giornata' ORDER BY IdPosizione;";
		$exe = mysql_query($q);
		while ($row = mysql_fetch_array($exe,MYSQL_ASSOC))
		{
    	$elenco[$row['IdPosizione']]=$row['IdGioc'];
      $idformazione=$row['IdFormazione'];
			$modulo=$row['Modulo'];
			$cap['C']=$row['C'];
			$cap['VC']=$row['VC'];
			$cap['VVC']=$row['VVC'];
			$flag=1;
    }
    if($flag == 1)
		{
            $formazione['Id']=$idformazione;
            $formazione['Elenco']=$elenco;
            $formazione['Modulo']=$modulo;
            $formazione['Cap']=$cap;
            return $formazione;
        }			
		else
			return FALSE;
	}
	
	function getFormazioneExistByGiornata($giornata)
	{
		$q = "SELECT squadra.IdSquadra,nome FROM formazioni INNER JOIN squadra ON formazioni.IdSquadra = squadra.IdSquadra WHERE idGiornata = '" . $giornata . "'; ";
		$exe = mysql_query($q) or die("Query non valida: ".$q . mysql_error());
		while ($row = mysql_fetch_row($exe))
		{
			$val[$row['0']][] = $row['0'];
			$val[$row['0']][] = $row['1'];
		}
		if (!isset($val))
			return FALSE;
		else
			return $val;
	}
}
?>
