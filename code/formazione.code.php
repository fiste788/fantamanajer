<?php
require_once(INCDBDIR . "utente.db.inc.php");
require_once(INCDBDIR . "formazione.db.inc.php");
require_once(INCDBDIR . "evento.db.inc.php");
require_once(VIEWDIR . 'GiocatoreStatistiche.view.db.inc.php');
require_once(INCDBDIR . "punteggio.db.inc.php");

$filterSquadra = NULL;
if(isset($_POST['squadra']))
	$filterSquadra = $_POST['squadra'];
$contentTpl->assign('squadra',$filterSquadra);

$val = Utente::getList();
$contentTpl->assign('elencosquadre',$val);
	
if(PARTITEINCORSO == TRUE)
	header("Location: " . Links::getLink('altreFormazioni'));


$formazione = Formazione::getFormazioneBySquadraAndGiornata($_SESSION['idUtente'],GIORNATA);

$missing = 0;
$frega = 0;
$moduloAr = array('P'=>0,'D'=>0,'C'=>0,'A'=>0);
$ruo = array('P','D','C','A');
$elencoCap = array('C','VC','VVC');
if(!PARTITEINCORSO)
{
	$giocatori = GiocatoreStatistiche::getByField('idUtente',$_SESSION['idUtente']);

	$i = 0;
	while($formazione == FALSE && $i < GIORNATA)
	{
		$formazione = Formazione::getFormazioneBySquadraAndGiornata($_SESSION['idUtente'],GIORNATA - $i);
		$i ++;
	}
$firePHP->log($formazione);
$firePHP->log($giocatori);

	$contentTpl->assign('formazione',$formazione);
	$contentTpl->assign('giocatori',$giocatori);
	if(!empty($_POST))
	{
		$i = 0;
		$j = 0;
		foreach($_POST['gioc'] as $key=>$val)
		{
			$titolariAr[$i] = $val;
			$i++;
		}
		foreach($_POST['panch'] as $key=>$val)
		{
			$panchinariAr[$j] = $val;
			$j++;
		}
		foreach($_POST['cap'] as $key=>$val)
			$capitano[$key] = $val;
	}

	$contentTpl->assign('usedJolly',Formazione::usedJolly($_SESSION['idUtente']));
	if(isset($formazione->modulo))
		$contentTpl->assign('modulo',explode('-',$formazione->modulo));
	else
		$contentTpl->assign('modulo',NULL);
}
?>
