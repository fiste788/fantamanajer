  <title>Setta Voti Giocatori</title>
  <form name=azzo method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  Giornata<input type="text" style="text-align:right"name="nome">
  <br>
  <input type="submit" value="calcola punti">
  </form>
<?php

  include 'SetVotiGiocatori.php';

  $giornata=$_POST['nome'];
  if(isset($_POST['nome']))
  { 
    $giornata=$_POST['nome'];
    recupera_voti($giornata);
  }

?>
