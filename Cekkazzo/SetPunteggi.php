<?php 
    include '../inc/punteggi.inc.php';
    include 'Functions.php';
    $percorso = "../docs/voti/Giornata1.csv";
    connessione();
    $punteggiObj = new punteggi();

    //recupera_voti(1);
    update_tab_giocatore(1);
?>