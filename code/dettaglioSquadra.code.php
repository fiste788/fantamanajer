<?php
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'mail.inc.php');
require_once(CODEDIR . 'upload.code.php');	//IMPORTO IL CODE PER EFFETTUARE L'UPLOAD

$utenteObj = new utente();
$punteggioObj = new punteggio();
$giocatoreObj = new giocatore();
$mailObj = new mail();

$filterSquadra = NULL;
if(isset($_GET['squadra']))
	$filterSquadra = $_GET['squadra'];
	
$squadraDett = $utenteObj->getSquadraById($filterSquadra);

$classifica = $punteggioObj->getClassificaByGiornata($squadraDett['idLega'],GIORNATA);
$elencoSquadre = $utenteObj->getElencoSquadre($squadraDett['idLega']);
foreach($classifica as $key => $val)
{
	if($filterSquadra == $val['idUtente'])
	{
		$contenttpl->assign('media',substr($classifica[$key]['punteggioMed'],0,5));
		$contenttpl->assign('min',$classifica[$key]['punteggioMin']);
		$contenttpl->assign('max',$classifica[$key]['punteggioMax']);
	}
}
if(isset($_POST['submit']))
{
	foreach($_POST as $key=>$val)
	{
		if($key != "passwordnew" && $key != "passwordnewrepeat")
		{
			if(empty($val))
			{
				echo $key;
				$message['level'] = 1;
				$message['text'] = "Non hai compilato tutti i campi";
			}
		}
	}
	if(!isset($message))
	{
		$password ="";
		if(!empty($_POST['passwordnew']) && !empty($_POST['passwordnewrepeat']))
		{
			if($_POST['passwordnew'] == $_POST['passwordnewrepeat'])
			{
				if(strlen($_POST['passwordnew']) > 6)
					$password = $_POST['passwordnew'];
				else
				{
					$message['level'] = 1;
					$message['text'] = "La password deve essere lunga almeno 6 caratteri";
				}
			}
			else
			{
				$message['level'] = 1;
				$message['text'] = "Le 2 password non corrispondono";
			}
		}
		if(isset($_POST['mail']))
		{
			if(!$mailObj->checkEmailAddress($_POST['mail']))
			{
				$message['level'] = 1;
				$message['text'] = "Mail non corretta";
			}
			else
				$email = $_POST['mail'];
		}
		else
		{
		
		}

		if(isset($_POST['nomeSquadra']))
		{
			$nomeSquadra = addslashes(stripslashes(trim($nomeSquadra)));
			if($utenteObj->getSquadraByNome($nomeSquadra,$filterSquadra) != FALSE)
			{
				$message['level'] = 1;
				$message['text'] = "Il nome della squadra è già presente";
			}
		}
		else
			$nomeSquadra = addslashes(stripslashes(trim($squadraDett['nome'])));
	}
	if(!isset($message))
	{
		if($_SESSION['usertype'] == "superadmin")
			$amministratore = 2;
		elseif($_SESSION['usertype'] == "admin")
			$amministratore = 1;
		else
			$amministratore = 0;
		if($abilitaMail = 'on')
			$abilitaMail = 1;
		else
			$abilitaMail = 0;
		$nome = addslashes(stripslashes(trim($_POST['nomeProp'])));
		$cognome = addslashes(stripslashes(trim($_POST['cognome'])));
		$utenteObj->changeData($nomeSquadra,$nome,$cognome,$email,$abilitaMail,$password,$amministratore,$_SESSION['idSquadra']);
		$message['level'] = 0;
		$message['text'] = "Dati modificati correttamente";
	}
	$layouttpl->assign('message',$message);
}

if(isset($elencoSquadre[$filterSquadra-1]))
{
	$idPrec = $filterSquadra -1;
	$quickLinks['prec']['href'] = $contenttpl->linksObj->getLink('dettaglioSquadra',array('squadra'=>$idPrec));
	$quickLinks['prec']['title'] = $elencoSquadre[$idPrec]['nome'];
}	
else
{
	$idPrec = FALSE;
	$quickLinks['prec'] = FALSE;
}

if(isset($elencoSquadre[$filterSquadra+1]))
{
	$idSucc = $filterSquadra +1;
	$quickLinks['succ']['href'] = $contenttpl->linksObj->getLink('dettaglioSquadra',array('squadra'=>$idSucc));
	$quickLinks['succ']['title'] = $elencoSquadre[$idSucc]['nome'];
}	
else
{
	$idSucc = FALSE;
	$quickLinks['succ'] = FALSE;
}
$ruoli = array('P'=>'Por.','D'=>'Dif.','C'=>'Cen','A'=>'Att.');

$contenttpl->assign('giocatori',$giocatoreObj->getGiocatoriByIdSquadraWithStats($filterSquadra));
$contenttpl->assign('squadra',$filterSquadra);
$contenttpl->assign('squadraDett',$utenteObj->getSquadraById($filterSquadra));
$contenttpl->assign('classifica',$classifica);
$operationtpl->assign('elencoSquadre',$elencoSquadre);
$operationtpl->assign('squadraPrec',$idPrec);
$operationtpl->assign('squadraSucc',$idSucc);
$layouttpl->assign('quickLinks',$quickLinks);
?>
