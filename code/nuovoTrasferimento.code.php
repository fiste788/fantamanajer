<?php
require_once(INCDIR . 'trasferimento.db.inc.php');
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'lega.db.inc.php');

$trasferimentoObj = new trasferimento();	
$utenteObj = new utente();
$giocatoreObj = new giocatore();
$legaObj = new lega();

$filterSquadra = NULL;
$filterLega = NULL;
if(isset($_POST['squadra']))
	$filterSquadra = $_POST['squadra'];
if(isset($_POST['lega']))
	$filterLega = $_POST['lega'];
if($_SESSION['usertype'] == 'admin')
	$filterLega = $_SESSION['idLega'];

$trasferimenti = $trasferimentoObj->getTrasferimentiByIdSquadra($filterSquadra);
$numTrasferimenti = count($trasferimenti);

if($numTrasferimenti < $_SESSION['datiLega']->numTrasferimenti )
{
	if(isset($_POST['submit']) && $_POST['submit'] == 'OK')
	{
		if(isset($_POST['acquista']) && !empty($_POST['acquista']) && isset($_POST['lascia']) && !empty($_POST['lascia']) )
		{
			$giocatoreAcquistato = $giocatoreObj->getGiocatoreById($_POST['acquista']);
			$giocatoreLasciato = $giocatoreObj->getGiocatoreById($_POST['lascia']);
			if($giocatoreAcquistato[$_POST['acquista']]->ruolo == $giocatoreLasciato[$_POST['lascia']]->ruolo)
			{
				$trasferimentoObj->transfer($_POST['lascia'],$_POST['acquista'],$filterSquadra,$filterLega);
				$message->success('Trasferimento effettuato correttamente');
			}
			else
				$message->warning('I giocatori devono avere lo stesso ruolo');
		}
		else
			$message->error('Non hai compilato correttamente');
	}
}
else
	$message->error('Ha raggiunto il limite di trasferimenti');
	
$ruoli = array('P'=>'Portiere','D'=>'Difensori','C'=>'Centrocampisti','A'=>'Attaccanti');

$contentTpl->assign('trasferimenti',$trasferimenti);
$contentTpl->assign('numTrasferimenti',$numTrasferimenti);
$contentTpl->assign('ruoli',$ruoli);
$contentTpl->assign('giocSquadra',$giocatoreObj->getGiocatoriByIdSquadra($filterSquadra));
$contentTpl->assign('freePlayer',$giocatoreObj->getGiocatoriNotSquadra($filterSquadra,$filterLega));
$contentTpl->assign('squadra',$filterSquadra);
$contentTpl->assign('lega',$filterLega);
$operationTpl->assign('squadra',$filterSquadra);
$operationTpl->assign('elencoLeghe',$legaObj->getLeghe());
$operationTpl->assign('lega',$filterLega);
if($filterLega != NULL)
{
	$operationTpl->assign('elencoSquadre',$utenteObj->getElencoSquadreByLega($filterLega));
	$contentTpl->assign('elencoSquadre',$utenteObj->getElencoSquadreByLega($filterLega));
}
?>
