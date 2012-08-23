<?php
require_once(INCDBDIR . 'trasferimento.db.inc.php');
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(INCDBDIR . 'lega.db.inc.php');

$trasferimento = new Trasferimento();
if($trasferimento->validate()) {
	$trasferimento->setIdGiornata(GIORNATA);
	if($trasferimento->save() != FALSE)
		$message->success('Trasferimento effettuato correttamente');
	else
		$message->error("Errore generico nell'inserimento");
}
?>
