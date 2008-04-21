<?php


function dividi_cognome()
{
  $query="SELECT Cognome FROM Giocatore";
  $risultato = mysql_query($query);
  $i=8533;
  while ($riga = mysql_fetch_array($risultato, MYSQL_NUM)) 
  {
    //print "riga:$riga[0]<br>";
    //print $riga[0];
    $esprex="/[A-Z`]*\s?[A-Z`]{2,}/";
    preg_match ($esprex,$riga[0],$ass);
    $cognome=$ass[0];
    $nome=trim(substr($riga[0],strlen($cognome)));
    $update="UPDATE giocatore SET Nome='$nome',Cognome='$cognome' WHERE IdGioc='$i'";
    $ap=mysql_query($update) or die("Query non valida: ".$nome . mysql_error());
    $i++;
  } 
}

function mysql_fetch_rowsarr($result, $numass=MYSQL_BOTH) {
  $got=array();
  print "result:$result";
  mysql_data_seek($result, 0);
    while ($row = mysql_fetch_array($result, $numass)) {
        print "cazzo $row";
        array_push($got, $row);
    }
  return $got;
}

function connessione()
{
  mysql_connect("localhost","ingo_fm","banana");
  mysql_select_db("ingo_fm");
  error_reporting(E_ALL ^ E_NOTICE);
}

function importa_giocatori()
{
  $percorso="./Elencocalciatori.txt";
  // copia il contenuto di un file in una stringa
  $handle = fopen($percorso, "r");
  $contenuto = fread($handle, filesize($percorso));
  fclose($handle);

  $patt_calc="-";
  $elenco=trim($elenco);
  $elenco=explode($patt_calc,$contenuto);
  
  foreach ($elenco as $giocatore)
  {
    $i++;
    $giocatore=trim($giocatore);
    $giocatore=ereg_replace("'","`",$giocatore);
    $pieces=explode("\n",$giocatore);
    $ruolo=trim($pieces[0]);
    $nome=trim($pieces[1]);
    $club=trim($pieces[2]);
    print "Nome:$nome";
    $uppa="update giocatore set Cognome='$nome',Ruolo='$ruolo',Club='$club'";
    $query="INSERT INTO giocatore(Cognome,Ruolo,Club) VALUES ('$nome','$ruolo','$club')";
    $risultato=mysql_query($query) or die("Query non valida: ".$nome . mysql_error());    
  } 
}


  // Inserimento Rose
function elencogiocatori($ruolo_filt)
{  
  $selez_ruolo="SELECT Cognome,IdGioc,Nome FROM giocatore WHERE Ruolo='$ruolo_filt' order by Cognome ASC";
  $risultato = mysql_query($selez_ruolo);
  $elencoopzioni="<option Selected></option>";
  while ($riga = mysql_fetch_array($risultato, MYSQL_NUM)) 
  {
    $elencoopzioni=$elencoopzioni."<option value=$riga[1]>$riga[0] $riga[2]</option>";
  }
  return $elencoopzioni;
   //$elenco=mysql_fetch_rowsarr($risultato);
}
function elencosquadre()
{
  $elencasq="select nome,IdSquadra from squadra";
  $risu=mysql_query($elencasq);
  $options="<option Selected></option>";
  while ($riga = mysql_fetch_array($risu, MYSQL_NUM)) 
  {
    print "aia".$riga[1];
    $options=$options."<option value=$riga[1]>$riga[0]</option>";
  }
  return $options; 
}
?>
