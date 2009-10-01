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

$squadra = NULL;
if(isset($_GET['squadra']))
	$squadra = $_GET['squadra'];
$contenttpl->assign('squadra',$squadra);
$classifica = $punteggiObj->getClassifica($_SESSION['legaView']);
foreach($classifica as $key => $val)
{
	if($squadra == $val['idUtente'])
	{
		$contenttpl->assign('media',substr($classifica[$key]['punteggioMed'],0,5));
		$contenttpl->assign('min',$classifica[$key]['punteggioMin']);
		$contenttpl->assign('max',$classifica[$key]['punteggioMax']);
	}
}
$contenttpl->assign('classifica',$classifica);
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
				$message[0] = 0;
				$message[1] = "Dati modificati correttamente";
			}
		}
		else
		{
			$message[0] = 1;
			$message[1] = "La password deve essere lunga almeno 6 caratteri";
		}
	}
	else
	{
		$message[0] = 1;
		$message[1] = "Le 2 password non corrispondono";
	}
	$contenttpl->assign('message',$message);
}

if(isset($elencoSquadre[$squadra-1]))
	$contenttpl->assign('squadraprec',($squadra-1));
else
	$contenttpl->assign('squadraprec',false);

if(isset($elencoSquadre[$squadra+1]))
	$contenttpl->assign('squadrasucc',($squadra+1));
else
	$contenttpl->assign('squadrasucc',false);
	
$contenttpl->assign('squadradett',$utenteObj->getSquadraById($squadra));

$ruoli = array('P'=>'Por.','D'=>'Dif.','C'=>'Cen','A'=>'Att.');
$values = $giocatoreObj->getGiocatoriByIdSquadraWithStats($squadra);
if(($squadra != NULL) && ($values))
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

?>
