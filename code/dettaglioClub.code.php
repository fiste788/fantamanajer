<?php
require_once(INCDBDIR . 'club.db.inc.php');
require_once(INCDBDIR . 'punteggio.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(VIEWDIR . 'ClubStatistiche.view.db.inc.php');
require_once(VIEWDIR . 'GiocatoreStatistiche.view.db.inc.php');
require_once(INCDIR . 'mail.inc.php');

$filterClub = NULL;
if(isset($_GET['club']))
	$filterClub = $_GET['club'];
if(isset($_POST['club']))
	$filterClub = $_POST['club'];
	
$clubdett = ClubStatistiche::getById($filterClub);
$elencoClub = Club::getList();
$keys = array_keys($elencoClub);
$current = array_search($filterClub,$keys);
if(isset($keys[($idPrec = $current - 1)]))
{
	$quickLinks->prec->href = Links::getLink('dettaglioClub',array('club'=>$keys[$idPrec]));
	$quickLinks->prec->title = $elencoClub[$keys[$idPrec]]->nomeClub;
}	
else
	$quickLinks->prec = FALSE;

if(isset($keys[($idSucc = $current + 1)]))
{
	$quickLinks->succ->href = Links::getLink('dettaglioClub',array('club'=>$keys[$idSucc]));
	$quickLinks->succ->title = $elencoClub[$keys[$idSucc]]->nomeClub;
}	
else
	$quickLinks->succ = FALSE;

$giocatori = GiocatoreStatistiche::getByField('idClub',$filterClub);
FirePHP::getInstance(true)->log($giocatori);
$pathClub = CLUBSURL . $filterClub . '.png';

$contentTpl->assign('pathClub',$pathClub);
$contentTpl->assign('giocatori',$giocatori);
$contentTpl->assign('club',$filterClub);
$contentTpl->assign('clubDett',$clubdett);


$operationTpl->assign('elencoClub',$elencoClub);
$operationTpl->assign('idClub',$filterClub);

?>
