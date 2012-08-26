<?php 
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'punteggio.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(INCDBDIR . 'formazione.db.inc.php');
require_once(INCDBDIR . 'voto.db.inc.php');
require_once(INCDBDIR . 'lega.db.inc.php');
require_once(INCDIR . 'decrypt.inc.php');
require_once(INCDIR . 'backup.inc.php');
require_once(INCDIR . 'fileSystem.inc.php');
require_once(INCDIR . 'swiftMailer/swift_required.php');

$giornata = GIORNATA - 1;
$logger->start("UPDATE GIOCATORI");
if($_SESSION['usertype'] == 'superadmin')
{
		$logger->info("Starting decript file day " . $giornata);
		$path = Decrypt::decryptCdfile($giornata);
		FirePHP::getInstance()->log($path);
		//$path="C:\Users\Shane\Downloads\mcc00.txt";
		$logger->info("Updating table players");
		$result = Giocatore::updateTabGiocatore($path,$giornata);
		if($result != TRUE){
			$logger->error($result);
			$message->warning("Errori nell'aggiornamento delle giornate");
		}
		$message->success("Operazione effettuata correttamente");
		FirePHP::getInstance()->log($path);
}
$logger->end("UPDATE GIOCATORI");
 ?>