<?php 
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'utente.inc.php');

$giocatoreObj = new giocatore();
$utenteObj = new utente();

$contenttpl->assign('giocatori',$giocatoreObj->getAllGiocatori());
if(isset($_POST['submit']))
{
	echo "prova";
//	$giocatoreObj->ricercaGioc($_POST);
}
?>
