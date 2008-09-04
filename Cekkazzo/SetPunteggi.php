<?php 
    include '../inc/punteggi.inc.php';
    include 'Functions.php';
    $percorso = "../docs/voti/Giornata1.csv";
    connessione();
    $punteggiObj = new punteggi();

    //recupera_voti(1);
    for($i=1;$i<=8;$i++)
    {
        $punteggiObj->calcolaPunti(1,$i); 
    }
?>