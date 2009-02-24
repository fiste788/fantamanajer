
<?php

  include 'Functions.php';
  require("../inc/giocatore.inc.php");
  require("../config/config.inc.php");
  require("../inc/db.inc.php");
  
  $giocatoreobj = new giocatore();
  $dbLink = &new db;
  $dbLink->dbConnect();
  $giocatoreobj->updateTabGiocatore(25);

?>
