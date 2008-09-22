<?php
require_once(INCDIR.'squadra.inc.php');
require_once(INCDIR.'giocatore.inc.php');

$squadraObj = new squadra();
$giocatoreObj = new giocatore();

$contenttpl->assign('portieri',$giocatoreObj->getFreePlayer('P'));
$contenttpl->assign('difensori',$giocatoreObj->getFreePlayer('D'));
$contenttpl->assign('centrocampisti',$giocatoreObj->getFreePlayer('C'));
$contenttpl->assign('attaccanti',$giocatoreObj->getFreePlayer('A'));

if(!empty($_POST))
{
	foreach($_POST as $key=>$val)
	{
		if(empty($val))
		{
			$message[0] = 1;
			$message[1] = "Non hai compilato tutti i campi";
		}
	}
	$squadraObj->nome = $_POST['nomeSquadra'];
	$squadraObj->username = $_POST['usernamenew'];
	$squadraObj->email = $_POST['email'];
	$squadraObj->amministratore = $_POST['amministratore'];
	$contenttpl->assign('messaggio',$message);
}
?>
