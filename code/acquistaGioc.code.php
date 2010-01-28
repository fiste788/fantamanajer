<?php 
require_once(INCDIR . 'trasferimento.db.inc.php');

$trasferimentoObj = new trasferimento();
$giornataObj = new giornata();

$logger->start("ACQUISTA GIOCATORI");
$today = date("Y-m-d");
$date = $giornataObj->getDataByGiornata($giornataObj->getGiornataByDate());
$giorn = explode(' ',$date[2]);
$dataGiornata = $giorn[0];

if(($today == $dataGiornata && date("H") == '00') || $_SESSION['usertype'] == 'superadmin')
{
	$logger->info("Starting do transfer");
	if($trasferimentoObj->doTransfertBySelezione())
	{
		$message->success("Operazione effettuata correttamente");
		$logger->info("Trasnfert finished successfully");
	}
	else
	{
		$message->error("Errore nell'eseguire i trasferimenti");
		$logger->error("Error while doing transfer");
	}
}
else
{
	$message->warning("Non puoi effettuare l'operazione ora");
	$logger->warning("Is not time to run it");
}
$logger->end("ACQUISTA GIOCATORI");
$contentTpl->assign('message',$message);
?>
