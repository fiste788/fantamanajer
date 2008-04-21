<? 
include 'Functions.php'; 
$cambi=array(3,8,8,6);
$ruoli=array("P","D","C","A");
connessione();
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
