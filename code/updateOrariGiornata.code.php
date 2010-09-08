<?php
require_once(INCDIR . 'giornata.db.inc.php');

if(Giornata::updateOrariGiornata())
	$message->success("Operazione effettuata correttamente");
else
	$message->warning("Errori nell'aggiornamento delle giornate");
?>
