<?php
function filter($var)
{
	if($var != '-')
		return $var;
}	

require(INCDIR.'squadra.inc.php');
require(CODEDIR.'upload.code.php');	//IMPORTO IL CODE PER EFFETTUARE IL DOWNLOAD
require(INCDIR.'punteggi.inc.php');

$squadra = NULL;
if(isset($_GET['squadra']))
	$squadra = $_GET['squadra'];

$contenttpl->assign('squadra',$squadra);
$contenttpl->assign('data', 0);

$punteggiObj = new punteggi();
$classifica = $punteggiObj->getClassifica();
foreach($classifica as $key=>$val)
{
	if($squadra == $val['IdSquadra'])
	{
		$contenttpl->assign('media',substr($classifica[$key]['punteggioMed'],0,5));
		$contenttpl->assign('min',$classifica[$key]['punteggioMin']);
		$contenttpl->assign('max',$classifica[$key]['punteggioMax']);
	}
}
$contenttpl->assign('classifica',$classifica);
$contenttpl->assign('posizioni',$punteggiObj->getPosClassifica($classifica));
$squadraObj = new squadra();
if(isset($_POST['passwordnew']) && isset($_POST['passwordnewrepeat']) )
{
	if($_POST['passwordnew'] == $_POST['passwordnewrepeat'])
	{
		unset($_POST['passwordnewrepeat']);
		if( (isset($_POST['nomeProp'])) || (isset($_POST['cognome'])) || (isset($_POST['usernamenew'])) || (isset($_POST['mail'])) || (isset($_POST['passwordnew'])) )
			$contenttpl->assign('data',$squadraObj->changeData($_POST,$_SESSION['idsquadra']));
	}
	else
		$contenttpl->assign('data',1);
}
$elencoSquadre = $squadraObj->getElencoSquadre();
$contenttpl->assign('elencosquadre',$elencoSquadre);
$contenttpl->assign('squadradett',$squadraObj->getSquadraById($squadra));

$ruoli = array('P'=>'Por.','D'=>'Dif.','C'=>'Cen','A'=>'Att.');
require(INCDIR.'giocatore.inc.php');
$giocatoreObj = new giocatore();
if($squadra != NULL && $squadra > 0 && $squadra < 9)
{
	$values = $giocatoreObj->getGiocatoriByIdSquadra($squadra);
	$i = 0;
	$appo = 0;
	$mediaVoto = 0;
	$mediaPartite = 0;
	foreach($values as $key=>$val)
	{
		$giocatori[$i]['nome'] = ucwords(mb_strtolower(utf8_encode($val[1]),"UTF-8")) . " " . utf8_encode($val[2]);
		$giocatori[$i]['ruolo'] = $ruoli[$val[3]];
		$giocatori[$i]['club'] = $val[5];
		$voti = explode(';',$val[6]);
		$media = array_sum($voti);
		$partiteGiocate = count(array_filter($voti,"filter"));
		if($partiteGiocate != 0)
		{
			$giocatori[$i]['voti'] = substr($media / $partiteGiocate,0,4);
			$giocatori[$i]['votiAll'] = $media / $partiteGiocate;
			$appo ++;
		}
		else
		{
			$giocatori[$i]['voti'] = 0;
			$giocatori[$i]['votiAll'] = 0;
		}
		$giocatori[$i]['partite'] = $partiteGiocate;
		$mediaVoto += $giocatori[$i]['voti'];
		$mediaPartite += $giocatori[$i]['partite'];
		$i++;
	}
	$contenttpl->assign('mediaVoto',substr($mediaVoto / $appo,0,4));
	$contenttpl->assign('mediaVotoAll',$mediaVoto / $appo);
	$contenttpl->assign('mediaPartite',substr($mediaPartite / $appo,0,4));
	$contenttpl->assign('mediaPartiteAll',$mediaPartite / $appo);
	$contenttpl->assign('giocatori',$giocatori);	
}

?>
