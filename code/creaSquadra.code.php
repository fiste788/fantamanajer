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
$mailContent = new Savant2();

if(isset($_GET['a']))
	$action = $_GET['a'];
if(isset($_POST['a']))
	$action = $_POST['a'];
if(isset($_GET['id']))
	$id = $_GET['id'];
if(isset($_POST['id']))
	$id = $_POST['id'];
	
if(isset($id) && $id != 0)
	$_SESSION['creaSquadraLega'] = $utenteObj->getLegaByIdSquadra($id);

if(!isset($_SESSION['creaSquadraLega']) || ((!isset($action)) && (!isset($id))))
	$_SESSION['creaSquadraLega'] = NULL;
	
if(isset($_POST['lega']))
	$_SESSION['creaSquadraLega'] = $_POST['lega'];
if($_SESSION['usertype'] == 'admin')
	$_SESSION['creaSquadraLega'] = $_SESSION['idLega'];

$giocatori = array();
$lega = $_SESSION['creaSquadraLega'];
unset($_POST['lega']);
if($lega != NULL)
{
	if(isset($action) && isset($id))
	{
		if($action == 'cancel')
		{
			if($utenteObj->deleteSquadra($id))
			{
				$squadreObj->unsetSquadraGiocatoreByIdSquadra($id);
				$message[0] = 0;
				$message[1] = "Cancellazione effettuata correttamente";
				$_SESSION['message'] = $message;
				header('Location: '. str_replace('&amp;','&',$linksObj->getLink('creaSquadra',array('a'=>'new','id'=>0))));
			}
			else
			{
				$message[0] = 1;
				$message[1] = "Hai già eliminato questa squadra";
			}
		}
		elseif($action == 'edit' || $action == 'new')
		{
			if(empty($_POST))
			{
				if($action == 'edit')
				{
					$contenttpl->assign('giocatori',$giocatoreObj->getGiocatoriByIdSquadra($id));
					$contenttpl->assign('datiSquadra',$utenteObj->getSquadraById($id));
				}
			}
			else
			{
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
								$data[$key] = $val;
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
						$squadra = $utenteObj->addSquadra($_POST['usernamenew'],$_POST['nome'],$amministratore,$password,$_POST['mail'],$lega);
						$squadreObj->setSquadraGiocatoreByArray($lega,$giocatori,$squadra);
						$message[0] = 0;
						$message[1] = "Squadra creata correttamente";
						$mailContent->assign('username',$_POST['usernamenew']);
						$mailContent->assign('squadra',$_POST['nome']);
						$mailContent->assign('password',$password);
						$mailContent->assign('lega',$legheObj->getLegaById($lega));
						$object = "Benvenuto nel FantaManajer!";
						//$mailContent->display(MAILTPLDIR.'mailBenvenuto.tpl.php');
						$mailObj->sendEmail($_POST['email'],$mailContent->fetch(MAILTPLDIR.'mailBenvenuto.tpl.php'),$object);
						header('Location: '. str_replace('&amp;','&',$linksObj->getLink('creaSquadra',array('a'=>'edit','id'=>$squadra))));
					}
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
if(isset($id))
	$contenttpl->assign('getId',$id);
if(isset($action))
$contenttpl->assign('getAction',$action);
?>
