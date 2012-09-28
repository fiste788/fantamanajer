<?php
$logger->start("UPDATE ORARI");
if(Giornata::checkDay(date("Y-m-d"),'dataFine',-2) != FALSE || $_SESSION['usertype'] == 'superadmin')
{
	$logger->info("Updating end of this gameweek and start of next gameweek");
	if(Giornata::updateOrariGiornata())
		$message->success("Operazione effettuata correttamente");
	else
		$message->warning("Errori nell'aggiornamento delle giornate");
}
else
{
	$message->warning("Non puoi effettuare l'operazione ora");
	$logger->warning("Is not time to run it");
}
$logger->end("UPDATE ORARI");
$contentTpl->assign("message",$message);
?>
