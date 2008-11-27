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
$mailContentObj = new Savant2();

$squadra = $_SESSION['idSquadra'];
$acquisto = NULL;
$lasciato = NULL;
$flag = 0;
$appo = 0;
if(isset($_GET['squad']))
	$squadra = $_GET['squad'];
if(isset($_POST['squad']))
	$squadra = $_POST['squad'];
$ruo = array('Portiere','Difensori','Centrocampisti','Attaccanti');
$contenttpl->assign('ruo',$ruo);
$contenttpl->assign('elencosquadre',$utenteObj->getElencoSquadre());
$contenttpl->assign('squadra',$squadra);
$trasferimenti = $trasferimentiObj->getTrasferimentiByIdSquadra($squadra);
$numTrasferimenti = count($trasferimenti);
$playerFree = array();
foreach($ruo as $key => $val)
	$playerFree = array_merge($playerFree,$giocatoreObj->getFreePlayer(substr($val,0,1),$_SESSION['idLega']));

$trasferiti = $giocatoreObj->getGiocatoriTrasferiti($_SESSION['idSquadra']);
/*
 * Quì effettuo il trasferimento diretto
 */ 
if($trasferiti != FALSE)
{
	$appo = 1;
	$numTrasferiti = 0;
	$contenttpl->assign('trasferiti',$trasferiti);
	foreach($trasferiti as $key => $val)
		$freePlayerByRuolo[$val['idGioc']] = $giocatoreObj->getFreePlayer($val['ruolo'],$_SESSION['idLega']);
	$contenttpl->assign('freePlayerByRuolo',$freePlayerByRuolo);
	foreach($trasferiti as $masterKey => $masterVal)
	{
		if($numTrasferimenti < MAXTRASFERIMENTI )
		{
			if(isset($_POST['submit']) && $_POST['submit'] == 'OK')
			{
				if(isset($_POST['acquista'][$masterKey]) && !empty($_POST['acquista'][$masterKey]) )
				{
					$giocatoreAcquistato = $giocatoreObj->getGiocatoreById($_POST['acquista'][$masterKey]);
					$flag = 0;
					foreach($playerFree as $key => $val)
						if($val['idGioc'] == $_POST['acquista'][$masterKey])
							$flag = 1;
					if($flag != 0)
					{
						$giocatoreLasciato = $giocatoreObj->getGiocatoreById($masterVal['idGioc']);
						if($giocatoreAcquistato[$_POST['acquista'][$masterKey]]['ruolo'] == $giocatoreLasciato[$masterVal['idGioc']]['ruolo'])
						{
							$trasferimentiObj->transfer($masterVal['idGioc'],$_POST['acquista'][$masterKey],$squadra,$_SESSION['idLega']);
							$appo = 0;
							$numTrasferiti ++;
							$selezione = $selezioneObj->getSelezioneByIdSquadra($_SESSION['idSquadra']);
							if($selezione['giocOld'] == $masterVal['idGioc']);
								$selezioneObj->unsetSelezioneByIdSquadra($squadra);
							$trasferimenti = $trasferimentiObj->getTrasferimentiByIdSquadra($squadra);
							$numTrasferimenti = count($trasferimenti);
							foreach($ruo as $key => $val)
								$playerFree = array_merge($playerFree,$giocatoreObj->getFreePlayer(substr($val,0,1),$_SESSION['idLega']));
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
						$messaggio[0] = 1;
						$messaggio[1] = 'Il giocatore non è libero';
					}
				}
				elseif($appo == 1)
				{
					$messaggio[0] = 1;
					$messaggio[1] = 'Non hai compilato correttamente';
				}
			}
		}
		else
		{
			$messaggio[0] = 2;
			$messaggio[1] = 'Hai raggiunto il limite di trasferimenti';
		}
	}
	if($numTrasferiti == count($trasferiti))
	{
		unset($contenttpl->generalMessage);
		unset($contenttpl->trasferiti);
		unset($_POST);
	}
	if(isset($messaggio))
		$contenttpl->assign('messaggio',$messaggio);
}



/*
 * Quì seleziono il giocatore per il trasferimento
 */   
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
								$mailContent->assign('giocatore',$acquistoDett[$acquisto]['nome'] . ' ' . $acquistoDett[$acquisto]['cognome']);s
								$appo = $squadre[$acquistoDett[$acquisto]['idSquadraAcquisto']];
								$mailObj->sendEmail($squadre[$appo]['mail'],$mailContent->fetch(MAILTPLDIR.'mailGiocatoreRubato.tpl.php'),'Giocatore rubato!');
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
							$eventiObj->addEvento('2',$_SESSION['idSquadra'],$_SESSION['idLega']);
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

$contenttpl->assign('giocSquadra',$giocatoreObj->getGiocatoriByIdSquadra($squadra));
$contenttpl->assign('freePlayer',$playerFree);
$contenttpl->assign('trasferimenti',$trasferimenti);
$contenttpl->assign('numTrasferimenti',$numTrasferimenti);
?>