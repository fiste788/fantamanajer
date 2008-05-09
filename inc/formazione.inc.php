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
	
	function carica_formazione($formazione,$capitano,$giornata)
	{
	  if(!empty($capitano))
	  {
	    foreach ($capitano as $key=>$value)
	    {
	      $dacerc=substr($key,0,5);
	      $formazione[$dacerc].="-".$value;
	    }
	  }
	   //echo "<pre>".print_r($formazione,1)."</pre>";
	  //echo "<pre>".print_r($capitano,1)."</pre>";
	  $formazz=join(";",array_values($formazione));
      $modulo=$_SESSION['modulo'];
	  $insert="INSERT INTO formazioni (IdSquadra,IdGiornata,Elenco,Modulo) VALUES (".$_SESSION['idsquadra'].",'$giornata','$formazz','$modulo')";
	  mysql_query($insert) or die("Query non valida: ".$insert . mysql_error());
	}
	
	function updateFormazione($formazione,$capitano,$giornata)
	{
		if(!empty($capitano))
		{
			foreach ($capitano as $key=>$value)
			{
				$dacerc=substr($key,0,5);
				$formazione[$dacerc].="-".$value;
			}
		}
		$formazz=join(";",array_values($formazione));
      	$modulo=$_SESSION['modulo'];
		$insert="UPDATE formazioni SET Modulo='$modulo',Elenco =  '" . $formazz . "' WHERE IdSquadra= '" . $_SESSION['idsquadra'] . "' AND IdGiornata = '" . $giornata . "';";
		mysql_query($insert) or die("Query non valida: ".$insert . mysql_error());
	}
	
	function getFormazioneBySquadraAndGiornata($idSquadra,$giornata)
	{
		$q = "SELECT Elenco,Modulo FROM formazioni WHERE IdSquadra='" . $idSquadra . "' AND IdGiornata = '" . $giornata . "';";
		$exe = mysql_query($q);
		$values = mysql_fetch_array($exe,MYSQL_ASSOC);
		if(isset($values))
			return $values;
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
