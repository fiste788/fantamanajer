<?php
require_once(INCDIR.'utente.inc.php');
require_once(CODEDIR.'upload.code.php');	//IMPORTO IL CODE PER EFFETTUARE IL DOWNLOAD
require_once(INCDIR.'punteggi.inc.php');
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'voti.inc.php');

$giocatoreObj = new giocatore();
$punteggiObj = new punteggi();
$utenteObj = new utente();
$votiObj = new voti();

$filterSquadra = NULL;
if(isset($_GET['squadra']))
	$filterSquadra = $_GET['squadra'];

$classifica = $punteggiObj->getClassificaByGiornata($_SESSION['legaView'],GIORNATA);
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
$values = $giocatoreObj->getGiocatoriByIdSquadraWithStats($filterSquadra);
if(($filterSquadra != NULL) && ($values))
{
	$i = 0;
	$appo = 0;
	$mediaVoto = 0;
	$mediaPartite = 0;
	$mediaGol = 0;
	$mediaAssist = 0;
	$mediaPunti = 0;
	$nonpermedia=0;
	foreach($values as $key => $val)
	{
		$giocatori[$i]['idGioc'] = $val['idGioc'];
		$giocatori[$i]['nome'] = $val['cognome'] . " " . $val['nome'];
		$giocatori[$i]['ruolo'] = $ruoli[$val['ruolo']];
		$giocatori[$i]['club'] = $val['nomeClub'];
		//$medieVoti = $votiObj->getMedieVoto($giocatori[$i]['idGioc']);
		$giocatori[$i]['avgpunti'] = $val['avgpunti'];
		$giocatori[$i]['avgvoto'] = $val['avgvoto'];
		$giocatori[$i]['presenze'] = $val['presenze'];
		$giocatori[$i]['presenzeEff'] = $val['presenzeconvoto'];
		if($val['ruolo']=="P")
			$giocatori[$i]['gol'] = -$val['golSubiti'];
		else
		{
			$giocatori[$i]['gol'] = $val['gol'];
			$mediaGol += $giocatori[$i]['gol'];
		}
		$giocatori[$i]['assist'] = $val['assist'];
		$giocatori[$i]['espulsioni'] = $val['espulsioni'];
		$giocatori[$i]['ammonizioni'] = $val['ammonizioni'];
		
		$mediaVoto += $giocatori[$i]['avgvoto'];
		$mediaPunti += $giocatori[$i]['avgpunti'];
		$mediaPartite += $giocatori[$i]['presenze'];
		$mediaAssist += $giocatori[$i]['assist'];
		if($giocatori[$i]['presenzeEff']==0)
			$nonpermedia++;
		$i++;
	}
	$contenttpl->assign('mediaVoto',round($mediaVoto/($i-$nonpermedia),2));
	$contenttpl->assign('mediaPunti',round($mediaPunti/($i-$nonpermedia),2));
	$contenttpl->assign('mediaPartite',round($mediaPartite/$i,2));
	$contenttpl->assign('mediaGol',round($mediaGol/($i-3),2));
	$contenttpl->assign('mediaAssist',round($mediaAssist/$i,2));
	$contenttpl->assign('giocatori',$giocatori);
}

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
