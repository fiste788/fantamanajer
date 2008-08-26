 <?php 
require_once(INCDIR.'trasferimenti.inc.php');
require_once(INCDIR.'squadra.inc.php');
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'punteggi.inc.php');
require_once(INCDIR.'mail.inc.php');
require_once(INCDIR.'eventi.inc.php');
	
$punteggiObj = new punteggi();
$squadraObj = new squadra();
$mailObj = new mail();
$eventiObj = new eventi();
$squadraObj = new squadra();
$giocatoreObj = new giocatore();
$trasferimentiObj = new trasferimenti();

$squadra = $_SESSION['idsquadra'];
$acquisto = NULL;
$lasciato = NULL;
if(isset($_GET['squad']))
	$squadra = $_GET['squad'];

$ruo = array('Portiere','Difensori','Centrocampisti','Attaccanti');
$contenttpl->assign('ruo',$ruo);
$contenttpl->assign('elencosquadre',$squadraObj->getElencoSquadre());
$contenttpl->assign('squadra',$squadra);
$trasferimenti = $trasferimentiObj->getTrasferimentiByIdSquadra($squadra);
$contenttpl->assign('trasferimenti',$trasferimenti);
$numTrasferimenti = count($trasferimenti);
$contenttpl->assign('giocSquadra',$giocatoreObj->getGiocatoriByIdSquadra($squadra));
$playerFree = array();
foreach($ruo as $key=>$val)
	$playerFree = array_merge($playerFree,$giocatoreObj->getFreePlayer(substr($val,0,1)));
$contenttpl->assign('freePlayer',$playerFree);

