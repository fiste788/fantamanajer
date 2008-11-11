<?php 
require_once(INCDIR.'trasferimenti.inc.php');
require_once(INCDIR.'utente.inc.php');
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'selezione.inc.php');
require_once(INCDIR.'punteggi.inc.php');
require_once(INCDIR.'mail.inc.php');
require_once(INCDIR.'eventi.inc.php');
	
$punteggiObj = new punteggi();
$utenteObj = new utente();
$mailObj = new mail();
$eventiObj = new eventi();
$utenteObj = new utente();
$giocatoreObj = new giocatore();
$selezioneObj = new selezione();
$trasferimentiObj = new trasferimenti();

$squadra = $_SESSION['idSquadra'];
$acquisto = NULL;
$lasciato = NULL;
$flag=0;
if(isset($_GET['squad']))
	$squadra = $_GET['squad'];
if(isset($_POST['squad']))
	$squadra = $_POST['squad'];
$ruo = array('Portiere','Difensori','Centrocampisti','Attaccanti');
$contenttpl->assign('ruo',$ruo);
$contenttpl->assign('elencosquadre',$utenteObj->getElencoSquadre());
$contenttpl->assign('squadra',$squadra);
$trasferimenti = $trasferimentiObj->getTrasferimentiByIdSquadra($squadra);
$contenttpl->assign('trasferimenti',$trasferimenti);
$numTrasferimenti = count($trasferimenti);
$contenttpl->assign('giocSquadra',$giocatoreObj->getGiocatoriByIdSquadra($squadra));
$playerFree = array();
foreach($ruo as $key => $val)
	$playerFree = array_merge($playerFree,$giocatoreObj->getFreePlayer(substr($val,0,1)));
$contenttpl->assign('freePlayer',$playerFree);

$contenttpl->assign('numTrasferimenti',$numTrasferimenti);
if($_SESSION['logged'] && $_SESSION['idSquadra'] == $squadra)
{
	if($numTrasferimenti <MAXTRASFERIMENTI )
	{
		$selezione = $selezioneObj->getSelezioneByIdSquadra($_SESSION['idSquadra']);

		if(!empty($selezione))
		{
			$acquisto = $selezione['giocNew'];
			$lasciato = $selezione['giocOld'];
		}
		if(isset($_POST['acquista']))
			$acquisto = $_POST['acquista'];
		if(isset($_POST['lascia']))
			$lasciato = $_POST['lascia'];

		if(isset($_POST['submit']) && $_POST['submit'] == 'Cancella acq.')
		{
			$selezioneObj->unsetSelezioneByIdSquadra($_SESSION['idSquadra']);
			$messaggio[] = 0;
			$messaggio[] = 'Cancellazione eseguita con successo';
			$contenttpl->assign('messaggio',$messaggio);
			$acquisto = NULL;
			$lasciato = NULL;
		}
	
		if($acquisto != NULL)
			$acquistoDett = $giocatoreObj->getGiocatoreById($acquisto);
		if($lasciato != NULL)
			$lasciatoDett = $giocatoreObj->getGiocatoreById($lasciato);
		$numSelezioni = $selezioneObj->getNumberSelezioni($squadra);

		if(isset($_POST['submit']) && $_POST['submit'] == 'OK')
		{
			if($lasciatoDett[$lasciato]['ruolo'] == $acquistoDett[$acquisto]['ruolo'])
			{
				if(isset($_POST['acquista']) && !empty($_POST['acquista']) && isset($_POST['lascia']) && !empty($_POST['lascia']) && ($selezione['giocNew'] != $acquisto || $selezione['giocOld'] != $lasciato))
				{
					if($numSelezioni < NUMSELEZIONI)
					{	
						$squadraOld = $selezioneObj->checkFree($acquisto,$_SESSION['idLega']);
						if($squadraOld != FALSE)
						{
							$classifica = $punteggiObj->getClassifica($_SESSION['idLega']);
							$squadre = $squadraObj->getElencoSquadre();
							foreach ($classifica as $key => $val)
								$classificaNew[$key] = $val[0];
							$posSquadraOld =  array_search($squadraOld,$classificaNew);
							$posSquadraNew = array_search($_SESSION['idSquadra'],$classificaNew);
							if($posSquadraNew > $posSquadraOld)
							{
								$selezioneObj->updateGioc($acquisto,$lasciato,$_SESSION['idLega'],$_SESSION['idSquadra']);
								$body = 'Il giocatore ' . $acquistoDett[$acquisto]['nome'] . $acquistoDett[$acquisto]['cognome'] . ' che volevi acquistare è stato selezionata da un altra squadra. Recati sul sito e seleziona un altro giocatore entro il giorno prima della fine della giornata';
								$appo = $squadre[$acquistoDett[$acquisto]['idSquadraAcquisto']];
								$mailObj->sendEmail($squadre[$appo]['mail'],$body,'Giocatore Rubato');
							}
							else
							{
								$messaggio[0] = 1;
								$messaggio[1] = 'Un altra squadra inferiore di te ha già selezionato questo giocatore';
								$contenttpl->assign('messaggio',$messaggio);
								$acquisto = NULL;
								$lasciato = NULL;
								$flag = 1;
							}
						}
						else
						{
							$selezioneObj->updateGioc($acquisto,$lasciato,$_SESSION['idLega'],$_SESSION['idSquadra']);
							$messaggio[0] = 0;
							$messaggio[1] = 'Operazione eseguita con successo';
							$contenttpl->assign('messaggio',$messaggio);
							$eventiObj->addEvento('2',$_SESSION['idSquadra']);
						}
					}
					else
					{
						$flag = 1;
						$messaggio[0] = 2;
						$messaggio[1] = 'Hai già cambiato ' . NUMSELEZIONI . ' volte il tuo acquisto';
						$contenttpl->assign('messaggio',$messaggio);
					}
			
				}
				elseif($selezione['giocNew'] == $acquisto && $selezione['giocOld'] == $lasciato)
				{
					$messaggio[0] = 2;
					$messaggio[1] = 'Hai già selezionato questi giocatori per l\'acquisto';
					$contenttpl->assign('messaggio',$messaggio);
				}
				else
				{
					$messaggio[0] = 1;
					$messaggio[1] = 'Non hai compilato correttamente';
					$contenttpl->assign('messaggio',$messaggio);
				}
			}
			else
			{
				$messaggio[0] = 1;
				$messaggio[1] = 'I giocatori devono avere lo stesso ruolo';
				$contenttpl->assign('messaggio',$messaggio);
			}
		}
		if($flag == 1)
		{
			$acquisto = $selezione['giocNew'];
			$lasciato = $selezione['giocOld'];
		}
		$contenttpl->assign('giocAcquisto',$acquisto);
		$contenttpl->assign('giocLasciato',$lasciato);
	
		$giocatoreAcquistatoOld = $selezioneObj->getSelezioneByIdSquadra($_SESSION['idSquadra']);	
		if(!empty($giocatoreAcquistatoOld))
			$contenttpl->assign('isset',$giocatoreAcquistatoOld);
	}
	else
	{
		$messaggio[0] = 2;
		$messaggio[1] = 'Hai raggiunto il limite di trasferimenti';
		$contenttpl->assign('messaggio',$messaggio);
	}
}
?>