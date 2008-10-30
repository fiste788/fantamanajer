<?php
require_once(INCDIR.'utente.inc.php');
require_once(INCDIR.'squadre.inc.php');
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'mail.inc.php');
require_once(INCDIR.'leghe.inc.php');
require_once(INCDIR.'strings.inc.php');

$utenteObj = new utente();
$squadreObj = new squadre();
$giocatoreObj = new giocatore();
$mailObj = new mail();
$legheObj = new leghe();
$stringObj = new string(NULL);

$lega = NULL;

if(isset($_GET['a']))
	$action = $_GET['a'];
if(isset($_GET['id']))
{
	$id = $_GET['id'];
	$lega = $utenteObj->getLegaByIdSquadra($id);
}
if(isset($_POST['lega']))
	$lega = $_POST['lega'];
	
$giocatori = array();
unset($_POST['lega']);
if($lega != NULL)
{
	if(isset($action) && isset($id))
	{
		if($action == 'edit')
		{
		
		}
		elseif($action == 'cancel')
		{
			if($utenteObj->deleteSquadra($id))
			{
				$squadreObj->unsetSquadraGiocatoreByIdSquadra($id);
				$message[0] = 0;
				$message[1] = "Cancellazione effettuata correttamente";
				$lega = NULL;
			}
			else
			{
				$lega = NULL;
				$message[0] = 1;
				$message[1] = "Hai già eliminato questa squadra";
			}
		}
	}
	if(!empty($_POST))
	{
		foreach($_POST as $key=>$val)
		{
			if(empty($val))
			{
				$message[0] = 1;
				$message[1] = "Non hai compilato tutti i campi";
			}
			elseif(in_array($val,$giocatori) && substr($key,0,9) == 'giocatore')
			{
				$message[0] = 1;
				$message[1] = "Hai immesso un giocatore più di una volta";
				break;
			}
			elseif(substr($key,0,9) == 'giocatore')
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
			$squadra = $utenteObj->addSquadra($_POST['usernamenew'],$_POST['nomeSquadra'],$amministratore,$stringObj->createRandomPassword(),$_POST['email'],$lega);
			echo $squadra;
			$squadreObj->setSquadraGiocatoreByArray($lega,$giocatori,$squadra);
			$message[0] = 0;
			$message[1] = "Squadra creata correttamente";
		}
	}
}
if(isset($message))
	$contenttpl->assign('messaggio',$message);

$contenttpl->assign('portieri',$giocatoreObj->getFreePlayer('P'));
$contenttpl->assign('difensori',$giocatoreObj->getFreePlayer('D'));
$contenttpl->assign('centrocampisti',$giocatoreObj->getFreePlayer('C'));
$contenttpl->assign('attaccanti',$giocatoreObj->getFreePlayer('A'));
if($lega != NULL)
	$contenttpl->assign('elencosquadre',$utenteObj->getElencoSquadreByLega($lega));
$contenttpl->assign('elencoLeghe',$legheObj->getLeghe());
$contenttpl->assign('lega',$lega);
?>
