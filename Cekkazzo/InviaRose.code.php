<?php
include 'Functions.php'; 
connessione();

  $idsquadra=array_shift($_POST);
  print "id:$idsquadra";
  foreach($_POST as $key => $val)
  {
    print "$key--->$val<br>";
    $query="UPDATE Giocatore SET IdSquadra='$idsquadra' WHERE IdGioc='$val'";
    mysql_query($query)or die("Query non valida: ".$nome . mysql_error());
    
  }

  

?>
