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
$values = $giocatoreObj->getGiocatoryByIdSquadraWithStats($squadra);
if(($squadra != NULL) && ($values))
{

	$i = 0;
	$appo = 0;
	$mediaVoto = 0;
	$mediaPartite = 0;
	foreach($values as $key=>$val)
	{
		$giocatori[$i]['idGioc'] = $val[0];
		$giocatori[$i]['nome'] = $val[1] . " " . $val[2];
		$giocatori[$i]['ruolo'] = $ruoli[$val[3]];
		$giocatori[$i]['club'] = $val[5];
        $giocatori[$i]['votiAll']=$val[6];
        $giocatori[$i]['voti']=substr($val[6],0,4);
        $giocatori[$i]['partite']=$val[7];
        $giocatori[$i]['gol']=$val[8];
        $giocatori[$i]['assist']=$val[9];
		$mediaVoto += $giocatori[$i]['voti'];
		$mediaPartite += $giocatori[$i]['partite'];
		$i++;
	}
	$contenttpl->assign('mediaVoto',substr($mediaVoto / $i,0,4));
	$contenttpl->assign('mediaVotoAll',$mediaVoto / $i);
	$contenttpl->assign('mediaPartite',substr($mediaPartite / $i,0,4));
	$contenttpl->assign('mediaPartiteAll',$mediaPartite / $i);
	$contenttpl->assign('giocatori',$giocatori);	
}

?>
