<?php
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(CODEDIR . 'upload.code.php');	//IMPORTO IL CODE PER EFFETTUARE IL DOWNLOAD

$utenteObj = new utente();
$punteggioObj = new punteggio();
$giocatoreObj = new giocatore();

$filterSquadra = NULL;
if(isset($_GET['squadra']))
	$filterSquadra = $_GET['squadra'];

$classifica = $punteggioObj->getClassificaByGiornata($_SESSION['legaView'],GIORNATA);
$elencoSquadre = $utenteObj->getElencoSquadre($_SESSION['legaView']);
foreach($classifica as $key => $val)
{
	if($filterSquadra == $val['idUtente'])
	{
		$contenttpl->assign('media',substr($classifica[$key]['punteggioMed'],0,5));
		$contenttpl->assign('min',$classifica[$key]['punteggioMin']);
		$contenttpl->assign('max',$classifica[$key]['punteggioMax']);
	}
}

if(isset($_POST['passwordnew']) && isset($_POST['passwordnewrepeat']) )
{
	if($_POST['passwordnew'] == $_POST['passwordnewrepeat'])
	{
		if(strlen($_POST['passwordnew']) > 6)
		{
			if($_SESSION['usertype'] == "superadmin")
				$_POST['amministratore'] = 2;
			elseif($_SESSION['usertype'] == "admin")
				$_POST['amministratore'] = 1;
			unset($_POST['passwordnewrepeat']);
			if( (isset($_POST['nomeProp'])) || (isset($_POST['cognome'])) || (isset($_POST['usernamenew'])) || (isset($_POST['mail'])) || (isset($_POST['nome'])) || (isset($_POST['passwordnew'])) )
			{
				$utenteObj->changeData($_POST,$_SESSION['idSquadra']);
				$message['level'] = 0;
				$message['text'] = "Dati modificati correttamente";
			}
		}
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
	$layouttpl->assign('message',$message);
}

$ruoli = array('P'=>'Por.','D'=>'Dif.','C'=>'Cen','A'=>'Att.');
//echo "<pre>".print_r($giocatoreObj->getGiocatoriByIdSquadraWithStats($filterSquadra),1)."</pre>";
$contenttpl->assign('giocatori',$giocatoreObj->getGiocatoriByIdSquadraWithStats($filterSquadra));
$contenttpl->assign('squadra',$filterSquadra);
$contenttpl->assign('squadraDett',$utenteObj->getSquadraById($filterSquadra));
$contenttpl->assign('classifica',$classifica);
$operationtpl->assign('elencoSquadre',$elencoSquadre);
if(isset($elencoSquadre[$filterSquadra-1]))
	$operationtpl->assign('squadraPrec',($filterSquadra-1));
else
	$operationtpl->assign('squadraPrec',false);

if(isset($elencoSquadre[$filterSquadra+1]))
	$operationtpl->assign('squadraSucc',($filterSquadra+1));
else
	$operationtpl->assign('squadraSucc',false);
?>
