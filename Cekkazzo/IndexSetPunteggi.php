  <title>Setta Punteggi Squadre</title>
  <form name=azzo method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  Giornata<input type="text" style="text-align:right"name="giornata">
  <br>
  IdSquadra<input type="text" style="text-align:right"name="id">
  <br>
  <input type="submit" value="calcola punti">
  </form>
<?php
  include 'SetPunteggi.php';
  include 'Functions.php';
  connessione();
  $giornata=$_POST['giornata'];
  $id=$_POST['id'];
  if(isset($_POST['giornata'])&&isset($_POST['id']))
  {   

        calcola_punti($giornata,$id);

    
  }    

?>











