<?php
function rec_voto($id,$giornata)
{
  $pattern=";";
  $query="SELECT Voti FROM giocatore WHERE IdGioc='$id'";
  $risu=mysql_query($query) or die("Query non valida: ".$query . mysql_error());
  $riga = mysql_fetch_array($risu, MYSQL_NUM);
  $voti=explode($pattern,$riga[0]);
  $voto=$voti[$giornata-1];  
  return $voto;
}
function verifica_voto($voto)
{
  $novoto="-";  
  if($voto!=$novoto)
    return true;
  else
    return false;
}
function calcola_punti($giornata,$idsquadra)
{
  $pattern=";";
  $cambi=0;
  $prn_cap="-";
// PARAMETRI DA RIDEFINIRE
//---------------------->
//----------------------<  
// ricavo la formazione relativa
  $query="SELECT Elenco FROM formazioni WHERE idgiornata='$giornata' AND idsquadra='$idsquadra'";
  $risu=mysql_query($query) or die("Query non valida: ".$query . mysql_error());
  $riga = mysql_fetch_array($risu, MYSQL_NUM);
  $formaz=explode("!",$riga[0]);
// ottengo i titolari
  $tito=explode($pattern,array_shift($formaz));
// ottengo i panchinari
  $panch=explode($pattern,array_shift($formaz));
  $el_voti=array();
// ciclo ogni giocatore titolare
  foreach ($tito as $player_dae)
  {
 
    $pieces=explode($prn_cap,$player_dae);
    $player=$pieces[0];
// recupero il voto del giocatore    
    $voto=rec_voto($player,$giornata);
// trovo i cap,v-cap e vv-cap 
    $cap=$pieces[1];
// se trovo li sbatto dentro così: $array["C"]->idgioc  $array["VC"]->idgioc    
    if($cap!="")
    {
      $el_cap[$cap]=$player;
    }
// se il gioc ha preso voto lo sbatto dentro così: $attay[idgioc]->voto    
    if(verifica_voto($voto))
    {
      $el_voti[$player]=$voto;
    }
// se non ha preso voto    
    elseif($cambi<3)
    {
// ottengo il ruolo del gioc che nn ha giocato
      $q_ruolo="SELECT ruolo FROM giocatore WHERE idgioc='$player'";
      $risu=mysql_query($q_ruolo) or die("Query non valida: ".$q_ruolo. mysql_error());
      $r=array_shift(mysql_fetch_array($risu, MYSQL_NUM));
// cerco fra tutti i panchinari i giocatori con lo stesso ruolo es. CRESPO SV(CERCO TUTTI GLI ATTACCANTI IN PANCA)
      $conc=substr(join(",",$panch),1);
      if($ruolo_prec!=$r)
      {
        $ruolo_prec=$r;
        $q_sost="SELECT idgioc FROM giocatore WHERE idgioc IN (".$conc.") AND ruolo='".$r."'";
        $esito=mysql_query($q_sost) or die("Query non valida: ".$q_sost . mysql_error());
        $sost=array();
// METTO IN $sost TUTTI QUELLI I PANCH CON LO STESSO RUOLO      
        while ($riga = mysql_fetch_array($esito, MYSQL_NUM))
        {
          $a=strpos($conc, $riga[0]);
          $sost[$a]=$riga[0];    
        }
        ksort($sost);
      }
      //echo "<pre>".print_r($sost,1)."</pre>";
// E LI ORDINO COME NELLA FORMAZIONE      
      foreach ($sost as $key=>$id_sost)
      {
        $voto_s=rec_voto($id_sost,$giornata);
// SE IL 1° PANCHINARO PRESO VOTO ESCO SENNò CONTINUO A CERCARE        
        if(verifica_voto($voto_s))
        {
          //print "mettoquesto:$id_sost";
          $el_voti[$id_sost]=$voto_s;
          unset($sost[$key]);
          $cambi++;
          break;
        }
      }
// LO AGGIUNGO NELL'ARRAY DI QLL KE HAN PRESO VOTO       
      
    }
  }
  ksort($el_cap);
// RADDOPPIO I PUNTI DEL CAP  

  foreach ($el_cap as $key=>$value)
  {
    //print "k:$key--->$value<br>";
    if(array_key_exists($value,$el_voti))
    {
      $el_voti[$value]*=2;
      break;
    }
  }
  echo "<pre>".print_r($el_voti,1)."</pre>";
// SOMMO I PUNTI DI QLL KE HAN GIOCATO  
  $somma=array_sum($el_voti);
  print "$somma";
  $ins_somma="INSERT INTO punteggi(IdGiornata,IdSquadra,Punteggio) VALUES ('$giornata','$idsquadra','$somma')";
  mysql_query($ins_somma) or die("Query non valida: ".$nome . mysql_error());
}


?>
