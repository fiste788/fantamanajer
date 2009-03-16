<?php 
require_once(INCDIR.'trasferimenti.inc.php');

$trasferimentiObj = new trasferimenti();
$giornataObj = new giornata();

$today = date("Y-m-d");
$date = $giornataObj->getDataByGiornata($giornataObj->getIdGiornataByDate());
$giorn = explode(' ',$date[2]);
$dataGiornata = $giorn[0];

if(($today == $dataGiornata && date("H") == '00') || $_SESSION['usertype'] == 'superadmin')
{
	$trasferimentiObj->doTransfertBySelezione();
	$message[0] = 0;
	$message[1] = "Operazione effettuata correttamente";
	
}
else
{
	$message[0] = 1;
	$message[1] = "Non puoi effettuare l'operazione ora");
}
$contenttpl->assign('message',$message);
?>
