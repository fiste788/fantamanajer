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

$classifica = $punteggiObj->getClassifica($_SESSION['idLega']);
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
$contenttpl->assign('posizioni',$punteggiObj->getPosClassifica($_SESSION['idLega']));
if(isset($_POST['passwordnew']) && isset($_POST['passwordnewrepeat']) )
{
	if($_POST['passwordnew'] == $_POST['passwordnewrepeat'])
	{
		if(strlen($_POST['passwordnew']) < 6)
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
$elencoSquadre = $utenteObj->getElencoSquadre();
$contenttpl->assign('elencosquadre',$elencoSquadre);
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
	$mediaMagic = 0;
	foreach($values as $key => $val)
	{
		$giocatori[$i]['idGioc'] = $val['idGioc'];
		$giocatori[$i]['nome'] = $val['cognome'] . " " . $val['nome'];
		$giocatori[$i]['ruolo'] = $ruoli[$val['ruolo']];
		$giocatori[$i]['club'] = $val['nomeClub'];
		$medieVoti = $votiObj->getMedieVoto($giocatori[$i]['idGioc']);
		$giocatori[$i]['votiAll'] = $medieVoti['mediaPunti'];
		$giocatori[$i]['voti'] = substr($giocatori[$i]['votiAll'],0,4);
		$giocatori[$i]['partite'] = $val['presenze'];
		$giocatori[$i]['partiteEff'] = $medieVoti['presenze'];
		$giocatori[$i]['gol'] = $val['gol'];
		$giocatori[$i]['assist'] = $val['assist'];
		$giocatori[$i]['votoEffAll'] = $medieVoti['mediaVoti'];
		$giocatori[$i]['votoEff'] = substr($giocatori[$i]['votoEffAll'],0,4);
		$mediaVoto += $giocatori[$i]['votoEffAll'];
		$mediaMagic += $giocatori[$i]['votiAll'];
		$mediaPartite += $giocatori[$i]['partite'];
		$mediaGol += $giocatori[$i]['gol'];
		$mediaAssist += $giocatori[$i]['assist'];
		$i++;
	}
	$contenttpl->assign('mediaVoto',substr($mediaVoto / $i,0,4));
	$contenttpl->assign('mediaVotoAll',$mediaVoto / $i);
	$contenttpl->assign('mediaMagicAll',$mediaMagic / $i);
	$contenttpl->assign('mediaMagic',substr($mediaMagic / $i,0,4));
	$contenttpl->assign('mediaPartite',substr($mediaPartite / $i,0,4));
	$contenttpl->assign('mediaPartiteAll',$mediaPartite / $i);
	$contenttpl->assign('mediaGol',substr($mediaGol / $i,0,4));
	$contenttpl->assign('mediaGolAll',$mediaGol / $i);
	$contenttpl->assign('mediaAssist',substr($mediaAssist / $i,0,4));
	$contenttpl->assign('mediaAssistAll',$mediaAssist / $i);
	$contenttpl->assign('giocatori',$giocatori);	
}

?>
