<?php
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'mail.inc.php');
require_once(CODEDIR . 'upload.code.php');	//IMPORTO IL CODE PER EFFETTUARE L'UPLOAD

$filterSquadra = NULL;
if(isset($_GET['squadra']))
	$filterSquadra = $_GET['squadra'];
	
$squadraDett = Utente::getSquadraById($filterSquadra);

$classifica = Punteggio::getClassificaByGiornata($squadraDett->idLega,GIORNATA);
$elencoSquadre = Utente::getElencoSquadre($squadraDett->idLega);
if($classifica != NULL) {
	foreach($classifica as $key => $val)
	{
		if($filterSquadra == $val->idUtente)
		{
			$contentTpl->assign('media',substr($classifica[$key]->punteggioMed,0,5));
			$contentTpl->assign('min',$classifica[$key]->punteggioMin);
			$contentTpl->assign('max',$classifica[$key]->punteggioMax);
		}
	}
}
if(isset($_POST['submit']))
{
	foreach($_POST as $key=>$val)
	{
		if($key != "passwordnew" && $key != "passwordnewrepeat")
		{
			if(empty($val))
				$message->error("Non hai compilato tutti i campi");
		}
	}
	if(!$message->show)
	{
		$password = "";
		if(!empty($_POST['passwordnew']) && !empty($_POST['passwordnewrepeat']))
		{
			if($_POST['passwordnew'] == $_POST['passwordnewrepeat'])
			{
				if(strlen($_POST['passwordnew']) > 6)
					$password = $_POST['passwordnew'];
				else
					$message->error("La password deve essere lunga almeno 6 caratteri");
			}
			else
				$message->error("Le 2 password non corrispondono");
		}
		if(isset($_POST['mail']))
		{
			if(!Mail::checkEmailAddress($_POST['mail']))
				$message->error("Mail non corretta");
			else
				$email = $_POST['mail'];
		}
		if(isset($_POST['nomeSquadra']))
		{
			$nomeSquadra = addslashes(stripslashes(trim($nomeSquadra)));
			if(Utente::getSquadraByNome($nomeSquadra,$filterSquadra) != FALSE)
				$message->error("Il nome della squadra è già presente");
		}
		else
			$nomeSquadra = addslashes(stripslashes(trim($squadraDett->nome)));
	}
	if(!$message->show)
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
		Utente::changeData($nomeSquadra,$nome,$cognome,$email,$abilitaMail,$password,$amministratore,$_SESSION['idSquadra']);
		$message->success("Dati modificati correttamente");
	}
	$layoutTpl->assign('message',$message);
}
if(isset($elencoSquadre[$filterSquadra - 1]))
{
	$idPrec = $filterSquadra - 1;
	$quickLinks->prec->href = Links::getLink('dettaglioSquadra',array('squadra'=>$idPrec));
	$quickLinks->prec->title = $elencoSquadre[$idPrec]->nome;
}	
else
	$quickLinks->prec = FALSE;

if(isset($elencoSquadre[$filterSquadra + 1]))
{
	$idSucc = $filterSquadra + 1;
	$quickLinks->succ->href = Links::getLink('dettaglioSquadra',array('squadra'=>$idSucc));
	$quickLinks->succ->title = $elencoSquadre[$idSucc]->nome;
}	
else
	$quickLinks->succ = FALSE;
$ruoli = array('P'=>'Por.','D'=>'Dif.','C'=>'Cen','A'=>'Att.');

$contentTpl->assign('giocatori',Giocatore::getGiocatoriByIdSquadraWithStats($filterSquadra));
$contentTpl->assign('squadra',$filterSquadra);
$contentTpl->assign('squadraDett',Utente::getSquadraById($filterSquadra));
$contentTpl->assign('classifica',$classifica);
$operationTpl->assign('elencoSquadre',$elencoSquadre);
$layoutTpl->assign('quickLinks',$quickLinks);
?>
