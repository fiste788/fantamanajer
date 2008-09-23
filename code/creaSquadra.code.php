<?php
require_once(INCDIR.'squadra.inc.php');
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'mail.inc.php');
require_once(INCDIR.'strings.inc.php');

$squadraObj = new squadra();
$giocatoreObj = new giocatore();
$mailObj = new mail();
$stringObj = new string(NULL);

$contenttpl->assign('portieri',$giocatoreObj->getFreePlayer('P'));
$contenttpl->assign('difensori',$giocatoreObj->getFreePlayer('D'));
$contenttpl->assign('centrocampisti',$giocatoreObj->getFreePlayer('C'));
$contenttpl->assign('attaccanti',$giocatoreObj->getFreePlayer('A'));

$giocatori = array();
if(!empty($_POST))
{
	foreach($_POST as $key=>$val)
	{
		if(empty($val))
		{
			$message[0] = 1;
			$message[1] = "Non hai compilato tutti i campi";
		}
		elseif(in_array($val,$giocatori))
		{
			$message[0] = 1;
			$message[1] = "Hai immesso un giocatore più di una volta";
			break;
		}
		else
			$giocatori[] = $val;
	}
	if(!$mailObj->checkEmailAddress($_POST['email']))
	{
		$message[0] = 1;
		$message[1] = "Mail non corretta";
	}
	if(!isset($message))
	{
		//tutto giusto
		if(isset($_POST['amministratore']))
			$amministratore = TRUE;
		else
			$amministratore = FALSE;
		$squadraObj->addSquadra($_POST['usernamenew'],$_POST['nomeSquadra'],$amministratore,$stringObj->createRandomPassword(),$_POST['email']);
	}
	echo "<pre>".print_r($squadraObj,1)."</pre>";
	$contenttpl->assign('messaggio',$message);
}
?>
