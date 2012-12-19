<?php
require_once(INCDBDIR . 'trasferimento.db.inc.php');
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(INCDBDIR . 'lega.db.inc.php');

$trasferimento = new Trasferimento();
$trasferimento->setIdGiornata(GIORNATA);
$trasferimento->setObbligato(FALSE);
$trasferimento->save();
$message->success('Trasferimento effettuato correttamente');

?>
