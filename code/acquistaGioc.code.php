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
	$contenttpl->assign('message','Operazione effettuata correttamente');
}
else
	$contenttpl->assign('message','Non puoi effettuare l\'operazione ora');
?>
