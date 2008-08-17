<?php 
require(INCDIR.'squadra.inc.php');
require(INCDIR.'formazione.inc.php');
require(INCDIR.'punteggi.inc.php');

$punteggiObj = new punteggi();
$squadraObj = new squadra();
$formazioneObj = new formazione();

$squadra = NULL;
$giornata = NULL;
if(isset($_GET['squad']))
	$squadra = $_GET['squad'];
if(isset($_GET['giorn']))
	$giornata = $_GET['giorn'];
	
$contenttpl->assign('getsquadra',$squadra);
$contenttpl->assign('getgiornata',$giornata);
$giornate = $punteggiObj->getGiornateWithPunt();
	
if(isset($_GET['giorn']) && $_GET['giorn']-1 >=0)
	$giornprec = $_GET['giorn']-1;	
else
	$giornprec = FALSE;
if(isset($_GET['giorn']) && $_GET['giorn']+1 <= $giornate)
	$giornsucc = $_GET['giorn']+1;	
else
	$giornsucc = FALSE;

if($squadra == NULL)
	$giornprec=$giornsucc=FALSE;

$contenttpl->assign('giornprec',$giornprec);
$contenttpl->assign('giornsucc',$giornsucc);

$contenttpl->assign('squadradett',$squadraObj->getSquadraById($squadra));
$contenttpl->assign('squadre',$squadraObj->getElencoSquadre());


$contenttpl->assign('punteggi',$punteggiObj->getAllPunteggi());

require(INCDIR.'giocatore.inc.php');
$giocatoreObj = new giocatore();

if($squadra != NULL && $giornata != NULL && $squadra > 0 && $squadra < 9 && $giornata > 0 && $giornata <= $giornate)
{	
	if($formazioneObj->getFormazioneBySquadraAndGiornata($squadra,$giornata) != FALSE)
	{
		//$result = $punteggiObj->calcolaPunti($giornata,$squadra,FALSE);
		$contenttpl->assign('somma',$punteggiObj->getPunteggi($squadra,$giornata));
		$contenttpl->assign('formazione',$giocatoreObj->getVotiGiocatoryByGiornataSquadra($giornata,$squadra));
		//echo "<pre>".print_r($giocatoreObj->getVotiGiocatoryById($giornata,$squadra),1)."</pre>";
		
	}
	else
	{
		$contenttpl->assign('formazione',2);
		$contenttpl->assign('somma',0);
	}
}
else
	$contenttpl->assign('formazione',FALSE);
	
/*$q = "SELECT * FROM giocatore;";
$exe = mysql_query($q);
while ($row = mysql_fetch_row($exe))
{
	$values[] = $row;	
}
echo "<pre>".print_r($values,1)."</pre>";
foreach ($values as $key => $val)
{
	$appo = explode(';',$val[5]);
	//echo $val[2]. " " .count($appo)."<br>";
	array_pop($appo);
	array_pop($appo);
	$values[$key][5] = implode(';',$appo).';';
}
echo "<pre>".print_r($values,1)."</pre>";
$q = "INSERT INTO giocatore2 (IdGioc,Nome,Cognome,Ruolo,Club,Voti,IdSquadra,IdGiocVec,idSquadraAcquisto) VALUES ";
foreach ($values as $key => $val)
{
	$q .= "(";
	foreach ($val as $key2 => $val2)
	{
		$q .= "'" . addslashes($val2) . "',";
	}
	$q = substr($q,0,-1);
	$q .= "),";
}
$q = substr($q,0,-1);
$q .= ";";
echo $q;
mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());*/
?>
