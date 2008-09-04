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
  mysql_select_db("test");
  error_reporting(E_ALL ^ E_NOTICE);
}

function importa_giocatori()
{
  $percorso="D:\Documents and Settings\Shane Vendrell\Documenti/fantacalcio.csv";
  // copia il contenuto di un file in una stringa
  $handle = fopen($percorso, "r");
  $contenuto = fread($handle, filesize($percorso));
  fclose($handle);
  $patt_calc="\n";
  $elenco=explode($patt_calc,$contenuto);
  
  foreach ($elenco as $giocatore)
  {
    $giocatore=trim($giocatore);
    //$giocatore=ereg_replace("'","`",$giocatore);
    $pieces=explode(";",$giocatore);
    $codice=trim($pieces[0]);
    $ruolo=trim($pieces[1]);
    $nome=trim($pieces[2]);
    $nome = addslashes(ucwords(strtolower($nome)));
    $club=ucfirst(strtolower(trim($pieces[4])));
    $query="INSERT INTO giocatore(IdGioc,Cognome,Ruolo,Club) VALUES ('$codice','$nome','$ruolo','$club')";
    $risultato=mysql_query($query) or die("Query non valida: ".$nome . mysql_error());    
  } 
}


  // Inserimento Rose
function elencogiocatori($ruolo_filt)
{  
  $selez_ruolo="SELECT Cognome,IdGioc,Nome FROM giocatore WHERE Ruolo='$ruolo_filt' order by Cognome ASC";
  $risultato = mysql_query($selez_ruolo) or die ("Query non valida: ".$selez_ruolo. mysql_error());
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
  echo "<pre>".print_r($risu,1)."</pre>";
  $options="<option Selected></option>";
  while ($riga = mysql_fetch_array($risu, MYSQL_NUM)) 
  {
    print "aia".$riga[1];
    $options=$options."<option value=$riga[1]>$riga[0]</option>";
  }
  return $options; 
}

function recupera_nomi($percorso)
{

    foreach (file($percorso) as $player)
    {
        $pezzi=explode(";",$player);
        $cod=$pezzi[0];
        $nome=ucwords(strtolower(addslashes($pezzi[2])));
        $cognome=strtoupper(addslashes($pezzi[1]));
        $ruolo=$pezzi[3];
        $club=substr($pezzi[4],0,3);
        print $cod.$nome.$club."<br>";
        $query="UPDATE giocatore SET Cognome='$cognome',Nome='$nome',IdGioc='$cod',Club='$club' WHERE IdGioc=$cod";
        mysql_query($query) or die("Query non valida: ".$insert . mysql_error());
        if(mysql_affected_rows()==0)
        {
            $insert="INSERT INTO giocatore(IdGioc,Cognome,Nome,Ruolo,Club) VALUE ('$cod','$cognome','$nome','$ruolo','$club')";
        mysql_query($insert) or die("Query non valida: ".$insert . mysql_error());
        }
    }
}

function foto()
{
    $q="SELECT cognome FROM giocatore WHERE Club='ATA'";
    $risu=mysql_query($q) or die("Query non valida: ".$q . mysql_error());;
    while ($riga = mysql_fetch_array($risu, MYSQL_ASSOC)) 
    {
        $giocatori[]=strtolower($riga['cognome']);
    }
    foreach($giocatori as $appo)
    {
        $link="http://www.atalanta.it/atalanta/site/img/squadra/".$appo.".jpg";
        print $link."<br>";
    }
}

?>
