<?php 

require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'fileSystem.inc.php');
$giocatoreObj = new giocatore();
$fileObj = new fileSystem();
$fileObj->decrypt();
die();
$giornata=GIORNATA-1;
if($giocatoreObj->updateTabGiocatore($giornata))
{
	$message[0] = 0;
	$message[1] = "Operazione effettuata correttamente";

}
else
{
	$message[0] = 1;
	$message[1] = "Problema nel recupero dei giocatori";
}
$contenttpl->assign('message',$message);

?>
