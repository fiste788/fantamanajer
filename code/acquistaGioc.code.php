<?php 
require_once(INCDIR.'trasferimenti.inc.php');

$trasferimentiObj = new trasferimenti();
$giornataObj = new giornata();

$today = date("Y-m-d");
$date = $giornataObj->getDataByGiornata($giornataObj->getIdGiornataByDate());
$giorn = explode(' ',$date[2]);
$dataGiornata = $giorn[0];

if((isset($_GET['user']) && trim($_GET['user']) == 'admin' && isset($_GET['pass']) && trim($_GET['pass']) == md5('omordotuanuoraoarounautodromo')) || $_SESSION['usertype'] == 'superadmin')
{
	if(($today == $dataGiornata && date("H") == '00') || $_SESSION['usertype'] == 'superadmin')
	{
		$trasferimentiObj->doTransfertBySelezione();
		$contenttpl->assign('message','Operazione effettuata correttamente');
	}
	else
		$contenttpl->assign('message','Non puoi effettuare l\'operazione ora');
}
else
	$contenttpl->assign('message','Non sei autorizzato a eseguire l\'operazione');
?>