$contenttpl->assign('numTrasferimenti',$numTrasferimenti);
if($_SESSION['logged'] && $_SESSION['idsquadra'] == $squadra)
{
	if($numTrasferimenti <15 )
	{
		$giocatoreAcquistatoOld = $giocatoreObj->getGiocatoreAcquistatoByIdSquadra($_SESSION['idsquadra']);
		$giocatoreLasciatoOld = $giocatoreObj->getGiocatoreLasciatoByIdSquadra($_SESSION['idsquadra']);

		if($giocatoreAcquistatoOld != FALSE)
			$acquisto = $giocatoreAcquistatoOld[0];
		if($giocatoreLasciatoOld != FALSE)
			$lasciato = $giocatoreLasciatoOld[0];
		if(isset($_POST['acquista']))
			$acquisto = $_POST['acquista'];
		if(isset($_POST['lascia']))
			$lasciato = $_POST['lascia'];

		if(isset($_POST['submit']) && $_POST['submit'] == 'Cancella acq.')
		{
			$giocatoreObj-> unsetGiocatoreLasciatoByIdGioc($giocatoreLasciatoOld[0]);
			$giocatoreObj-> unsetGiocatoreAcquistatoByIdGioc($giocatoreAcquistatoOld[0]);
			$messaggio[] = 0;
			$messaggio[] = 'Cancellazione eseguita con successo';
			$contenttpl->assign('messaggio',$messaggio);
			$acquisto = NULL;
			$lasciato = NULL;
		}
	
		if($acquisto != NULL)
		{
			$appo[] = $acquisto;
			$acquistoDett = $giocatoreObj->getGiocatoriByArray($appo);
		}
		if($lasciato != NULL)
		{
			$appo2[] = $lasciato;
			$lasciatoDett = $giocatoreObj->getGiocatoriByArray($appo2);
		}
		$numTrasferimenti = $squadraObj->getNumberTransfert($squadra);

		if(isset($_POST['submit']) && $_POST['submit'] == 'OK')
		{
			if(isset($_POST['acquista']) && !empty($_POST['acquista']) && isset($_POST['lascia']) && !empty($_POST['lascia']) && ($giocatoreAcquistatoOld[0] != $acquisto || $giocatoreLasciatoOld[0] != $lasciato))
			{
				if($numTrasferimenti < 2)
				{
					if($acquistoDett[$acquisto]['idSquadraAcquisto'] != 0)
					{
						$classifica = $punteggiObj->getClassifica();
						$squadre = $squadraObj->getElencoSquadre();
						foreach ($classifica as $key => $val)
						{
							$classificaNew[$key] = $val[0];
						}
						$posSquadraOld =  array_search($acquistoDett[$acquisto]['idSquadraAcquisto'],$classificaNew);
						$posSquadraNew = array_search($_SESSION['idsquadra'],$classificaNew);
						if($posSquadraNew > $posSquadraOld)
						{
							$giocatoreObj-> unsetGiocatoreLasciatoByIdSquadra($acquistoDett[$acquisto]['idSquadraAcquisto']);
							$giocatoreObj-> unsetGiocatoreAcquistatoByIdGioc($acquisto);
							$squadraObj->decreaseNumberTransfert($acquistoDett[$acquisto]['idSquadraAcquisto']);
							$body = 'Il giocatore ' . $acquistoDett[$acquisto]['Nome'] . $acquistoDett[$acquisto]['Cognome'] . ' che volevi acquistare è stato selezionata da un altra squadra. Recati sul sito e seleziona un altro giocatore entro il giorno prima della fine della giornata';
							$mailObj->sendEmail($squadre[$acquistoDett[0][3]-1][4],$body,'Giocatore Rubato');
							$acquistoDett[$acquisto]['idSquadraAcquisto'] = 0; //ALMENO ENTRA NELL'IF SUCCESSIVO
						}
						else
						{
							$messaggio[0] = 1;
							$messaggio[1] = 'Un altra squadra inferiore di te ha già selezionato questo giocatore';
							$contenttpl->assign('messaggio',$messaggio);
							$acquisto = NULL;
							$lasciato = NULL;
						}
					}
					if($acquistoDett[$acquisto]['idSquadraAcquisto'] == 0)
					{
						if($giocatoreAcquistatoOld != FALSE)
						{
							$giocatoreObj-> unsetGiocatoreLasciatoByIdGioc($giocatoreLasciatoOld[0]);
							$giocatoreObj-> unsetGiocatoreAcquistatoByIdGioc($giocatoreAcquistatoOld[0]);
						}
						$giocatoreObj->setGiocatoreAcquistatoByIdGioc($acquisto,$_SESSION['idsquadra']);
						$giocatoreObj->setGiocatoreLasciatoByIdGioc($lasciato);
						$squadraObj->increaseNumberTransfert($_SESSION['idsquadra']);
						$messaggio[] = 0;
						$messaggio[] = 'Operazione eseguita con successo';
						$contenttpl->assign('messaggio',$messaggio);
						$eventiObj->addEvento('2',$_SESSION['idsquadra']);
					}
				}
				else
				{
					$messaggio[] = 2;
					$messaggio[] = 'Hai già cambiato 2 volte il tuo acquisto';
					$contenttpl->assign('messaggio',$messaggio);
				}
			
			}
			elseif($giocatoreAcquistatoOld[0] == $acquisto && $giocatoreLasciatoOld[0] == $lasciato)
			{
				$messaggio[] = 2;
				$messaggio[] = 'Hai già selezionato questi giocatori per l\'acquisto';
				$contenttpl->assign('messaggio',$messaggio);
			}
			else
			{
				$messaggio[] = 1;
				$messaggio[] = 'Non hai compilato correttamente';
				$contenttpl->assign('messaggio',$messaggio);
			}
		}
		$contenttpl->assign('giocAcquisto',$acquisto);
		$contenttpl->assign('giocLasciato',$lasciato);
	
		$giocatoreAcquistatoOld = $giocatoreObj->getGiocatoreAcquistatoByIdSquadra($_SESSION['idsquadra']);	
		if($giocatoreAcquistatoOld != FALSE)
			$contenttpl->assign('isset',$giocatoreAcquistatoOld);
	}
	else
	{
		$messaggio[] = 2;
		$messaggio[] = 'Hai raggiunto il limite di trasferimenti';
		$contenttpl->assign('messaggio',$messaggio);
	}
}
?>
