<?php
require_once(INCDIR.'utente.inc.php');
require_once(INCDIR.'squadre.inc.php');
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'mail.inc.php');
require_once(INCDIR.'leghe.inc.php');
require_once(INCDIR.'strings.inc.php');
require_once(INCDIR.'punteggi.inc.php');

$utenteObj = new utente();
$squadreObj = new squadre();
$giocatoreObj = new giocatore();
$mailObj = new mail();
$legheObj = new leghe();
$stringObj = new string(NULL);
$mailContent = new Savant2();
$punteggiObj = new punteggi();

$action = NULL;
$id = NULL;
$lega = NULL;
if(isset($_GET['a']))
	$action = $_GET['a'];
if(isset($_GET['id']))
	$id = $_GET['id'];
if(isset($_GET['lega']))
	$lega = $_GET['lega'];
if(isset($_POST['a']))
	$action = $_POST['a'];
if(isset($_POST['id']))
	$id = $_POST['id'];
if(isset($_POST['lega']))
	$lega = $_POST['lega'];
	
if($_SESSION['usertype'] == 'admin')
	$lega = $_SESSION['idLega'];

if($lega != NULL && $action != NULL && $id != NULL)
{
	if(!isset($_POST['button']))
	{
		if($action == 'cancel' || $action == 'edit')
		{
			$contenttpl->assign('giocatori',$giocatoreObj->getGiocatoriByIdSquadra($id));
			$contenttpl->assign('datiSquadra',$utenteObj->getSquadraById($id));
		}
	}
	else
	{
		if($action == 'cancel')
		{
			if($utenteObj->deleteSquadra($id))
			{
				$squadreObj->unsetSquadraGiocatoreByIdSquadra($id);
				$message[0] = 0;
				$message[1] = "Cancellazione effettuata correttamente";
				unset($_POST);
			}
			else
			{
				$message[0] = 1;
				$message[1] = "Hai già eliminato questa squadra";
			}
		}
		elseif($action == 'edit' || $action == 'new')
		{
			$giocatori = array();
			foreach($_POST as $key => $val)
			{
				if($key != 'id' && empty($val))
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
			if(!$mailObj->checkEmailAddress($_POST['mail']))
			{
				$message[0] = 1;
				$message[1] = "Mail non corretta";
			}
			if($utenteObj->getSquadraByUsername(addslashes(stripslashes(trim($_POST['usernamenew']))),$id) != FALSE)
			{
				$message[0] = 1;
				$message[1] = "Un altro utente con questo username è già presente";
			}
			if($utenteObj->getSquadraByNome(addslashes(stripslashes(trim($_POST['nome']))),$id) != FALSE)
			{
				$message[0] = 1;
				$message[1] = "Il nome della squadra è già presente";
			}
			if(!isset($message))
			{
				//tutto giusto
				if(isset($_POST['amministratore']))
					$amministratore = '1';
				else
					$amministratore = '0';
				if($action == 'edit')
				{
					$campi = array('nome'=>'','usernamenew'=>'','mail'=>'','amministratore'=>'');
					foreach($_POST as $key => $val)
					{
						if(isset($campi[$key]))
							$data[$key] = addslashes(stripslashes(trim($val)));
					}
					$utenteObj->changeData($data,$id);
					$giocatoriOld = $giocatoreObj->getGiocatoriByIdSquadra($id);
					foreach($_POST as $key => $val)
						if(substr($key,0,9) == 'giocatore')
							$giocatoriNew[] = $val;
					foreach($giocatoriOld as $key => $val)
						if($val['idGioc'] != $giocatoriNew[$key])
							$squadreObj->updateGiocatore($giocatoriNew[$key],$val['idGioc'],$id);
					unset($_POST);
					$contenttpl->assign('giocatori',$giocatoreObj->getGiocatoriByIdSquadra($id));
					$contenttpl->assign('datiSquadra',$utenteObj->getSquadraById($id));
					$message[0] = 0;
					$message[1] = "Squadra modificata correttamente";
				}
				else
				{
					$password = $stringObj->createRandomPassword();
					echo md5($password)."<br >";
					echo $password;
					$squadra = $utenteObj->addSquadra(addslashes(stripslashes(trim($_POST['usernamenew']))),addslashes(stripslashes(trim($_POST['nome']))),$amministratore,$password,addslashes(stripslashes(trim($_POST['mail']))),$lega);
					$squadreObj->setSquadraGiocatoreByArray($lega,$giocatori,$squadra);
					$id = $squadra;
					$message[0] = 0;
					$message[1] = "Squadra creata correttamente";
					$mailContent->assign('username',$_POST['usernamenew']);
					$mailContent->assign('squadra',$_POST['nome']);
					$mailContent->assign('password',$password);
					$mailContent->assign('lega',$legheObj->getLegaById($lega));
					$object = "Benvenuto nel FantaManajer!";
					$punteggiObj->setPunteggiToZero($squadra,$lega);
					$mailContent->display(MAILTPLDIR.'mailBenvenuto.tpl.php');
					//$mailObj->sendEmail($_POST['mail'],$mailContent->fetch(MAILTPLDIR.'mailBenvenuto.tpl.php'),$object);
					unset($_POST);
				}
			}
		}
	}
}
if(isset($message))
	$_SESSION['message'] = $message;

$contenttpl->assign('portieri',$giocatoreObj->getFreePlayer('P'));
$contenttpl->assign('difensori',$giocatoreObj->getFreePlayer('D'));
$contenttpl->assign('centrocampisti',$giocatoreObj->getFreePlayer('C'));
$contenttpl->assign('attaccanti',$giocatoreObj->getFreePlayer('A'));
if($lega != NULL)
	$contenttpl->assign('elencosquadre',$utenteObj->getElencoSquadreByLega($lega));
$contenttpl->assign('elencoLeghe',$legheObj->getLeghe());
$contenttpl->assign('lega',$lega);
$goTo = array();
if($action != NULL && $action == 'cancel' || $action == 'new')
	$goTo = array('lega'=>$lega,'a'=>'new','id'=>'0');
elseif($action != NULL)
	$goTo = array('lega'=>$lega,'a'=>'edit','id'=>$id);
$contenttpl->assign('goTo',$goTo);
?>
