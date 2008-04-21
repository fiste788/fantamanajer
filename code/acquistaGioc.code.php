<?php 
require(INCDIR.'giocatore.inc.php');
require(INCDIR.'giornata.inc.php');

$giocatoreObj = new giocatore();
$giornataObj = new giornata();

$today = date("Y-m-d");
$date = $giornataObj->getDataByGiornata($giornataObj->getIdGiornataByDate());
$giorn = explode(' ',$date[2]);
$dataGiornata = $giorn[0];

if($today == $dataGiornata && date("H") == '00')
	$giocatoreObj->doTransfert();
?>
