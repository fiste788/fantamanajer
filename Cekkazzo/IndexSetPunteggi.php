
<?php
  include '../inc/punteggi.inc.php';
  include 'Functions.php';
  $percorso = "../docs/voti/Giornata1.csv";
  connessione();
  $punteggiObj = new punteggi();

    $punteggiObj->calcolaPunti(1,2);   

?>











