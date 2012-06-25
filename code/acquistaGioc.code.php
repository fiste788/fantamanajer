<?php 
require_once(INCDBDIR . 'trasferimento.db.inc.php');

$logger->start("ACQUISTA GIOCATORI");
if(Giornata::checkDay(($data = date("Y-m-d")),'dataFine') || $_SESSION['usertype'] == 'superadmin') {
	$logger->info("Starting do transfer");
	if(Trasferimento::doTransfertBySelezione()) {
		$message->success("Operazione effettuata correttamente");
		$logger->info("Trasnfert finished successfully");
	} else {
		$message->error("Errore nell'eseguire i trasferimenti");
		$logger->error("Error while doing transfer");
	}
} else {
	$message->warning("Non puoi effettuare l'operazione ora");
	$logger->warning("Is not time to run it");
}

$logger->end("ACQUISTA GIOCATORI");
$contentTpl->assign('message',$message);
?>
