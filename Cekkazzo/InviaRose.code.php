<?php
  mysql_connect("localhost","ingo_fm","banana");
  mysql_select_db("test");
  error_reporting(E_ALL ^ E_NOTICE);
  $idsquadra=array_shift($_POST);
  print "id:$idsquadra";
  foreach($_POST as $key => $val)
  {
    print "$key--->$val<br>";
    $query="UPDATE Giocatore SET IdSquadra='$idsquadra' WHERE IdGioc='$val'";
    mysql_query($query)or die("Query non valida: ".$nome . mysql_error());
    
  }

  

?>
