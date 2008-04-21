<?php
function trim_value(&$value)
{
    $value = trim($value);
}

function scarica_voti($giornata)
{
  $percorso=".\Voti\Giornata".$giornata.".txt";
  $link="http://www.pianetafantacalcio.it/Voti_UfficialiTuttiPianeta.asp?giornataScelta=".$giornata."&tipolink=1&stagione=2007_2008";;
  $contenuto_ar = file($link);
  array_walk($contenuto_ar, 'trim_value');
  $contenuto=join("",$contenuto_ar);

  $espr2="/giornata\s*<table.+?<\/table>/m";
  preg_match($espr2,$contenuto,$s);
  $tabella_voti=$s[0];
  return $tabella_voti;
  



}

function recupera_voti($giorn)
{
  $sep_voti=";";
  $novoto="-";
  $voti=scarica_voti($giorn);  
  $espr="<tr>";
  $keywords = explode($espr, $voti);
  array_shift($keywords);
  array_shift($keywords);
  array_shift($keywords);
  foreach ($keywords as $player)
  {
    $i++;
    $espre="/(<[^<>]+>)+/";
    $player=preg_replace($espre,"\t",$player);
    
    $pieces=explode("\t",$player);
    $cognome=trim($pieces[2]);
    $club=trim($pieces[4]);
    $club=substr($club,6,3);

    $voto=$pieces[6];
    $voto=ereg_replace(',','.',$voto);

    if (preg_match("/(.*?)\s{1}(\w+)(\.|[a-z])/",$cognome,$pieces))
    {
      $cognome=$pieces[1];
      $nome=$pieces[2];
    }
    else
    {
      $nome="";
    }
    //print "cognome:$cognome\t\tnome:$nome---->$voto<br>";
    $select="SELECT IdGioc FROM Giocatore WHERE Cognome='$cognome' AND Nome LIKE '$nome%'";
    $risu=mysql_query($select);
    $num=mysql_num_rows($risu);
    if($num>1)
    {
      //print "Nome:$cognome-------->Numero:$num<br>";
      $par_club=" AND Club='$club'";
    }
    
    $query="SELECT IdGioc,Voti,Cognome FROM Giocatore WHERE Nome LIKE '$nome%' 
    AND Cognome='$cognome'".$par_club;
    $par_club=""; 
    $risultato=mysql_query($query);
    if(mysql_num_rows($risultato)==0)
    {
      print "Giocatore: $cognome non trovato";
      continue;
    }   
    $riga = mysql_fetch_array($risultato, MYSQL_NUM);
    //print "id:$riga[0]---->$riga[2]<br>";
    $array_idgioc[]=$riga[0];
    $votoold=$riga[1];
    
    
    $update="UPDATE Giocatore SET Voti='$votoold$voto$sep_voti' 
    WHERE IdGioc='$riga[0]'";
    mysql_query($update);

  }
  $conc=join(",",$array_idgioc);
  $q="SELECT idgioc,voti FROM giocatore WHERE idgioc NOT IN ($conc)";
  $esito=mysql_query($q)or die("Query non valida: ".$q . mysql_error());
  while ($riga = mysql_fetch_array($esito, MYSQL_NUM))
  {
    $votoold=$riga[1];
    $id=$riga[0];
    $update="UPDATE Giocatore SET Voti='$votoold$novoto$sep_voti' WHERE IdGioc='$id'";
    mysql_query($update)or die("Query non valida: ".$update . mysql_error());
  }
}

?>
