<?php
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'squadra.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'lega.db.inc.php');
require_once(INCDIR . 'mail.inc.php');
require_once(INCDIR . 'strings.inc.php');

$utenteObj = new utente();
$squadraObj = new squadra();
$giocatoreObj = new giocatore();
$legaObj = new lega();
$mailObj = new mail();
$stringObj = new string(NULL);
$mailContent = new Savant3();
$dbObj = new db();

$filterAction = NULL;
$filterId = NULL;
$filterLega = NULL;

if(isset($_GET['a']))
	$filterAction = $_GET['a'];
if(isset($_POST['a']))
	$filterAction = $_POST['a'];
if(isset($_GET['id']))
	$filterId = $_GET['id'];
if(isset($_POST['id']))
	$filterId = $_POST['id'];
if(isset($_GET['lega']) && !empty($_GET['lega']))
	$filterLega = $_GET['lega'];
if(isset($_POST['lega']))
	$filterLega = $_POST['lega'];
if($_SESSION['roles'] == '1')
	$filterLega = $_SESSION['idLega'];

if($filterLega != NULL && $filterAction != NULL && $filterId != NULL)
{
	if(!isset($_POST['button']))
	{
		if($filterAction == 'cancel' || $filterAction == 'edit')
		{
			$contenttpl->assign('giocatori',array_values($giocatoreObj->getGiocatoriByIdSquadra($filterId)));
			$contenttpl->assign('datiSquadra',$utenteObj->getSquadraById($filterId));
		}
	}
	else
	{
		if($filterAction == 'cancel')
		{
			if($utenteObj->deleteSquadra($filterId))
			{
				$squadraObj->unsetSquadraGiocatoreByIdSquadra($filterId);
				$message['level'] = 0;
				$message['text'] = "Cancellazione effettuata correttamente";
				unset($_POST);
			}
			else
			{
				$message['level'] = 1;
				$message['text'] = "Hai già eliminato questa squadra";
			}
		}
		elseif($filterAction == 'edit' || $filterAction == 'new')
		{
			$giocatori = array();
			foreach($_POST['giocatore'] as $key => $val)
			{
				if(!empty($val))
				{
					if(in_array($val,$giocatori))
					{
						$message['level'] = 1;
						$message['text'] = "Hai immesso un giocatore più di una volta";
						break;
					}
					else
						$giocatori[] = $val;
				}
				else
				{
					$message['level'] = 1;
					$message['text'] = "Non hai compilato tutti i giocatori";
				}
			}
			if(!$mailObj->checkEmailAddress($_POST['mail']))
			{
				$message['level'] = 1;
				$message['text'] = "Mail non corretta";
			}
			if($utenteObj->getSquadraByUsername(addslashes(stripslashes(trim($_POST['usernamenew']))),$filterId) != FALSE)
			{
				$message['level'] = 1;
				$message['text'] = "Un altro utente con questo username è già presente";
			}
			if($utenteObj->getSquadraByNome(addslashes(stripslashes(trim($_POST['nome']))),$filterId) != FALSE)
			{
				$message['level'] = 1;
				$message['text'] = "Il nome della squadra è già presente";
			}
			if(!isset($message))
			{
				//tutto giusto
				if(isset($_POST['amministratore']))
					$amministratore = '1';
				else
					$amministratore = '0';
				if($filterAction == 'edit')
				{
					$campi = array('nome'=>'','usernamenew'=>'','mail'=>'','amministratore'=>'');
					foreach($_POST as $key => $val)
					{
						if(isset($campi[$key]))
							$data[$key] = addslashes(stripslashes(trim($val)));
					}
					$utenteObj->changeData($data,$filterId);
					$giocatoriOld = array_keys($giocatoreObj->getGiocatoriByIdSquadra($filterId));
					foreach($giocatori as $key => $val)
						if(!in_array($val,$giocatoriOld))
							$squadraObj->updateGiocatore($val,$giocatoriOld[$key],$filterId);
					unset($_POST);
					$contenttpl->assign('giocatori',array_values($giocatoreObj->getGiocatoriByIdSquadra($filterId)));
					$contenttpl->assign('datiSquadra',$utenteObj->getSquadraById($filterId));
					$message['level'] = 0;
					$message['text'] = "Squadra modificata correttamente";
				}
				else
				{
					$password = $stringObj->createRandomPassword();
					$dbObj->startTransaction();
					$squadra = $utenteObj->addSquadra(addslashes(stripslashes(trim($_POST['usernamenew']))),addslashes(stripslashes(trim($_POST['nome']))),$amministratore,$password,addslashes(stripslashes(trim($_POST['mail']))),$filterLega);
					$squadraObj->setSquadraGiocatoreByArray($filterLega,$giocatori,$squadra);
					$dbObj->commit();
					$filterId = $squadra;
					$message[0] = 0;
					$message[1] = "Squadra creata correttamente";
					$mailContent->assign('username',$_POST['usernamenew']);
					$mailContent->assign('squadra',$_POST['nome']);
					$mailContent->assign('password',$password);
					$mailContent->assign('lega',$legaObj->getLegaById($filterLega));
					$mailContent->assign('autore',$utenteObj->getSquadraById($_SESSION['idSquadra']));
					$object = "Benvenuto nel FantaManajer!";
					//$mailContent->display(MAILTPLDIR.'mailBenvenuto.tpl.php');
					$mailObj->sendEmail($_POST['mail'],$mailContent->fetch(MAILTPLDIR.'mailBenvenuto.tpl.php'),$object);
					unset($_POST);
				}
			}
		}
	}
}
if(isset($message))
	$layouttpl->assign('message',$message);

if(isset($filterAction))
{
	switch($filterAction)
	{
		case 'new': $button = 'Crea'; break;
		case 'edit': $button = 'Modifica'; break; 
		case 'cancel': $button = 'Cancella'; break; 
	}
}
$contenttpl->assign('portieri',$giocatoreObj->getFreePlayer('P',$filterLega));
$contenttpl->assign('difensori',$giocatoreObj->getFreePlayer('D',$filterLega));
$contenttpl->assign('centrocampisti',$giocatoreObj->getFreePlayer('C',$filterLega));
$contenttpl->assign('attaccanti',$giocatoreObj->getFreePlayer('A',$filterLega));
$contenttpl->assign('lega',$filterLega);
$contenttpl->assign('id',$filterId);
$contenttpl->assign('action',$filterAction);
$goTo = array();
if($filterAction != NULL && $filterAction == 'cancel' || $filterAction == 'new')
	$goTo = array('a'=>'new','id'=>'0','lega'=>$filterLega);
elseif($filterAction != NULL)
	$goTo = array('a'=>'edit','id'=>$filterId,'lega'=>$filterLega);
$contenttpl->assign('goTo',$goTo);
$contenttpl->assign('button',$button);
if($filterLega != NULL)
	$operationtpl->assign('elencoSquadre',$utenteObj->getElencoSquadreByLega($filterLega));
$operationtpl->assign('elencoLeghe',$legaObj->getLeghe());
$operationtpl->assign('lega',$filterLega);
$operationtpl->assign('id',$filterId);
$operationtpl->assign('action',$filterAction);
?>
