<?php 
require_once(INCDIR . 'trasferimento.db.inc.php');

$trasferimentoObj = new trasferimento();
$giornataObj = new giornata();

$today = date("Y-m-d");
$date = $giornataObj->getDataByGiornata($giornataObj->getIdGiornataByDate());
$giorn = explode(' ',$date[2]);
$dataGiornata = $giorn[0];

if(($today == $dataGiornata && date("H") == '00') || $_SESSION['usertype'] == 'superadmin')
{
	$trasferimentoObj->doTransfertBySelezione();
	$message['level'] = 0;
	$message['text'] = "Operazione effettuata correttamente";
	
}
else
{
	$message['level'] = 1;
	$message['text'] = "Non puoi effettuare l'operazione ora";
}
$layouttpl->assign('message',$message);
?>
