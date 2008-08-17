<? 
include 'Functions.php'; 
$cambi=array(3,8,8,6);
$ruoli=array("P","D","C","A");
connessione();
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
    print "ciao";
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
?>
<form method=POST action="InviaRose.code.php"; name=nome>
<p align="center" class="titolo_articolo">Inserisci la formazione :
<b></b>
<select name="cazzo">
<? echo elencosquadre();?>
</select>
<br>del Presidente<B> Shane Vendrell</b></p>
<table align="center"  width="500" border="0" cellpadding="1" cellspacing="1">
 <tr>
 	<td  valign="top" align="center" class="nero16">Portieri
 	
 	<table align="center" width="450"  border="0" cellpadding="0" cellspacing="0" style="border: 1 solid #000000">

			<tr>
        		<td height="15" align="center" class=P_sx>&nbsp;</td>
			</tr>
			<tr>
        		<td height="15" align="center" class=P_sx >

			</tr>
      <?
      $i_ruolo=0;
      $j=0;
      $invio="";
      for($i=0;$i<25;$i++)
      {
        $combobox="<tr><td height=\"15\" align=\"center\" class=P_sx> <select name=\"Giocatore".$i."\" size=\"1\" selected=\"\"style=\"font-size: 8 pt; font-family: Courier\">"
        .elencogiocatori($ruoli[$i_ruolo])."</select></td></tr>";  
        $invio="";
        echo $combobox;
        $combobox="";
        $j++;
        if($j==$cambi[$i_ruolo])
        {
          $j=0;
          $i_ruolo++;
          $invio="<tr><br></tr>";
        }
      }
      ?>
  </table>
  <br>
  <input type=submit value="Manda">
</form>


</HTML>
