<?php
require_once(INCDIR . 'club.db.inc.php');
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'mail.inc.php');

$filterClub = NULL;
if(isset($_POST['club']))
	$filterClub = $_POST['club'];
if(isset($_GET['club']))
	$filterClub = $_GET['club'];
	
$clubdett = Club::getClubById($filterClub);
$elencoClub = Club::getElencoClub();

if(isset($elencoClub[$filterClub - 1]))
{
	$idPrec = $filterClub - 1;
	$quickLinks->prec->href = Links::getLink('dettaglioClub',array('club'=>$idPrec));
	$quickLinks->prec->title = $elencoClub[$idPrec]->nomeClub;
}	
else
	$quickLinks->prec = FALSE;

if(isset($elencoClub[$filterClub + 1]))
{
	$idSucc = $filterClub + 1;
	$quickLinks->succ->href = Links::getLink('dettaglioClub',array('club'=>$idSucc));
	$quickLinks->succ->title = $elencoClub[$idSucc]->nomeClub;
}	
else
	$quickLinks->succ = FALSE;

$giocatori=Giocatore::getGiocatoriByIdClubWithStats($filterClub);
FirePHP::getInstance()->log($clubdett);
FirePHP::getInstance()->log($elencoClub);
$pathClub = CLUBSURL .$filterclub. '.png';
FirePHP::getInstance()->log($pathClub);
$contentTpl->assign('pathClub',$pathClub);
$contentTpl->assign('giocatori',$giocatori);
$contentTpl->assign('club',$filterClub);
$contentTpl->assign('clubDett',$clubdett);


$operationTpl->assign('elencoClub',$elencoClub);
$operationTpl->assign('idClub',$filterClub);

$layoutTpl->assign('quickLinks',$quickLinks);
?>
