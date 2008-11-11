<?php
require_once(INCDIR.'trasferimenti.inc.php');
require_once(INCDIR.'utente.inc.php');
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'eventi.inc.php');
require_once(INCDIR.'leghe.inc.php');
	
$eventiObj = new eventi();
$utenteObj = new utente();
$giocatoreObj = new giocatore();
$trasferimentiObj = new trasferimenti();
$legheObj = new leghe();

$squadra = NULL;
$lega = NULL;
$acquisto = NULL;
$lasciato = NULL;
if(isset($_POST['lega']))
	$lega = $_POST['lega'];
if(isset($_POST['squad']))
	$squadra = $_POST['squad'];
if($_SESSION['usertype'] == 'admin')
	$lega = $_SESSION['idLega'];
$ruo = array('Portiere','Difensori','Centrocampisti','Attaccanti');
$contenttpl->assign('ruo',$ruo);

$contenttpl->assign('elencoleghe',$legheObj->getLeghe());
$contenttpl->assign('lega',$lega);

/*foreach($legheObj->getLeghe() as $key => $val)
	$appo[$val['idLega']] = $utenteObj->getElencoSquadreByLega($val['idLega']);
$contenttpl->assign('elencojs',$appo);*/

if($lega != NULL)
{
	$contenttpl->assign('elencosquadre',$utenteObj->getElencoSquadreByLega($lega));
	$contenttpl->assign('squadra',$squadra);
}

$trasferimenti = $trasferimentiObj->getTrasferimentiByIdSquadra($squadra);
$contenttpl->assign('trasferimenti',$trasferimenti);
$numTrasferimenti = count($trasferimenti);

$contenttpl->assign('numTrasferimenti',$numTrasferimenti);
if($numTrasferimenti <MAXTRASFERIMENTI )
{
	if(isset($_POST['submit']) && $_POST['submit'] == 'OK')
	{
		if(isset($_POST['acquista']) && !empty($_POST['acquista']) && isset($_POST['lascia']) && !empty($_POST['lascia']) )
		{
			$giocatoreAcquistato = $giocatoreObj->getGiocatoreById($_POST['acquista']);
			$playerFree = $giocatoreObj->getGiocatoriNotSquadra($squadra);
			$flag = 0;
			foreach($playerFree as $key => $val)
				if($val['idGioc'] == $_POST['acquista'])
					$flag = 1;
			if($flag != 0)
			{
				$giocatoreLasciato = $giocatoreObj->getGiocatoreById($_POST['lascia']);
				if($giocatoreAcquistato[$_POST['acquista']]['ruolo'] == $giocatoreLasciato[$_POST['lascia']]['ruolo'])
				{
					$trasferimentiObj->transfer($_POST['lascia'],$_POST['acquista'],$squadra,$_SESSION['idLega']);
					$messaggio[0] = 0;
					$messaggio[1] = 'Trasferimento effettuato correttamente';
				}
				else
				{
					$messaggio[0] = 1;
					$messaggio[1] = 'I giocatori devono avere lo stesso ruolo';
				}
			}
			else
		{
			$messaggio[] = 1;
			$messaggio[] = 'Il giocatore non è libero';
		}
		}
		else
		{
			$messaggio[] = 1;
			$messaggio[] = 'Non hai compilato correttamente';
		}
	}
}
else
{
	$messaggio[] = 2;
	$messaggio[] = 'Hai raggiunto il limite di trasferimenti';
}
if(isset($messaggio))
	$contenttpl->assign('messaggio',$messaggio);
	
$contenttpl->assign('giocSquadra',$giocatoreObj->getGiocatoriByIdSquadra($squadra));
$contenttpl->assign('freePlayer',$giocatoreObj->getGiocatoriNotSquadra($squadra));
?>
